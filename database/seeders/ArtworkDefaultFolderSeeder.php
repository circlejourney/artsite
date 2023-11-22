<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Artwork;

class ArtworkDefaultFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artworks = DB::table("artworks")->get();
		foreach($artworks as $artwork_row) {
			$artwork = Artwork::where("id", $artwork_row->id)->first();
			$users = $artwork->users;
			$hasfolders = $artwork->folders()->count();
			if($hasfolders === 0) {
				$top_folders = $users->map(function($i) {
					return $i->top_folder_id;
				})->all();
				$artwork->folders()->attach($top_folders);
			}
		}
    }
}
