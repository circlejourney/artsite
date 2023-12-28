<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Collective;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_faves(Request $request)
    {
		$notifications = $request->user()->notifications()->where("type", "fave")->orderBy("created_at", "desc")->get();
        return view("notifications.index", [ "notifications" => $notifications, "active" => "faves" ]);
    }

    public function index_follows(Request $request)
    {
		$notifications = $request->user()->notifications()->where("type", "follow")->orderBy("created_at", "desc")->get();
        return view("notifications.index", [ "notifications" => $notifications, "active" => "follows" ]);
    }

    public function index_invites(Request $request)
    {
		$notifications = $request->user()->notifications()->where("type", "art-invite")->orderBy("created_at", "desc")->get();
        return view("notifications.index", [ "notifications" => $notifications, "active" => "invites", "mass_delete" => false ]);
    }

	public function post_invite(Request $request) {
		$request->validate([ "notification_id" => "required|integer", "action" => "required" ]);
        $notification = Notification::where("id", $request->notification_id)->firstOrFail();
		if(!$notification->recipients()->where("recipient_id", $request->user()->id)->exists()) abort(403);

		if($request->action == "reject") {
			Notification::dispatch($request->user(), collect([$notification->sender]), collect([ "type" => "art-invite", "content" => $request->user()->getNametag()." rejected your invitation to add them as an artist." ]));
			$notification->delete();
			return redirect()->back()->with("status", "Rejected invite.");
		} else if($request->action == "accept") {
			foreach($notification->recipients as $recipient) {
				$notification->artwork->addForeignUser($recipient);
			}
			$notification->delete();
			return redirect()->back()->with("success", "Accepted invite.");
		}
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

	public function index_feed() {
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

	public function put_read(Request $request) {
		foreach($request->read as $notification_id) {
			$notification = Notification::where("id", $notification_id);
			$notification->update(["read" => true]);
		}
		return response(["notifications" => $request->read]);
	}

	public function get_count(Request $request) {
		$user = $request->user();
		return response($user->notifications->count() + $user->art_invites->count() + $user->collective_notifications()->count());
	}
	
	public function index_collectives(Request $request) {
		return view("notifications.collectives.index",
			["collective_notifications" => $request->user()->collective_notifications()->merge(
				$request->user()->notifications()->whereNotNull("sender_collective_id")->get()
			)->sortByDesc("created_at")]
		);
	}

	public function post_collectives(Request $request) {
		$request->validate([ "notification_id" => "required|integer", "action" => "required" ]);
		$notification = Notification::where("id", $request->notification_id)->firstOrFail();

		$isInvite = $notification->type == "co-invite";
		
		if(!$isInvite) {
			$collective = Collective::where("id", $notification->recipient_collective_id)->firstOrFail();
			if($request->user()->collectives->pluck("id")->doesntContain($notification->recipient_collective->id)) abort(403);
			$joiner = $notification->sender;

		} else {
			$collective = Collective::where("id", $notification->sender_collective_id)->firstOrFail();
			$joiner = $request->user();
			
		}

		if($request->action == "reject") {
			if($isInvite) {
				Notification::dispatch(
					$request->user(),
					collect([$notification->sender]),
					collect([
						"recipient_collective_id" => $collective->id,
						"type" => "co-inv-reject"
					])
				);
			} else {
				Notification::dispatch_from_collective(
					$request->user(),
					$collective,
					collect([$notification->sender]),
					collect([
						"type" => "co-reject"
					])
				);
			}
			$notification->delete();
			return redirect()->back()->with("status", "Rejected invite.");
		} else if($request->action == "accept") {

			if($collective->members()->where("user_id", $joiner->id)->exists()) {
				return redirect()->back()->withErrors("User is already a member.");
			}

			if($isInvite) {
				Notification::dispatch_to_collective(
					$request->user(),
					$collective,
					collect([
						"type" => "co-inv-accept"
					])
				);
			} else {
				Notification::dispatch_from_collective(
					$request->user(),
					$collective,
					collect([$notification->sender]),
					collect([
						"type" => "co-accept"
					])
				);
			}

			$obsolete = Notification::whereHas("recipients", function($q) use($joiner) {
					$q->where("recipient_id", $joiner->id);
				})->where("sender_collective_id", $collective->id)->get()
				->merge(
					Notification::where([
						"sender_id" => $joiner->id,
						"recipient_collective_id" => $collective->id
					])->get()
				);
			
			error_log($obsolete);

			$collective->members()->syncWithoutDetaching($joiner->id);
			$notification->delete();
			return redirect()->back()->with("success", "Accepted invite.");
		}
	}
}
