<?php

namespace App\Http\Controllers;

use App\Models\Collective;
use App\Models\Folder;
use App\Services\PrivacyLevelService;
use Illuminate\Http\Request;

class CollectiveFolderController extends Controller
{
    public function index_collective(Collective $collective) {
        $folderlist = $collective->folders;

        $tags = collect([]);
        foreach($folderlist as $folder) {
            $tags = $tags->concat($folder->tags());
        }

        $artworks = $collective->artworks()->with("tags")->orderBy("created_at", "desc")->get();

        return view("folders.collective-index", ["collective" => $collective, "folderlist" => $folderlist, "tags" => $tags, "artworks" => $artworks, "tag" => null]);
    }

    public function show_collective(Request $request, Collective $collective, Folder $folder, $all = false) {
		if(!$collective->folders->contains($folder)) abort(404);
		
		$childfolders = $folder->children()->with("artworks")->get();
		
		if($request->query("tag")) {
			$tag = $folder->tags()->where("name", $request->query('tag'))->first();
		}
			
		$artworks = collect([]);
		if(!isset($tag) || $tag !== null) {
			if($all != "all") {
				$folders = collect([$folder]);
			} else {
				$folders = $folder->getTree(true, 5)->pluck("folder");
			}
			foreach($folders as $thisfolder) {
				if(isset($tag)) $thisartworks = $thisfolder->artworks()->whereHas("tags", function($q) use($tag) { $q->where("id", $tag->id); })->get();
				else $thisartworks = $thisfolder->artworks;
				$artworks = $artworks->concat($thisartworks);
			}
		}

		$params = [
			"collective" => $collective,
			"folder" => $folder,
			"childfolders" => $childfolders,
			"tags" => $folder->tags()->sortByDesc("tag_highlight"),
			"artworks" => $artworks,
			"all" => $all == "all",
			"tag" => $tag ?? null
		];

		return view("folders.collective-show", $params);

    }
}
