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
			$imagepaths[] = $uploadService->upload($image, "art/".$request->user()->name);
		};

		$thumb = $uploadService->generate_thumbnail($imagepaths[0], "art/".$request->user()->name, 300);
		
		$path = $this->makeURL($request->title)."-".Str::random(8);

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

	public function showdelete(Request $request, string $path) {
		$artwork = Artwork::where("path", $path)->first();
		$owner_ids = $this->get_owners($artwork);

		if(!$owner_ids->contains($request->user()->id)) return route("art",["path" => $artwork->path]);

		return view("art.delete", ["artwork" => $artwork]);
	}

	public function delete(Request $request, string $path, UploadService $uploadService) {
		$artwork = Artwork::where("path", $path)->first();
		error_log($artwork->id);
		foreach($artwork->images as $image) {
			$uploadService->delete($image);
		}
		Artwork::destroy($artwork->id);
		return redirect(route("user", ["username" => $request->user()->id]));
	}

	private function makeURL(string $string) {
		$stringparts = explode(" ", $string);
		$string = implode("-", array_slice($stringparts, 0, 10));
		return strtolower(preg_replace("/[^A-Za-z0-9]+/", "-", $string));
	}

	private function get_owners(Artwork $artwork) {
		$owner_ids = $artwork->users()->get()
			->map(function($user) {
				return $user->id;
			});
		return $owner_ids;
	}
}
