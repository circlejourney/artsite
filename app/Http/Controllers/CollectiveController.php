<?php

namespace App\Http\Controllers;

use App\Models\Collective;
use Illuminate\Http\Request;

class CollectiveController extends Controller
{
    /**
     * Display a listing of a user's groups.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new group.
     */
    public function create()
    {
        return view("collectives.create");
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request)
    {
		$request->validate([
			"url" => ["required", "string", "max:255", "regex:/^[\w-]+$/"],
			"display_name" => ["string", "max:255", "regex:/^[^<>]+$/"],
			"privacy_level_id" => ["required", "integer"],
		]);

		$collective = Collective::create([
			"url" => $request->url,
			"display_name" => $request->display_name,
			"privacy_level_id" => intval($request->privacy_level_id)
		]);
		return redirect(route("collectives.show", ["url" => $collective->url]));
    }

    /**
     * Display the selected group.
     */
    public function show(string $url)
    {
		$collective = Collective::where("url", $url)->firstOrFail();
        return view("collectives.show", ["collective" => $collective]);
    }

    /**
     * Show the form for editing the group.
     */
    public function edit(string $url)
    {
		$collective = Collective::where("url", $url)->firstOrFail();
        return view("collectives.edit", ["collective" => $collective]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $url)
    {
        $collective = Collective::where("url", $url)->firstOrFail();
		$collective->update([
			"privacy_level_id" => $request->privacy_level_id,
			"display_name" => $request->display_name,
			"url" => $request->url
		]);
		return redirect(route("collectives.show", ["url" => $collective->url]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collective $collective)
    {
        //
    }

    /**
     * Show the form for joining an existing group.
     */
    public function join(Collective $collective)
    {
        //
    }

    /**
     * Add the joining user to the group.
     */
    public function insert(Collective $collective)
    {
        //
    }
}
