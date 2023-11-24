<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Artwork;

class AdminPageController extends Controller
{
	function index(Request $request) {
		$roles = $request->user()->roles();
		return view("admin.index", ["roles" => $roles]);
	}

    function index_users() {
		$users = User::all();
		return view("admin.user.index", ["users" => $users]);
	}

	function edit_user(Request $request, User $user) {
		$controlledRoles = $request->user()->controlsRoles();
		$user_roles = $user->roles->pluck("id");
		return view("admin.user.edit", ["user" => $user, "roles" => $controlledRoles, "user_roles" => $user_roles]);
	}

	function update_user(User $user, Request $request) {
		$controlledRoleIDs = $request->user()->controlsRoles()->pluck("id");
		foreach($request->roles as $role) {
			if($controlledRoleIDs->doesntContain(intval($role))) abort(403);
		}
		$user->roles()->sync($request->roles);
		return Redirect::back()->with("success", "User roles updated successfully.");
	}

    function index_roles(Request $request) {
		$roles = $request->user()->controlsRoles();
		return view("admin.role.index", ["roles" => $roles]);
	}

	function update_roles(Role $role, Request $request) {
		$role->fill($request->all());
		return Redirect::back()->with("success", "Role updated successfully.");

	}

	function index_artworks(Request $request) {
		$artworks = Artwork::all();
		return view("admin.art.index", ["artworks" => $artworks]);
	}

}