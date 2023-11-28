<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Artwork;
use Illuminate\Http\Request;
use App\Http\Requests\UserPageUpdateRequest;
use App\Models\PrivacyLevel;
use App\Services\UploadService;
use App\Services\SanitiseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use App\Services\PrivacyLevelService;

class UserPageController extends Controller
{
    public function show(Request $request, string $username) {
        $user = User::where("name", $username)->firstOrFail();
		
		$preview_artworks = PrivacyLevelService::filterArtworkCollection($request->user(), $user->artworks()->limit(10)->orderBy("created_at", "desc")->get());
		
		$profile_html = $user->getProfileHTML() ?? "";
		$highlights = collect($user->highlights)->map(function($i) { return Artwork::where("id", $i)->first(); })->slice(0,3);
        return view("profile.show", ["user" => $user, "artworks" => $preview_artworks, "profile_html" => $profile_html, "highlights" => $highlights]);
    }

	public function edit(Request $request) {
		$user = $request->user();
		$profile_html = $user->getProfileHTML() ?? "";
		return view("profile.html.edit", ["user" => $user, "profile_html" => $profile_html]);
	}

	public function update(UserPageUpdateRequest $request) {
		// Validate HTML
		$profile_html = $request->validated()["profile_html"] ?? "";
		$request->user()->updateProfileHTML($profile_html);
		$request->user()->customised = $request->customised == "on";
		if($request->avatar)
		{
			$request->user()->updateAvatar($request->avatar);
		}
		if($request->banner)
		{
			$request->user()->updateBanner($request->banner);
		}
		$request->user()->save();
		return Redirect::route('profile.html.edit')->with('status', 'Profile updated successfully.');
	}


	public function show_stats(string $username) {
		$user = User::where("name", $username)->firstOrFail();
		return view("profile.stats", ["user" => $user]);
	}


	public function index_faves(string $username) {
		$user = User::where("name", $username)->firstOrFail();
		return view("profile.faves", ["user" => $user]);
	}
}
