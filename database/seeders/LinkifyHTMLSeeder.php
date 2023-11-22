<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class LinkifyHTMLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table("users")->get();
		foreach($users as $user_row) {
			$user = User::where("id", $user_row->id)->first();
			$profile_html = $user->profile_html;
			// TODO: Make it upload profile HTML and change the text to a link
			if($profile_html == null) continue;
			$filename = "html.txt";
			$file = UploadService::create($filename, $profile_html, "profiles/".$user->id);
			$relative_path = $file->getRelativePath();
			$user->update([ "profile_html" => $relative_path ]);
		}
    }
}
