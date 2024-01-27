<?php

namespace App\Http\Controllers;

use App\Models\Collective;
use App\Models\Folder;
use App\Services\FolderListService;
use App\Services\PrivacyLevelService;
use Illuminate\Http\Request;

class CollectiveFolderController extends Controller
{
    public function index_collective(Collective $collective) {
        $folderlist = $collective->folders;

        /*$tags = collect([]);
        foreach($folderlist as $folder) {
            $tags = $tags->concat($folder->tags());
        }*/

        $artworks = $collective->artworks();

        return view("folders.collective-index", ["collective" => $collective, "folderlist" => $folderlist, "artworks" => $artworks, "tag" => null]);
    }

    public function show_collective(Request $request, Collective $collective, Folder $folder, $all = false) {
		if(!$collective->folders->contains($folder)) abort(404);
		
		$childfolders = $folder->children()->with("artworks")->get();
		
		/*if($request->query("tag")) {
			$tag = $folder->tags()->where("name", $request->query('tag'))->first();
		}*/
			
		$artworks = collect([]);
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

		$params = [
			"collective" => $collective,
			"folder" => $folder,
			"childfolders" => $childfolders,
			//"tags" => $folder->tags()->sortByDesc("tag_highlight"),
			"artworks" => $artworks,
			"all" => $all == "all",
			"tag" => $tag ?? null
		];

		return view("folders.collective-show", $params);

    }

    public function index_manage(Collective $collective) {
        if($collective->members->doesntContain(auth()->user())) abort(403);
        $topfolder = $collective->top_folder;
        $folderlist = FolderListService::class($topfolder)->tree(false);
        return view("folders.collective-manage", ["collective" => $collective, "folderlist" => $folderlist]);
    }

    public function store(Request $request, Collective $collective) {
		$query = [
			"title" => $request->title,
			"collective_id" => $collective->id,
			"privacy_level_id" => $request->privacy_level_id,
			"parent_folder_id" => $request->parent_folder ?? $collective->top_folder_id
		];
		
        Folder::create($query);
		return redirect( route("collectives.folders.manage", ["collective" => $collective]) )->with('success', 'Folder created successfully.');
    }

    public function edit(Collective $collective, Folder $folder) {
		$sorted = $collective->getFolderTree(false);
		
		$thisfolder = Folder::with("allChildren")->where("id", $folder->id)->first();
		$childkeys = FolderListService::class($thisfolder)->tree(false)
			->map(function($i){ return $i["folder"]->id; })
			->push($folder->id)
			->all();

		$selectedfolder = $folder->parent()->first()->id;
			
		return view("folders.collective-edit", ["collective" => $collective, "folder" => $folder, "folderlist" => $sorted, "childkeys" => $childkeys, "selected" => $selectedfolder]);
    }
}
