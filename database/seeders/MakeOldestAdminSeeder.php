<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class MakeOldestAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::oldest()->first();
		$admin_role = Role::where("name", "admin")->first()->id;
		if(!$user->hasRole("admin")) {
			$user->roles()->attach($admin_role);
		}
    }
}
