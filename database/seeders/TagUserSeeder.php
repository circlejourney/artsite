<?php

namespace Database\Seeders;

use App\Models\Artwork;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$artworks = Artwork::all();
		foreach($artworks as $artwork) {
			foreach($artwork->tags as $tag) {
				foreach($artwork->users as $user){
					$newtag = Tag::where("name", $tag->name)->where("user_id", $user->id)->first();
					if(!$newtag) {
						$newtag = Tag::create([
							"user_id" => $user->id,
							"name" => $tag->name
						]);
					}
					$artwork->tags()->attach($newtag->id);
				}
			}
		}

		$obsoleteTags = Tag::whereNull("user_id")->get();
		foreach($obsoleteTags as $tag) {
			$tag->artworks()->sync([]);
			$tag->delete();
		}
    }
}
