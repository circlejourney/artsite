<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Tag;
use App\Models\User;
use App\Services\PrivacyLevelService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_user(string $username)
    {
		$user = User::where("name", $username)->firstOrFail();
        $tags = $user->getTags();
		
		return view("tags.index", ["user" => $user, "tags" => $tags]);
    }

	public function index_global() {
		$tags = Tag::all();
		return view("tags.index", ["tags" => $tags]);
	}

	public function show_global(Request $request) {
		$tagID = $request->query("tag");

		$taggedArtworks = Artwork::whereHas("tags",  function($query) use($tagID){
			return $query->where("id", $tagID);
		})->get()->reject(function($artwork) {
			if(!$artwork->searchable) return true;
			$maxPrivacyAllowed = PrivacyLevelService::getMaxPrivacyAllowed(auth()->user(), $artwork->users()->get()->pluck("id"));
			$artworkPrivacy = $artwork->getPrivacyLevel();
			return $artworkPrivacy > $maxPrivacyAllowed;
		});
		return view("tags.show-global", ["tagID" => $tagID, "artworks" => $taggedArtworks]);
	}
}
