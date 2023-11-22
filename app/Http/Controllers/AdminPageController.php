<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class AdminPageController extends Controller
{
    function index_users() {
		$users = User::all();
		return view("admin.user.index", ["users" => $users]);
	}

	function edit_user(User $user) {
		$roles = Role::all();
		$user_roles = $user->roles->map(function($i){
			return $i->id;
		});
		return view("admin.user.edit", ["user" => $user, "roles" => $roles, "user_roles" => $user_roles]);
	}

	function update_user(User $user, Request $request) {
		$user->roles()->sync($request->roles);
		return Redirect::back()->with("success", "User roles updated successfully.");
	}

    function index_roles() {
		$roles = Role::all();
		return view("admin.role.index", ["roles" => $roles]);
	}

}