<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manage(Request $request)
    {
		$user = $request->user();
        return view("invites.manage", ["user" => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function generate(Request $request)
    {
		if($request->user()->invite_credits == 0) return redirect()->back()->withErrors("You do not any invite generations available.");
		
		$request->user()->update([
			"invite_credits" => $request->user()->invite_credits-1
		]);

		Invite::create([
			"creator_id" => $request->user()->id
		]);

		return redirect()->back()->with("success", "Invite code generated successfully.");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invite $invite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invite $invite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invite $invite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invite $invite)
    {
        //
    }
}
