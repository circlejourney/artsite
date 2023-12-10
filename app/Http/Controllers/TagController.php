<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Tag;
use App\Models\TagHighlight;
use App\Models\User;
use App\Services\PrivacyLevelService;
use App\Services\SanitiseService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of user's tags.
     */
    public function index_user(string $username)
    {
		$user = User::where("name", $username)->firstOrFail();
		$tags = $user->tags;
		return view("tags.index", ["user" => $user, "tags" => $tags]);
    }

	public function index_global() {
		$tags = Tag::all();
		return view("tags.index", ["tags" => $tags]);
	}

	public function show_global(Request $request) {
		$tagName = SanitiseService::of($request->query("tag"))->makeTag()->get();

		$taggedArtworks = Artwork::whereHas("tags", function($query) use($tagName){
			return $query->where("name", $tagName);
		})->where("searchable", true)
			->get()->reject(function($artwork) {
				$maxPrivacyAllowed = PrivacyLevelService::getMaxPrivacyAllowed(auth()->user(), $artwork->users()->get()->pluck("id"));
				$artworkPrivacy = $artwork->getPrivacyLevel();
				return $artworkPrivacy > $maxPrivacyAllowed;
			});
		return view("tags.show-global", ["tagName" => $tagName, "artworks" => $taggedArtworks]);

	}

	public function index_manage() {
		$user = request()->user();
		$tags = $user->tags()->get()->sortByDesc("tag_highlight.created_at");
		return view("tags.manage", ["tags" => $tags]);
	}

	public function edit(string $tagname) {
		$user = request()->user();
		$tag = $user->tags()->where("name", $tagname)->firstOrFail();
		return view("tags.edit", ["tag" => $tag]);
	}

	public function store_or_update(Request $request, string $tagname) {
		$user = $request->user();
		$tag = $user->tags()->where("name", $tagname)->first();
		if(!$tag) abort(403);

		$request->validate([
			"text" => ["string"],
			"thumbnail" => ["mimes:jpg,jpeg,png,gif", "max:2048"]
		]);
		$cleanText = SanitiseService::of($request->text)->sanitiseHTML()->get();
		
		if(!$tag_highlight = TagHighlight::where("tag_id", $tag->id)->first()) {
			$tag_highlight = TagHighlight::create([
				"text" => $cleanText,
				"tag_id" => $tag->id
			]);
		} else {
			$tag_highlight->update([ "text" => $cleanText ]);
		}

		if($request->thumbnail) {
			$tag_highlight->uploadThumbnail($request->thumbnail, $user);
		}

		return view("tags.edit", ["tag" => $tag])->with("success", "Tag highlight saved successfully.");
	}

	public function update(Request $request, string $tagname) {
		$request->validate([
			"text" => ["string"],
			"thumbnail" => ["mimes:jpg,jpeg,png,gif", "max:2048"]
		]);
	}
}
