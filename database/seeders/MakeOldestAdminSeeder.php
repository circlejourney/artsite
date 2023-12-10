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
		if(!$user->hasRole("founder")) {
			$user->roles()->attach(1);
		}
		if(!$user->hasRole("admin")) {
			$user->roles()->attach(2);
		}
    }
}
