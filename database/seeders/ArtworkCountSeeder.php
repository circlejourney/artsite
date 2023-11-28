<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ArtworkCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table("users")->get();
		foreach($users as $user_row) {
			$user = User::where("id", $user_row->id)->first();
			$user->artwork_count = $user->artworks->count();
			$user->save();
		}
    }
}
