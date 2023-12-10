<?php

namespace App\Http\Controllers;

use App\Models\ArtInvite;
use App\Models\Notification;
use Illuminate\Http\Request;

class ArtInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $art_invites = request()->user()->art_invites->reverse();
		return view("notifications.art-invites.index", ["art_invites" => $art_invites]);
    }

    /**
     * Display the specified resource.
     */
    public function post(Request $request)
    {
		$request->validate([ "art_invite_id" => "required|integer", "action" => "required" ]);
        $art_invite = ArtInvite::where("id", $request->art_invite_id)->firstOrFail();
		if($art_invite->user->id !== $request->user()->id) abort(403);

		if($request->action == "reject") {
			$art_invite->delete();
			Notification::dispatch($request->user(), collect([$art_invite->sender]), collect([ "type" => "art-invite", "content" => $request->user()->getNametag()." rejected your invitation to add them as an artist." ]));
			return redirect()->back()->with("status", "Rejected invite.");
		} else if($request->action == "accept") {
			$art_invite->delete();
			$art_invite->artwork->addForeignUser($art_invite->user);
			return redirect()->back()->with("success", "Accepted invite.");
		}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ArtInvite $artInvite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArtInvite $artInvite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArtInvite $artInvite)
    {
        //
    }
}
