<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$roles = DB::table("roles")->pluck("name");

		if($roles->doesntContain("founder")) {
			DB::table("roles")->insert([
				"name" => "founder",
				"manage_users" => false,
				"manage_roles" => false,
				"manage_artworks" => false,
				"change_own_flair" => true,
				"default_flair" => "crown" // Font Awesome selector
			]);
		}

		if($roles->doesntContain("admin")) {
			DB::table("roles")->insert([
				"name" => "admin",
				"manage_users" => true,
				"manage_roles" => true,
				"manage_artworks" => true,
				"change_own_flair" => true,
				"default_flair" => "crown"
			]);
		}
		
		if($roles->doesntContain("mod")) {
			DB::table("roles")->insert([
				"name" => "mod",
				"manage_users" => true,
				"manage_artworks" => true,
				"change_own_flair" => false,
				"default_flair" => "star"
			]);
		}

		if($roles->doesntContain("user")) {
			DB::table("roles")->insert([
				"name" => "user",
				"manage_users" => false,
				"manage_artworks" => false,
				"change_own_flair" => false,
				"default_flair" => "user"
			]);
		}

		$users = DB::table("users")->get();
		$user_role = Role::where("name", "user")->first()->id;

		foreach($users as $user_row) {
			$user = User::where("id", $user_row->id)->first();
			if($user->roles()->count() > 0) continue;
			$user->roles()->attach($user_role);
		}
    }
}
