<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BroadcastVerificationEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table("users")->get();
		foreach($users as $user_row) {
			$user = User::where("id", $user_row->id)->first();
			if($user->email_verified_at === null) {
                $user->sendEmailVerificationNotification();
            }
        }
    }
}
