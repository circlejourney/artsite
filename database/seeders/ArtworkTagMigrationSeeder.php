<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtworkTagMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$artwork_tags = DB::table("artwork_tag")->get();
		foreach($artwork_tags as $artwork_tag) {
			$newID = DB::table("tags")->where("name", $artwork_tag->tag_id)->first("id")->id;
			DB::table("artwork_tag")->where("tag_id", $artwork_tag->tag_id)->update(["tag_id" => $newID]);
		}
    }
}
