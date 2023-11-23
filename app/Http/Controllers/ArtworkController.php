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
		$folderlist = Folder::getUserFolder($request->user());
		return view("art.create", ["folderlist" => $folderlist]);
	}

	public function store(ArtworkRequest $request) {
		$validated = $request->validated();
		$imagepaths = array();

		$path = SanitiseService::makeURL($request->title, 10, 8);
		$artwork = Artwork::create([
            'title' => $validated["title"],
            'images' => $imagepaths,
			'path' => $path
        ]);		

		foreach(array_filter($request->images) as $i => $image) {
			if(!$image) continue;
			$artwork->writeImage($i, $image, $request->user());
		};

		$artwork->generateThumbnail()->updateText($request->text)->save();
		
		$folderIDs=[];
		if($request->folder && Folder::where("id", $request->folder)->user == $request->user) {
			$folderIDs[] = $request->folder;
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

		return redirect(route("art", ["path" => $path]));
	}

	public function edit(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
		$folderlist = Folder::getUserFolder($request->user());
		$text = $artwork->getText();
		$image_urls = $this->getImageURLs($artwork->images);
		return view("art.edit", ["artwork" => $artwork, "image_urls" => $image_urls, "folderlist" => $folderlist, "text" => $text]);
	}

	public function update(string $path, Request $request) {
		$artwork = Artwork::byPath($path);

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

		if($request->parent_folder) {
			error_log($request->user()->folders()->get()->contains($request->parent_folder));
		}

		$artwork->title = $request->title;
		$artwork->updateText($request->text ?? "");
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
