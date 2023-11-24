<?php

namespace App\Http\Controllers;
use App\Models\Artwork;
use App\Models\User;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\ArtworkRequest;
use App\Services\UploadService;
use App\Services\SanitiseService;
use App\Services\FolderListService;
use Illuminate\Support\Facades\Auth;

class ArtworkController extends Controller
{
    public function show(string $path) {
		$artwork = Artwork::byPath($path);
		$image_urls = $this->getImageURLs($artwork->images);
		$owner_ids = $this->getOwners($artwork);
		$artwork_text = $artwork->getText();
		return view("art.show", ["artwork" => $artwork, "text" => $artwork_text, "image_urls" => $image_urls ,"owner_ids" => $owner_ids]);
	}
	
	public function create(Request $request) {
		$folderlist = $request->user()->getFolderTree();
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
			'path' => $path
        ]);
		
		$folderIDs=[];
		if($request->parent_folder && $request->user()->folders()->get()->contains($request->parent_folder)) {
			$folderIDs[] = $request->parent_folder;
		} else {
			$folderIDs[] = $request->user()->top_folder_id;
		}

		$artistIDs = array( $request->user()->id );
		foreach($request->artist as $artist) {
			// Loop through guest artists (not the user making the request)
			if(!$artist) continue;
			$guestArtist = User::where("name", $artist)->first();
			$artistIDs[] = $guestArtist->id;
			$folderIDs[] = $guestArtist->top_folder_id;
		}

		$artwork->users()->attach($artistIDs);
		$artwork->folders()->attach($folderIDs);

		foreach(array_filter($request->images) as $i => $image) {
			if(!$image) continue;
			$artwork->writeImage($i, $image, $request->user());
		};

		$artwork->generateThumbnail()->updateText($request->text)->save();

		return redirect(route("art", ["path" => $path]));
	}


	/* Artwork edit form */
	public function edit(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
		if($artwork->users()->get()->doesntContain($request->user())
			&& !$request->user()->hasPermissions("manage_artworks")) abort(403);

		$folderlist = $request->user()->getFolderTree();
		$text = $artwork->getText();
		$image_urls = $this->getImageURLs($artwork->images);
		return view("art.edit", ["artwork" => $artwork, "image_urls" => $image_urls, "folderlist" => $folderlist, "text" => $text]);
	}

	
	/* Update the artwork */
	public function update(string $path, Request $request) {
		$artwork = Artwork::byPath($path);
		if($artwork->users()->get()->doesntContain($request->user())
			&& !$request->user()->hasPermissions("manage_artworks")) abort(403);	

		if($request->parent_folder && $request->user()->folders()->get()->contains($request->parent_folder)) {
			$keepfolders = $artwork->folders()->get()
				->diff( $request->user()->folders() )
				->pluck("id")
				->push( $request->parent_folder );
			error_log($keepfolders);
			$artwork->folders()->sync($keepfolders);
		}

		$artwork->title = $request->title;
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

		if($request->images) {
			foreach($request->images as $i => $image) {
				if(!$image) continue;
				$artwork->writeImage($i, $image, $request->user())->save();
			};
		}

		if($request->delete_image){
			foreach($request->delete_image as $i => $delete) {
				if($delete === "true") $artwork->deleteImage($i)->save();
			}
		}
			
		if($request->images && $request->images[0] !== null || $request->delete_image[0] !== null) {
			$artwork->generateThumbnail()->save();
		}

		$artwork->save();

		return redirect()->route('art', ["path" => $path])->with('success', 'Post updated successfully.');
	}

	public function showdelete(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
		$owner_ids = $this->getOwners($artwork);
		if(!$owner_ids->contains($request->user()->id)) return route("art",["path" => $artwork->path]);
		return view("art.delete", ["artwork" => $artwork]);
	}

	public function delete(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
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
