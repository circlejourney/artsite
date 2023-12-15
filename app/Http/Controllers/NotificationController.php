<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Collective;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		$notifications = auth()->user()->notifications()->orderBy("created_at", "desc")->whereNull("sender_collective_id")->get();
        return view("notifications.index", [ "notifications" => $notifications ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
		$user = $request->user();
        foreach($request->notifications as $notificationID) {
			if(!$notification = Notification::where("id", $notificationID)->first()) continue;
			$notification->deleteFor($user);
		}
		return view("notifications.index", ["user" => $user]);
    }

	public function index_follow() {
		$artworks = Artwork::whereDoesntHave("users", function($q) { $q->where("user_id", auth()->user()->id); })->whereHas("users", function($query){
			$query->whereIn("user_id", auth()->user()->follows->pluck("id")->all());
		})->orderBy("created_at", "desc")->get();
		return view("notifications.follow-feed", ["artworks" => $artworks]);
	}

	public function delete_one(Request $request, Notification $notification) {
		$user = $request->user();
		$notificationID = $notification->id;

		$notification->deleteFor($user);

		return response(["notification" => $notificationID]);
	}

	public function get_count(Request $request) {
		$user = $request->user();
		return response($user->notifications->count() + $user->invites->count() + $user->collective_notifications()->count());
	}
	
	public function index_collectives(Request $request) {
		return view("notifications.collectives.index",
			["collective_notifications" => $request->user()->collective_notifications()->merge(
				$request->user()->notifications()->whereNotNull("sender_collective_id")->get()
			)]
		);
	}

	public function post_collectives(Request $request) {
		$request->validate([ "notification_id" => "required|integer", "action" => "required" ]);
		$notification = Notification::where("id", $request->notification_id)->firstOrFail();
		$collective = Collective::where("id", $notification->recipient_collective_id)->firstOrFail();
		if($request->user()->collectives->pluck("id")->doesntContain($notification->recipient_collective->id)) abort(403);

		if($request->action == "reject") {
			Notification::dispatch_from_collective(
				$request->user(),
				$collective,
				collect([$notification->sender]),
				collect([
					"type" => "co-reject",
					"content" => $collective->display_name." rejected your request to join."
				])
			);
			$notification->delete();
			return redirect()->back()->with("status", "Rejected invite.");
		} else if($request->action == "accept") {
			Notification::dispatch_from_collective(
				$request->user(),
				$collective,
				collect([$notification->sender]),
				collect([
					"type" => "co-accept",
					"content" => $collective->display_name." accepted your request to join."
				])
			);
			$collective->members()->attach($notification->sender_id);
			$notification->delete();
			return redirect()->back()->with("success", "Accepted invite.");
		}
	}
}
