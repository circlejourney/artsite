<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Artwork;
use Illuminate\Http\Request;
use App\Http\Requests\UserPageUpdateRequest;
use App\Models\Notification;
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

	public function follow(User $user) {
		$follower = auth()->user();
		if($follower->id == $user->id) return response(["user" => $user->id, "action" => 0]);
		if($follower->follows->doesntContain($user)) {
			$follower->follows()->attach($user->id);

			Notification::dispatch($follower, collect([$user]), collect([ "type" => "follow" ]));
			return response(["user" => $user->id, "action" => 1]);
			
		} else {
			$follower->follows()->detach($user->id);
			return response(["user" => $user->id, "action" => -1]);
		}
	}

	public function show_stats(string $username) {
		$user = User::where("name", $username)->firstOrFail();
		return view("profile.stats", ["user" => $user]);
	}


	public function index_faves(string $username) {
		$user = User::where("name", $username)->firstOrFail();
		return view("profile.faves", ["user" => $user]);
	}

	public function invite(User $user) {
		// Get only authed user's collectives where the selected user is not a member
		$collectives = auth()->user()->collectives()->whereDoesntHave("members", function($q) use($user) {
			$q->where("user_id", $user->id);
		})->get();
		

		if($collectives->count() === 0) {
			return redirect()->back()->withErrors("User is already a member of all your collectives.");
		}

		return view("profile.invite", ["user" => $user, "collectives" => $collectives]);
	}
}
