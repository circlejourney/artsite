<?php

namespace App\Http\Controllers;

use App\Models\Collective;
use App\Models\Folder;
use App\Models\Notification;
use App\Models\User;
use App\Services\FolderListService;
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
        $artworks = $collective->artwork_folders->pluck("artworks")->flatten();
        error_log($artworks);
        return view("collectives.show", ["collective" => $collective, "artworks" => $artworks->sortByDesc("pivot.created_at")]);
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

	public function show_destroy(Collective $collective) {
		return view("collectives.delete", ["collective" => $collective]);
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collective $collective)
    {
		if(($member = $collective->members()->where("user_id", auth()->user()->id)->withPivot("role_id")->first()) && $member->pivot->role_id <= 2) {
        	$collective->delete();
		}
		return redirect(route("collectives.index"));
    }

    /**
     * Show the form for joining an existing group.
     */
    public function request_join(Collective $collective, Request $request)
    {

		if($collective->members()->where("user_id", $request->user()->id)->exists()) {
			return redirect()->back()->withErrors("User is already a member.");
		}

        Notification::dispatch_to_collective($request->user(), $collective, collect(["type" => "co-join", "content" => $request->join_message]));
        return redirect( route("collectives.show", ["collective" => $collective]) )->with("success", "Request sent successfully.");
    }

    /**
     * Show the form for joining an existing group.
     */
    public function invite(User $user, Request $request)
    {
        $collective = Collective::where("id", $request->collective)->firstOrFail();

		if($collective->members()->where("user_id", $user->id)->exists()) {
			return redirect()->back()->withErrors("User is already a member.");
		}

        Notification::dispatch_from_collective($request->user(), $collective, collect([$user]), collect([
            "type" => "co-invite",
            "content" => $request->invite_message
        ]));
        return redirect( route("user", ["username" => $user->name]) )->with("status", "Invite sent successfully.");
    }

    public function dashboard(Collective $collective) {
        return view("collectives.dashboard.index", ["collective" => $collective]);
    }

    public function manage_artworks(Collective $collective) {
        $folders = FolderListService::class($collective->top_folder)->tree(true);
        return view("collectives.dashboard.art", ["collective" => $collective, "folders" => $folders]);
    }

    public function update_artworks(Request $request, Collective $collective) {
        $user = $request->user();
        if(!$collective->members()->exists($request->user())) abort(403);
        $folder = Folder::where("id", $request->parent_folder)->firstOrFail();
        if(!$collective->folders()->exists($folder)) abort(403);

        if(isset($request->select) && sizeof($request->select) > 0) {
            $allowed = collect($request->select)->filter(function($i) use($user) {
                return $user->artworks()->exists($i);
            });

            foreach($allowed as $id => $value) {
                if(!$value) continue;
				if(!$artwork = $request->user()->artworks->where("id", $id)->first()) abort(403);
                $in_folders = $artwork->folders->filter(function($i) use($collective) {
                    return $collective->folders()->where("id", $i->id)->exists();
                })->pluck("id")->all();
                $artwork->folders()->detach($in_folders);
                $artwork->folders()->attach($folder->id);
			}
            return redirect(route("collectives.art.manage", ["collective" => $collective]))->with("success", "Artwork added successfully.");
		}
        
        return redirect(route("collectives.art.manage", ["collective" => $collective]));
    }

    public function delete_artworks(Request $request, Collective $collective) {
        $user = $request->user();
        if(!$collective->members()->exists($request->user())) abort(403);

        if(isset($request->select) && sizeof($request->select) > 0) {
            $allowed = collect($request->select)->filter(function($i) use($user) {
                return $user->artworks()->exists($i);
            });

            foreach($allowed as $id => $value) {
                if(!$value) continue;
				if(!$artwork = $request->user()->artworks->where("id", $id)->first()) abort(403);
                $in_folders = $artwork->folders->filter(function($i) use($collective) {
                    return $collective->folders()->where("id", $i->id)->exists();
                })->pluck("id")->all();
                $artwork->folders()->detach($in_folders);
			}

            return redirect(route("collectives.art.manage", ["collective" => $collective]))->with("status", "Artwork removed successfully.");
		}
    }

    /**
     * Show the form to leave the collective
     */
    public function show_leave(Collective $collective)
    {
		return view("collectives.leave", ["collective" => $collective]);
    }

    /**
     * Add the joining user to the group.
     */
    public function leave(Collective $collective)
    {
        $collective->members()->detach(auth()->user()->id);
        return redirect( route("collectives.show", ["collective" => $collective]) );
    }
}
