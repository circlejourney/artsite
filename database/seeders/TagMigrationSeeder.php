<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagrows = DB::table("tags")->get();
		foreach($tagrows as $tagrow) {
			$tag = Tag::where("id", $tagrow->id);
			$tag->update([
				"name" => $tagrow->id
			]);
		}
    }
}
