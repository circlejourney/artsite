<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("notifications.index", [ "user" => auth()->user() ]);
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
		return response($user->notifications->count() + $user->invites->count());
	}
}
