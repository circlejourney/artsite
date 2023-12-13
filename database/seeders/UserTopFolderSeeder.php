<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Folder;

class UserTopFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table("users")->get();
		foreach($users as $user_row) {
			$user = User::where("id", $user_row->id)->first();
			if($user->top_folder_id === null) {
				$top_folder = Folder::create([
					"title" => "Unsorted",
					"user_id" => $user->id
				]);	
				$user->update([
					"top_folder_id" => $top_folder->id
				]);
			}
			$orphanFolders = $user->folders->whereNull("parent_folder_id")->where("parent_folder_id", "not", $user->top_folder_id);
			foreach($orphanFolders as $orphan) {
				$orphan->update([
					"parent_folder_id" => $user->top_folder_id
				]);
			}
		}
    }
}
