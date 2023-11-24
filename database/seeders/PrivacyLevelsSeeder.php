<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivacyLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$levels = DB::table("privacy_levels")->pluck("id");
		if($levels->doesntContain(1)) {
			DB::table("privacy_levels")->insert([
				"id" => 1,
				"name" => "Public",
				"description" => "Visible to all unblocked users and guests"
			]);
		}

		if($levels->doesntContain(2)) {
			DB::table("privacy_levels")->insert([
				"id" => 2,
				"name" => "Logged-in",
				"description" => "Visible only to logged-in users"
			]);
		}
		
		if($levels->doesntContain(3)) {
			DB::table("privacy_levels")->insert([
				"id" => 3,
				"name" => "Authorised",
				"description" => "Visible only to authorised users"
			]);
		}
		
		if($levels->doesntContain(4)) {
			DB::table("privacy_levels")->insert([
				"id" => 4,
				"name" => "Specific lists",
				"description" => "Visible only to specific authorisation lists"
			]);
		}
		
		if($levels->doesntContain(5)) {
			DB::table("privacy_levels")->insert([
				"id" => 5,
				"name" => "Private",
				"description" => "Visible only to you and through access keys."
			]);
		}
    }
}
