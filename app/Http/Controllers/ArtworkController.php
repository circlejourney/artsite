<?php

namespace App\Http\Controllers;
use App\Models\Artwork;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UploadService;
use Illuminate\Support\Facades\Auth;

class ArtworkController extends Controller
{
    public function show(string $path) {
		$artwork = Artwork::where("path", $path)->first();
		$image_urls = array_map(function($image){
			return Storage::url($image);
		}, $artwork->images);
		$owner_ids = $this->get_owners($artwork);
		return view("art.show", ["artwork" => $artwork, "image_urls" => $image_urls ,"owner_ids" => $owner_ids]);
	}
	
	public function create() {
		return view("art.create");
	}

	public function store(Request $request, UploadService $uploadService) {
		$imagepaths = array();

		foreach($request->images as $image) {
			if(!$image) continue;
			$imagepaths[] = $uploadService->upload($image, "art/".$request->user()->name);
		};

		$thumb = sizeof($imagepaths) > 0 ? $uploadService->generate_thumbnail($imagepaths[0], "art/".$request->user()->name, 300) : null;
		
		$path = $this->makeURL($request->title);

		$artwork = Artwork::create([
            'title' => $request->title,
			'text' => $request->text,
            'images' => $imagepaths,
			'thumbnail' => $thumb,
			'path' => $path
        ]);
		$artwork->users()->attach($request->user()->id);
		return redirect("/works/".$path);
	}

	public function edit(string $path) {
		$artwork = Artwork::where("path", $path)->first();
		return view("art.edit", ["artwork" => $artwork]);
	}

	public function update(Request $request) {

	}

	public function showdelete(Request $request, string $path) {
		$artwork = Artwork::where("path", $path)->first();
		$owner_ids = $this->get_owners($artwork);

		if(!$owner_ids->contains($request->user()->id)) return route("art",["path" => $artwork->path]);

		return view("art.delete", ["artwork" => $artwork]);
	}

	public function delete(Request $request, string $path, UploadService $uploadService) {
		$artwork = Artwork::where("path", $path)->first();
		if($artwork->thumbnail) $uploadService->delete($artwork->thumbnail);
		foreach($artwork->images as $image) {
			$uploadService->delete($image);
		}
		Artwork::destroy($artwork->id);
		return redirect(route("user", ["username" => $request->user()->name]));
	}

	private function makeURL(string $string) {
		$stringparts = explode(" ", $string);
		$string = implode("-", array_slice($stringparts, 0, 10));
		return trim(strtolower(preg_replace("/[^A-Za-z0-9]+/", "-", $string)), "-")."-".Str::random(8);
	}

	private function get_owners(Artwork $artwork) {
		$owner_ids = $artwork->users()->get()
			->map(function($user) {
				return $user->id;
			});
		return $owner_ids;
	}
}
