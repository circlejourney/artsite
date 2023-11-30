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
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
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
		$artworks = Artwork::whereHas("users", function($query){
			$query->where("user_id", auth()->user()->follows->pluck("id")->all());
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
		return response($user->notifications->count());
	}
}
