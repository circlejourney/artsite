<?php

namespace App\Http\Controllers;
use App\Models\Artwork;
use App\Models\User;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
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
		if($artwork->text) {
			$artwork->text = SanitiseService::sanitiseHTML($artwork->text);
		}
		return view("art.show", ["artwork" => $artwork, "image_urls" => $image_urls ,"owner_ids" => $owner_ids]);
	}
	
	public function create(Request $request) {
		$folders = $request->user()->folders();
		$folderlist = FolderListService::class($folders)->makeTree(); //Folder::makeTree($folders);
		return view("art.create", ["folders" => $folderlist]);
	}

	public function store(Request $request) {
		$imagepaths = array();
		foreach($request->images as $image) {
			if(!$image) continue;
			$imagepaths[] = UploadService::upload($image, "art/".$request->user()->name)->getRelativePath();
		};
		$thumb = sizeof($imagepaths) > 0 ? UploadService::find($imagepaths[0])->makeThumbnail(300)->getRelativePath() : null;
		$path = SanitiseService::makeURL($request->title, 10, 8);
		$artwork = Artwork::create([
            'title' => $request->title,
			'text' => $request->text,
            'images' => $imagepaths,
			'thumbnail' => $thumb,
			'path' => $path
        ]);
		
		$artistIDs = array( $request->user()->id );
		foreach($request->artist as $artist) {
			if(!$artist) continue;
			$artistIDs[] = User::where("name", $artist)->first()->id;
		}
		$artwork->users()->attach($artistIDs);

		return redirect(route("art", ["path" => $path]));
	}

	public function edit(string $path) {
		$artwork = Artwork::byPath($path);
		$image_urls = $this->getImageURLs($artwork->images);
		return view("art.edit", ["artwork" => $artwork, "image_urls" => $image_urls]);
	}

	public function update(string $path, Request $request) {
		$artwork = Artwork::byPath($path);

		foreach($request->images as $i => $image) {
			if(!$image) continue;
			UploadService::find($artwork->images[$i])->delete();
			$imageupload = UploadService::upload($image, "art/".$request->user()->name);
			$imagepath = $imageupload->getRelativePath();

			$images = $artwork->images;
			$images[$i] = $imagepath;
			$artwork->update(['images' => $images]);
			
			if($i == 0) {
				UploadService::find($artwork->thumbnail)->delete();
				$thumbpath = $imageupload->makeThumbnail(300)->getRelativePath();
				$artwork->update(["thumbnail" => $thumbpath]);
			}
		};

		$artwork->update([
			"title" => $request->title,
			"text" => $request->text
		]);
		return redirect()->route('art', ["path" => $path])->with('success', 'Post updated successfully.');
	}

	public function showdelete(Request $request, string $path) {
		$artwork = Artwork::byPath($path);
		$owner_ids = $this->getOwners($artwork);
		if(!$owner_ids->contains($request->user()->id)) return route("art",["path" => $artwork->path]);
		return view("art.delete", ["artwork" => $artwork]);
	}

	public function delete(Request $request, string $path) {
		$artwork = Artwork::where("path", $path)->first();
		if($artwork->thumbnail) UploadService::find($artwork->thumbnail)->delete();
		foreach($artwork->images as $image) {
			if($image && gettype($image) == "string") UploadService::find($image)->delete();
		}
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
