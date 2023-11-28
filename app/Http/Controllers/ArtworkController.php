<?php

namespace App\Http\Controllers;
use App\Models\Artwork;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\ArtworkRequest;
use App\Services\UploadService;
use App\Services\SanitiseService;
use App\Services\PrivacyLevelService;
use App\Services\TaggerService;

class ArtworkController extends Controller
{
    public function show(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
		$ownerIDs = $artwork->users()->get()->pluck("id");
		
		$maxPrivacyAllowed = PrivacyLevelService::getMaxPrivacyAllowed($request->user(), $ownerIDs);
		$artworkprivacy = $artwork->folders()->get()->map(function($i) { return $i->getLineagePrivacyLevel(); })->max();

		if($artworkprivacy > $maxPrivacyAllowed) abort(401);

		$folders = $artwork->folders()
			->orderBy("artwork_folder.created_at")
			->get()
			->reject(function($i){ return $i->id == $i->user()->first()->top_folder_id; });
		$image_urls = $artwork->getImageURLs();
		$owner_ids = $artwork->getOwners();
		$artwork_text = $artwork->getText();
		$params = ["artwork" => $artwork, "text" => $artwork_text, "image_urls" => $image_urls ,"owner_ids" => $owner_ids, "folders" => $folders];
		if($owner_ids->count() == 1) $params["user"] = $artwork->users()->get()->first();
		return view("art.show", $params);
	}
	
	public function create(Request $request) {
		$folderlist = $request->user()->getFolderTree(false);
		return view("art.create", ["folderlist" => $folderlist]);
	}


	/* Store new artwork submission */
	public function store(ArtworkRequest $request) {
		$validated = $request->validated();
		$imagepaths = array();

		$path = SanitiseService::makeURL($request->title, 10, 8);
		$artwork = Artwork::create([
            'title' => $validated["title"],
            'images' => $imagepaths,
			'path' => $path,
			'searchable' => !$request->not_searchable
        ]);
		
		$folderIDs=[];
		if($request->parent_folder && $request->user()->folders()->get()->contains($request->parent_folder)) {
			$folderIDs[] = $request->parent_folder;
		} else {
			$folderIDs[] = $request->user()->top_folder_id;
		}

		$artistIDs = array( $request->user()->id );
		foreach($request->artist as $artist) {
			// Loop through guest artists (not the user making the request) and add their top folders
			if(!$artist) continue;
			$guestArtist = User::where("name", $artist)->first();
			$artistIDs[] = $guestArtist->id;
			$folderIDs[] = $guestArtist->top_folder_id;
		}

		$artwork->users()->attach($artistIDs);
		$artwork->folders()->attach($folderIDs);
		
		TaggerService::tagArtwork($artwork, explode(",", $request->tags));

		foreach(array_filter($request->images) as $i => $image) {
			if(!$image) continue;
			$artwork->writeImage($i, $image, $request->user());
		};

		$artwork->updateThumbnail()->updateText($request->text)->save();

		return redirect(route("art", ["path" => $path]));
	}


	/* Artwork edit form */
	public function edit(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
		if($artwork->users()->get()->doesntContain($request->user())
			&& !$request->user()->hasPermissions("manage_artworks")) abort(403);

		$folderlist = $request->user()->getFolderTree(false);
		$selectedfolder = $artwork->folders()->get()
			->intersect($request->user()->folders()->get())->first()->id;
		$text = $artwork->getText();
		$image_urls = $this->getImageURLs($artwork->images);
		return view("art.edit", [
			"artwork" => $artwork,
			"image_urls" => $image_urls,
			"folderlist" => $folderlist,
			"text" => $text,
			"selectedfolder" => $selectedfolder]);
	}

	
	/* Update the artwork */
	public function update(string $path, Request $request) {
		
		$artwork = Artwork::byPath($path);
		if($artwork->users()->get()->doesntContain($request->user())
			&& !$request->user()->hasPermissions("manage_artworks")) abort(403);

		$parentfolderID = $request->parent_folder ? intval($request->parent_folder) : $request->user()->top_folder_id;

		if($request->user()->folders()->get()->contains($parentfolderID)) {
			$keepfolders = $artwork->folders()->get()
				->diff( $request->user()->folders()->get() )
				->pluck("id")
				->push( $parentfolderID );
			$artwork->folders()->sync($keepfolders);
		}

		$artwork->title = $request->title;
		$artwork->searchable = !$request->not_searchable;
		$artwork->updateText($request->text ?? "");
		
		if($request->artist) {
			$currentartists = $artwork->users()->get()->map(function($i){ return $i->name; });
			$incomingartists = collect($request->artist);
			$removeArtists = $currentartists->diff($incomingartists);
			$addArtists = $incomingartists->diff($currentartists);
			if($removeArtists) {
				foreach($removeArtists as $artistname) {
					if(!$artistname) continue;
					if(!$artist = User::where("name", $artistname)->first()) continue;
					$artwork->removeForeignUser($artist);
				}
			}
			if($addArtists) {
				foreach($request->artist as $artistname) {
					// Loop through guest artists (not the user making the request)
					if(!$artistname) continue;
					if(!$artist = User::where("name", $artistname)->first()) continue;
					if($artwork->users()->get()->contains($artist)) continue;
					$artwork->addForeignUser($artist);
				}	
			}
		}

		TaggerService::tagArtwork($artwork, explode(",", $request->tags));

		$images = $artwork->images;
		$imageTransforms = collect($request->image_order)->map(function($order) use($images) {
			return collect([ "old_index" => $order !== "false" ? intval($order) : null, "url" => $images[$order] ?? null ]);
		});

		foreach($imageTransforms as $i => $imageTransform) {
			$toReplace = $imageTransform["old_index"];
			if($toReplace == null && $request->images && isset($request->images[$i])) {
				$relpath = $artwork->uploadImage($request->images[$i], $request->user())->getRelativePath();
				$imageTransforms[$i]->put("url", $relpath);
				continue;
			}

			if($request->delete_image && $request->delete_image[$i] == "true") {
				$artwork->deleteImage($toReplace);
				$imageTransforms->forget($i);
				continue;
			}

			if($request->images && isset($request->images[$i])) {
				if(isset($images[$toReplace])) {
					$artwork->deleteImage($toReplace);
					$relpath = $artwork->uploadImage($request->images[$i], $request->user())->getRelativePath();
					$imageTransforms[$i]->put("url", $relpath);
				}
			}
		}
		$newImages = $imageTransforms->pluck("url")->filter()->all();
		$artwork->images = $newImages;
		$artwork->save();

		if($newImages[0] !== $images[0]) {
			$artwork->updateThumbnail()->save();
		}

		return redirect()->route('art', ["path" => $path])->with('success', 'Post updated successfully.');
	}

	public function manage(Request $request) {
		$user = $request->user();
		return view("art.manage", ["user" => $user]);
	}

	public function put(Request $request) {
		$request->user()->update([
			"highlights" => collect($request->update_art)->map(function($i) { return intval($i); })
		]);
		return view("art.manage", ["user" => $request->user()]);
	}

	public function showdelete(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
		$owner_ids = $this->getOwners($artwork);
		if(!$owner_ids->contains($request->user()->id)) return route("art",["path" => $artwork->path]);
		return view("art.delete", ["artwork" => $artwork]);
	}

	public function delete(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
		TaggerService::tagArtwork($artwork, []);
		if($artwork->thumbnail) UploadService::find($artwork->thumbnail)->delete();
		$artwork->deleteText()->deleteThumbnail()->deleteAllImages();
		Artwork::destroy($artwork->id);
		return redirect(route("user", ["username" => $request->user()->name]));
	}

	private function getOwners(Artwork $artwork) {
		$owner_ids = $artwork->users()->get()
			->map(function($user) {
				return $user->id;
			});
		return $owner_ids;
	}

	private function getImageURLs($images) {
		$image_urls = array_map(function($image){
			return Storage::url($image);
		}, $images);
		return $image_urls;
	}
}
