<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index() {
		$random_art = Artwork::inRandomOrder()->whereNotNull("thumbnail")->limit(3)->get();
		$date_limit = now()->subWeek();
		$popular_art = Artwork::whereHas("faved_by", function($q) use($date_limit){
			$q->where("faves.created_at", ">", $date_limit);
		})->whereNotNull("thumbnail")->get()->filter(function($i) { return $i->faved_by->count() > 0; })->values();
		$popular_art = $popular_art->sortByDesc(function($i) use($date_limit) {
			return $i->faved_by->where("pivot.created_at", ">", $date_limit)->count();
		})->slice(0, 10);
		return view("welcome", ["random_artworks" => $random_art, "popular_artworks" => $popular_art]);
	} 
}
