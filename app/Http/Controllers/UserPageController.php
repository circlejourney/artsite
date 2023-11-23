<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserPageUpdateRequest;
use App\Services\UploadService;
use App\Services\SanitiseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class UserPageController extends Controller
{
    public function show(string $username) {
        $user = User::where("name", $username)->first();
		if(!$user) abort(404);
		$avatar_url = $user->avatar ? Storage::url($user->avatar) : "/images/user.png";
		$preview_artworks = $user->artworks()->limit(10)->orderBy("created_at", "desc")->get();
		$profile_html = $user->getProfileHTML() ?? "";
        return view("profile.show", ["user" => $user, "avatar_url" => $avatar_url, "artworks" => $preview_artworks, "profile_html" => $profile_html]);
    }

	public function edit(Request $request) {
		$user = $request->user();
		$user->avatar_url = $user->avatar ? Storage::url($user->avatar) : "/images/user.png";
		$profile_html = $user->getProfileHTML() ?? "";
		return view("profile.html.edit", ["user" => $user, "profile_html" => $profile_html]);
	}

	public function update(UserPageUpdateRequest $request) {
		// Validate HTML
		$profile_html = $request->validated()["profile_html"] ?? "";
		$request->user()->updateProfileHTML($profile_html);
		$request->user()->customised = strlen(trim($profile_html)) > 0;
		if($request->avatar)
		{
			$request->user()->updateAvatar($request->avatar);
		}
		$request->user()->save();
		return Redirect::route('profile.html.edit')->with('status', 'Profile updated successfully.');
	}
}
