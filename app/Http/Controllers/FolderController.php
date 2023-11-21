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
		$folders = $request->user()->folders()->get();
		$childfolders = $request->user()->folders()->whereNotNull('parent_folder_id')->get();
        $topfolders = $request->user()->folders()->whereNull('parent_folder_id')->get();

		$foldermap = array_fill_keys(
			$topfolders->map(function($folder) { return $folder->id; })->all(),
			null
		);

		return view("folders.manage", ["folders" => $folders]);
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
    public function edit(Folder $folder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Folder $folder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Folder $folder)
    {
        //
    }
}
