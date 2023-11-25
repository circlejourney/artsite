<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Services\TaggerService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_user(string $username)
    {
		$user = User::where("name", $username)->firstOrFail();
        $tags = $user->getTags();
		
		return view("tags.index", ["user" => $user, "tags" => $tags]);
    }
	
	/**
	 * Display the specified resource.
	 */
	public function show_user(string $username, Tag $tag)
	{
		$user = User::where("name", $username)->firstOrFail();
		$taggedArtworks = $user->artworks()->whereHas("tags", function($q) use($tag) {
			return $q->where("id", $tag->id);
		})->get();
		
		return view("tags.show", ["user" => $user, "tag" => $tag, "artworks" => $taggedArtworks]);
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
