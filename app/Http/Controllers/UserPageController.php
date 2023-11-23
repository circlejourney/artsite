<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Requests\UserPageUpdateRequest;
use App\Services\UploadService;
use App\Services\SanitiseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserPageController extends Controller
{
    public function show(string $username) {
        $user = User::where("name", $username)->first();
		if(!$user) abort(404);
		$avatar_url = $user->avatar ? Storage::url($user->avatar) : "/images/user.png";
		$preview_artworks = $user->artworks()->limit(10)->orderBy("created_at", "desc")->get();
		$profile_html = $user->profile_html ? SanitiseService::sanitiseHTML(Storage::get($user->profile_html)) : "";
        return view("profile.show", ["user" => $user, "avatar_url" => $avatar_url, "artworks" => $preview_artworks, "profile_html" => $profile_html]);
    }

	public function edit() {
		$user = Auth::user();
		$user->avatar_url = $user->avatar ? Storage::url($user->avatar) : "/images/user.png";
		$profile_html = $user->profile_html ? Storage::get($user->profile_html) : "";
		return view("profile.html.edit", ["user" => $user, "profile_html" => $profile_html]);
	}

	public function update(UserPageUpdateRequest $request) {
		// Validate HTML
		$profile_html = $request->validated()["profile_html"];
		$target_folder = "profiles/".$request->user()->id;
		UploadService::create("html.txt", $profile_html, "profiles/".$request->user()->id);
		$request->user()->profile_html = "$target_folder/html.txt";
		$request->user()->customised = strlen(trim($profile_html)) > 0;
		if($request->avatar)
		{
			if($request->user()->avatar) UploadService::find($request->user()->avatar)->delete();
			$avatar = UploadService::upload($request->validated()["avatar"], "avatars/".$request->user()->id)
				->resizeToFit(300)->getRelativePath();
			$request->user()->avatar = $avatar;
		}
		$request->user()->save();
		return Redirect::route('profile.html.edit')->with('status', 'Profile updated successfully.');
	}
}
