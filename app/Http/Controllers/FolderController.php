<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		$folders = $request->user()->folders();
		$sorted = $this->makeTree($folders);
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
		$sorted = $this->makeTree($folders);
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
		return redirect( route("folders.edit", ["folder" => $folder->id]) )->with('success', 'Folder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Folder $folder)
    {
        //
    }

	public static function makeTree($folders) {
		$unsorted = $folders->orderBy("depth", "asc")->get();
		$sorted = collect();
		foreach($unsorted as $folder) {
			$parentid = $folder->parent_folder_id;
			$foundparent = $sorted->search(function($item)use($parentid) {
				return $item->id === $parentid;
			});
			if($foundparent === false) {
				$sorted->push($folder);
				continue;
			}
			$insertpoint = $foundparent + 1;
			$sorted->splice($insertpoint, 0, [$folder]);
		}
		return $sorted->all();
	}
}