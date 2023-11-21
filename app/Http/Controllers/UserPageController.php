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
		$user->profile_html = SanitiseService::sanitiseHTML($user->profile_html);
		$avatar_url = $user->avatar ? Storage::url($user->avatar) : "/images/user.png";
		$preview_artworks = $user->artworks()->limit(10)->orderBy("created_at", "desc")->get();
        return view("profile.show", ["user" => $user, "avatar_url" => $avatar_url, "artworks" => $preview_artworks]);
    }

	public function edit() {
		$user = Auth::user();
		$user->avatar_url = $user->avatar ? Storage::url($user->avatar) : "/images/user.png";
		return view("profile.html.edit", ["user" => $user]);
	}

	public function update(UserPageUpdateRequest $request) {
		// Validate HTML
		$profile_html = $request->validated()["profile_html"];
		$request->user()->profile_html = $profile_html;
		$request->user()->customised = strlen(trim($profile_html)) > 0;
		if($request->avatar)
		{
			if($request->user()->avatar) UploadService::find($request->user()->avatar)->delete();
			$avatar = UploadService::upload($request->validated()["avatar"], "avatars/".$request->user()->name)
				->resizeToFit(300)->getRelativePath();
			$request->user()->avatar = $avatar;
		}
		$request->user()->save();
		return Redirect::route('profile.html.edit')->with('status', 'profile-updated');
	}
}
