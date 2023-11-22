<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Folder;
use Illuminate\Http\Request;
use App\Services\FolderListService;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		$folders = $request->user()->folders();
		$sorted = FolderListService::class($folders)->makeTree();
		return view("folders.manage", ["folderlist" => $sorted]);
    }

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
			"user_id" => $request->user()->id
		];
		if($request->parent_folder) {
			$query["parent_folder_id"] = $request->parent_folder;
			$query["depth"] = Folder::where("id", $request->parent_folder)->first()->depth + 1;
		};
		
        $folder = Folder::create($query);
		return redirect( route("folders") )->with('success', 'Folder created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Folder $folder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Folder $folder, Request $request)
    {
		$folders = $request->user()->folders();
		$sorted = FolderListService::class($folders)->makeTree();
		return view("folders.edit", ["folder" => $folder, "folderlist" => $sorted]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Folder $folder)
    {
		$query = [
			"title" => $request->title
		];
		if($request->parent_folder) {
			$query["parent_folder_id"] = $request->parent_folder;
			$query["depth"] = Folder::where("id", $request->parent_folder)->first()->depth + 1;
		};
		
        $folder->update($query);
		return redirect( route("folders", ["folder" => $folder->id]) )->with('success', 'Folder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Folder $folder)
    {
        //
    }
}
