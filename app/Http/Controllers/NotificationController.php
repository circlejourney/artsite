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
    public function index_faves(Request $request) {
		$notifications = $request->user()->notifications()->where("type", "fave")->orderBy("created_at", "desc")->withPivot("read")->get();
        return view("notifications.index", [ "notifications" => $notifications, "active" => "favorites" ]);
    }

    public function index_follows(Request $request) {
		$notifications = $request->user()->notifications()->where("type", "follow")->orderBy("created_at", "desc")->get();
        return view("notifications.index", [ "notifications" => $notifications, "active" => "follows" ]);
    }

    public function index_invites(Request $request)
    {
		$notifications = $request->user()->notifications()->where("type", "like", "art-inv%")->orderBy("created_at", "desc")->get();
        return view("notifications.index", [ "notifications" => $notifications, "active" => "art-invites", "mass_delete" => false ]);
    }
	
	public function index_collectives(Request $request) {
		$notifications = $request->user()->collective_notifications()->orderBy("created_at", "desc")->get();
		return view(
			"notifications.index",
			[
				"notifications" => $notifications,
				"active" => "collectives"
			]
		);
	}

	public function post_invite(Request $request) {
		$request->validate([ "notification_id" => "required|integer", "action" => "required" ]);
        $notification = Notification::where("id", $request->notification_id)->firstOrFail();
		if(!$notification->recipients()->where("recipient_id", $request->user()->id)->exists()) abort(403);

		if($request->action == "reject") {
			Notification::dispatch($request->user(), collect([$notification->sender]), collect([
				"artwork_id" => $notification->artwork_id,
				"type" => "art-inv-reject"
			]));
			$notification->delete();
			return redirect()->back()->with("status", "Rejected invite.");
		} else if($request->action == "accept") {
			$notification->artwork->addForeignUser($request->user());
			Notification::dispatch($request->user(), collect([$notification->sender]), collect([
				"artwork_id" => $notification->artwork_id,
				"type" => "art-inv-accept"
			]));
			$notification->delete();
			return redirect()->back()->with("success", "Accepted invite.");
		}
	}

	public function index_feed() {
		$artworks = Artwork::whereHas("users", function($query){
			$query->whereIn("user_id", auth()->user()->follows->pluck("id")->all());
		})->orderBy("created_at", "desc")->get()
		->filter(function($artwork) {
			return $artwork->oldest_user()->id !== auth()->user()->id;
		});

		return view("notifications.follow-feed", ["artworks" => $artworks]);
	}

	public function mark_read_many(Request $request) {
		foreach($request->notification_checked as $notification_id) {
			$notification = Notification::where("id", $notification_id)->first();
			if(!$notification) continue;

			$relatedPivotEntry = $notification->recipients()->withPivot("id")->where("notification_recipient.recipient_id", $request->user()->id)->first();
			if($relatedPivotEntry === null) continue;
			
			$notification->recipients()->updateExistingPivot(
				$relatedPivotEntry->id,
				["read" => true]
			);
		}
		return redirect()->back()->with("status", "Messages marked as read.");
	}

	public function delete_many(Request $request) {
		$user = $request->user();
		foreach($request->notification_checked as $notification_id) {

			$notification = Notification::where("id", $notification_id)->first();

			if($notification->type == "co-invite") {
				Notification::dispatch(
					$request->user(),
					collect([$notification->sender]),
					collect([
						"recipient_collective_id" => $notification->sender_collective->id,
						"type" => "co-inv-reject"
					])
				);
			} else if($notification->type == "co-join") {
				Notification::dispatch_from_collective(
					$request->user(),
					$notification->recipient_collective,
					collect([$notification->sender]),
					collect([
						"type" => "co-reject"
					])
				);
			} else if($notification->type == "art-invite") {
				Notification::dispatch(
					$user,
					collect([ $notification->sender ]),
					collect([
						"type" => "art-inv-reject",
						"artwork_id" => $notification->artwork_id
					])
				);
			}

			$notification->deleteFor($user);
		}
		return redirect()->back()->with("status", "Messages deleted.");
	}

	/* AJAX */

	public function delete_one(Request $request, Notification $notification) {
		$user = $request->user();
		$notificationID = $notification->id;
		$notification->deleteFor($user);
		return response(["notification" => $notificationID]);
	}

	public function put_read(Request $request) {
		foreach($request->read as $notification_id) {
			$notification = Notification::where("id", $notification_id)->first();
			if(!$notification) continue;

			$relatedPivotEntry = $notification->recipients()->withPivot("id")->where("notification_recipient.recipient_id", $request->user()->id)->first();
			if($relatedPivotEntry === null) continue;
			
			$notification->recipients()->updateExistingPivot(
				$relatedPivotEntry->id,
				["read" => true]
			);
		}
		return response(["notifications" => $request->read]);
	}

	public function get_count(Request $request) {
		$user = $request->user();
		return response($user->notifications()->where("notification_recipient.read", 0)->count());
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

			$collective->members()->syncWithoutDetaching($joiner->id);
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
}
