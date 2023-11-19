<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Requests\UserPageUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserPageController extends Controller
{
    public function show(string $username) {
        $user = User::where("name", $username)->first();
		$user->profile_html = $this->sanitise_html($user->profile_html);
        return view("profile.show", ["user" => $user]);
    }

	public function edit() {
		$user = Auth::user();
		return view("profile.html.edit", ["user" => $user]);
	}

	public function update(UserPageUpdateRequest $request) {
		$profile_html = $request->validated()["profile_html"];
		$request->user()->profile_html = $profile_html;
		$request->user()->customised = strlen(trim($profile_html)) > 0;
		$request->user()->save();
		return Redirect::route('profile.html.edit')->with('status', 'profile-updated');
	}

	private function sanitise_html($string) {
		// Sanitise the string before rendering, but don't transform it in database because we don't want the user to lose their work.
		$blocked = implode(
			"|",
			["script", "style", "title", "head", "body"]
		);
		$re = "/<\/?($blocked).*?>/";
		error_log($re);
		return preg_replace($re, "", $string);
	}
}
