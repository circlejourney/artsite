<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FolderListService;
use Illuminate\Support\Facades\Redirect;

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
			"parent_folder_id" => $request->parent_folder ?? $request->user()->top_folder_id
		];
		
        Folder::create($query);
		return redirect( route("folders.manage") )->with('success', 'Folder created successfully.');
    }

    /**
     * Display a listing of the selected user's folders.
     */
	public function index_user(string $username) {
		$user = User::where("name", $username)->firstOrFail();
		$artworks = $user->artworks()->get();
		$sorted = $user->getFolderTree(true);
		return view("folders.index", ["user" => $user, "artworks" => $artworks, "folderlist" => $sorted]);
	}

    /**
     * Display the specified resource.
     */
	public function show(string $username, Folder $folder) {
		$user = User::where("name", $username)->firstOrFail();
		if(!$user->folders()->get()->contains($folder)) abort(404);

		$sorted = $user->getFolderTree(true);
		return view("folders.show", ["user" => $user, "folder" => $folder, "folderlist" => $sorted]);
	}

    /**
     * Display a listing of the authenticated user's folders
     */
    public function index_manage(Request $request)
    {
		$sorted = $request->user()->getFolderTree(false);
		return view("folders.manage", ["folderlist" => $sorted]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Folder $folder, Request $request)
    {
		/*$topfolder = Folder::with("allChildren")->where("id", $request->user()->top_folder_id)->first();
		$sorted = FolderListService::class($topfolder)->tree(false);*/
		$sorted = $request->user()->getFolderTree(false);
		
		$thisfolder = Folder::with("allChildren")->where("id", $folder->id)->first();
		$childkeys = FolderListService::class($thisfolder)->tree(false)
			->map(function($i){ return $i["id"]; })->all();

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
			->map(function($i){ return $i["id"]; });
			
		if($childkeys->contains($request->parent_folder) || $request->parent_folder == $folder->id) {
			return Redirect::back()->withErrors("Folder cannot be placed within itself.");
		}

		$query = [
			"title" => $request->title,
			"parent_folder_id" => $request->parent_folder ?? $folder->user()->first()->top_folder_id,
			"privacy_level_id" => $request->privacy_level
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
			$artwork->update(["folder_id" => $owner->top_folder_id]);
		}
        Folder::destroy($folder->id);
		return redirect( route("folders") );
    }
}
