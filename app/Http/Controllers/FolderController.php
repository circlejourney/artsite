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
		return view("folders.index", ["user" => $user]);
	}

    /**
     * Display the specified resource.
     */
	public function show(string $username, Folder $folder) {
		$user = User::where("name", $username)->firstOrFail();
		if($user->folders()->get()->contains($folder)) return view("folders.show", ["folder" => $folder]);
		abort(404);
	}

    /**
     * Display a listing of the authenticated user's folders
     */
    public function index_manage(Request $request)
    {
		$topfolder = Folder::with("allChildren")->where("id", $request->user()->top_folder_id)->first();
		$sorted = FolderListService::class($topfolder)->tree();
		return view("folders.manage", ["folderlist" => $sorted]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Folder $folder, Request $request)
    {
		$topfolder = Folder::with("allChildren")->where("id", $request->user()->top_folder_id)->first();
		$sorted = FolderListService::class($topfolder)->tree();
		
		$thisfolder = Folder::with("allChildren")->where("id", $folder->id)->first();
		$childkeys = FolderListService::class($thisfolder)->tree()
			->map(function($i){ return $i["id"]; })->all();
			
		return view("folders.edit", ["folder" => $folder, "folderlist" => $sorted, "childkeys" => $childkeys]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Folder $folder)
    {
		$thisfolder = Folder::with("allChildren")->where("id", $folder->id)->first();
		$childkeys = FolderListService::class($thisfolder)->tree()
			->map(function($i){ return $i["id"]; })->all();
		if(in_array($request->parent_folder, $childkeys) || $request->parent_folder == $folder->id) {
			return Redirect::back()->withErrors("Folder cannot be placed within itself.");
		}

		$query = [
			"title" => $request->title,
			"parent_folder_id" => $request->parent_folder
		];
		if($request->parent_folder) {
			$query["depth"] = Folder::where("id", $request->parent_folder)->first()->depth + 1;
		};
		
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
		error_log($folder->id);
        Folder::destroy($folder->id);
		return redirect( route("folders") );
    }
}
