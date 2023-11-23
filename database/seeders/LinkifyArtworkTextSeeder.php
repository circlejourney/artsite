<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Artwork;

class LinkifyArtworkTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artworks = DB::table("artworks")->get();
		foreach($artworks as $artwork_row) {
			$artwork = Artwork::where("id", $artwork_row->id)->first();
			$text = $artwork->text;
			// TODO: Make it upload profile HTML and change the text to a link
			if($text == null) continue;
			$filename = Str::random(20).".txt";
			$file = UploadService::create($filename, $text, "artwork-text/".$artwork->id);
			$relative_path = $file->getRelativePath();
			$artwork->update([ "text" => $relative_path ]);
		}
    }
}
