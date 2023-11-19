<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserPageController extends Controller
{
    public function show(string $username) {
        $user = User::where("name", $username)->first();
        return view("profile.show", ["user" => $user]);
    }
}
