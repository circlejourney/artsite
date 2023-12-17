<?php

namespace App\Http\Controllers;

use App\Models\Collective;
use App\Models\Notification;
use Illuminate\Http\Request;
use PHPUnit\TestRunner\TestResult\Collector;

class CollectiveController extends Controller
{
    /**
     * Display a listing of a user's groups.
     */
    public function index()
    {
        $collectives = Collective::all();
        return view("collectives.index", ["collectives" => $collectives]);
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
		return redirect(route("collectives.show", ["collective" => $collective]));
    }

    /**
     * Display the selected group.
     */
    public function show(Collective $collective)
    {
        return view("collectives.show", ["collective" => $collective]);
    }

    /**
     * Show the form for editing the group.
     */
    public function edit(Collective $collective)
    {
        return view("collectives.edit", ["collective" => $collective]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collective $collective)
    {
		$request->validate([
			"url" => ["required", "string", "max:255", "regex:/^[\w-]+$/"],
			"display_name" => ["string", "max:255", "regex:/^[^<>]+$/"],
			"privacy_level_id" => ["required", "integer"],
		]);
		$collective->update([
			"privacy_level_id" => $request->privacy_level_id,
			"display_name" => $request->display_name,
			"url" => $request->url
		]);
		return redirect(route("collectives.show", ["collective" => $collective]));
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
    public function request_join(Collective $collective, Request $request)
    {
        Notification::dispatch_to_collective($request->user(), $collective, collect(["type" => "co-join", "content" => $request->join_message]));
        return redirect( route("collectives.show", ["collective" => $collective]) )->with("success", "Request sent successfully.");
    }

    /**
     * Add the joining user to the group.
     */
    public function add_member(Collective $collective)
    {
        //
    }
}
