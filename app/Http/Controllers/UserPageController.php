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
        return view("profile.show", ["user" => $user]);
    }

	public function edit() {
		$user = Auth::user();
		return view("profile.html.edit", ["user" => $user]);
	}

	public function update(UserPageUpdateRequest $request) {
		$request->user()->profilehtml = $request->validated()["profilehtml"];
		$request->user()->save();
		return Redirect::route('profile.html.edit')->with('status', 'profile-updated');
	}
}
