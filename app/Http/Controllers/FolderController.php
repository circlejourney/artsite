<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\FolderListService;
use Illuminate\Support\Facades\Redirect;
use App\Services\PrivacyLevelService;

class FolderController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
		$query = [
			"title" => $request->title,
			"user_id" => $request->user()->id,
			"privacy_level_id" => $request->privacy_level_id,
			"parent_folder_id" => $request->parent_folder ?? $request->user()->top_folder_id
		];
		
        Folder::create($query);
		return redirect( route("folders.manage") )->with('success', 'Folder created successfully.');
    }

    /**
     * Display a listing of the selected user's folders.
     */
	public function index_user(Request $request, string $username) {
		$user = User::where("name", $username)->firstOrFail();
		$artworks = PrivacyLevelService::filterArtworkCollection($request->user(), $user->artworks()->with("tags")->orderBy("created_at", "desc")->get() );
		
		if($request->query("tag")) { 
			$tag = Tag::where("user_id", $user->id)->where("name", $request->query('tag', null))->first();
			$artworks = $artworks->filter(function($artwork) use($tag){
				return $artwork->tags->pluck("id")->contains($tag->id);
			});
		}

		$maxPrivacyAllowed = PrivacyLevelService::getMaxPrivacyAllowed(auth()->user(), collect([$user->id]));
		
		$childfolders = $user->getTopFolder()->children()->with("artworks")
			->get()->reject(function($folder) use($maxPrivacyAllowed) { return $folder->privacy_level_id > $maxPrivacyAllowed; });
		$folderlist = collect([$user->getTopFolder()])->merge($childfolders);
		
		return view("folders.index", [
			"user" => $user,
			"artworks" => $artworks,
			"folderlist" => $folderlist,
			"tags" => $user->tags,
			"tag" => $tag ?? null
		]);
	}

    /**
     * Display the specified folter.
     */

	public function show(Request $request, string $username, Folder $folder, $all = false) {
		$user = User::where("name", $username)->firstOrFail();
		if(!$user->folders()->get()->contains($folder)) abort(404);
		
		$maxPrivacyAllowed = PrivacyLevelService::getMaxPrivacyAllowed(auth()->user(), collect([$user->id]));
		if($folder->getLineagePrivacyLevel() > $maxPrivacyAllowed) abort(401);

		$childfolders = $folder->children()->with("artworks")->get();
		
		if($request->query("tag")) {
			$tag = $user->tags()->where("name", $request->query('tag'))->first();
		}
			
		$artworks = collect([]);
		if(!isset($tag) || $tag !== null) {
			if($all != "all") {
				$folders = collect([$folder]);
			} else {
				$folders = $folder->getTree(true, $maxPrivacyAllowed)->pluck("folder");
			}
			foreach($folders as $thisfolder) {
				if(isset($tag)) $thisartworks = $thisfolder->artworks()->whereHas("tags", function($q) use($tag) { $q->where("id", $tag->id); })->get();
				else $thisartworks = $thisfolder->artworks;
				$artworks = $artworks->concat($thisartworks);
			}
		}

		$params = [
			"user" => $user,
			"folder" => $folder,
			"childfolders" => $childfolders,
			"tags" => $user->tags()->with("tag_highlight")->get()->sortByDesc("tag_highlight"),
			"artworks" => $artworks,
			"all" => $all == "all",
			"tag" => $tag ?? null
		];

		return view("folders.show", $params);
		
	}

	public function show_all(Request $request, string $username, Folder $folder) {
		$user = User::where("name", $username)->firstOrFail();
		if(!$user->folders()->get()->contains($folder)) abort(404);
		$maxPrivacyAllowed = PrivacyLevelService::getMaxPrivacyAllowed(auth()->user(), collect([$user->id]));
		if($folder->getLineagePrivacyLevel() > $maxPrivacyAllowed) abort(401);

		if($request->query("tag")) {
			$tag = Tag::where("user_id", $user->id)->where("name", $request->query('tag'))->first();
		}
		$artworks = collect([]);
		if(!isset($tag) || $tag !== null) {
			$folders = $folder->getTree(true, $maxPrivacyAllowed)->pluck("folder");
			foreach($folders as $folder) {
				if(isset($tag)) $thisartworks = $folder->artworks()->whereHas("tags", function($q) use($tag) { $q->where("id", $tag->id); })->get();
				else $thisartworks = $folder->artworks;
				$artworks = $artworks->concat($thisartworks);
			}
		}

		$params = [
			"user" => $user,
			"folder" => $folder,
			"tags" => $user->tags,
			"artworks" => $artworks
		];
		if(isset($tag)) $params["tag"] = $tag;

		return view("folders.show", $params);
	}

    /**
     * Display a listing of the authenticated user's folders for management
     */
    public function index_manage(Request $request)
    {
		$sorted = $request->user()->getFolderTree(false);
		return view("folders.manage", ["folderlist" => $sorted]);
    }

    /**
     * Show the form for editing the folder.
     */
    public function edit(Folder $folder, Request $request)
    {
		$sorted = $request->user()->getFolderTree(false);
		
		$thisfolder = Folder::with("allChildren")->where("id", $folder->id)->first();
		$childkeys = FolderListService::class($thisfolder)->tree(false)
			->map(function($i){ return $i["id"]; })
			->push($folder->id)
			->all();

		$selectedfolder = $folder->parent()->first()->id;
			
		return view("folders.edit", ["folder" => $folder, "folderlist" => $sorted, "childkeys" => $childkeys, "selected" => $selectedfolder]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Folder $folder)
    {
		$thisfolder = Folder::with("allChildren")->where("id", $folder->id)->first();
		$childkeys = FolderListService::class($thisfolder)->tree(false)
			->map(function($i){ return $i["folder"]->id; });
			
		if($childkeys->contains($request->parent_folder) || $request->parent_folder == $folder->id) {
			return Redirect::back()->withErrors("Folder cannot be placed within itself.");
		}

		$query = [
			"title" => $request->title,
			"parent_folder_id" => $request->parent_folder ?? $folder->user()->first()->top_folder_id,
			"privacy_level_id" => $request->privacy_level_id
		];
		
        $folder->update($query);
		return redirect( route("folders.edit", ["folder" => $folder->id]) )->with('success', 'Folder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Folder $folder)
    {
		$owner = $folder->user;
		$artworks = $folder->artworks;
		foreach($artworks as $artwork) {
			$artwork->folders()->attach($owner->top_folder_id);
		}
        Folder::destroy($folder->id);
		return redirect( route("folders.manage") );
    }
}
