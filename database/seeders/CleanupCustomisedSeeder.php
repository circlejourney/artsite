<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleanupCustomisedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$users = DB::table("users")->get();
		foreach($users as $user_row) {
			$user = User::first("id", $user_row->id);
			$user->customised = false;
			$user->save();
		}
    }
}
