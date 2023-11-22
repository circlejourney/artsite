<?php

namespace App\Http\Controllers;

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
		return view("admin.user.edit", ["user" => $user]);
	}
    function index_roles() {
		$roles = Role::all();
		return view("admin.role.index", ["roles" => $roles]);
	}
}