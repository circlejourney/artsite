<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Notification extends Model
{
    use HasFactory;

	protected $fillable = [
		"type", "sender_id", "sender_collective_id", "recipient_collective_id", "artwork_id", "content", "read"
	];

	public function sender() : BelongsTo {
		return $this->belongsTo(User::class, "sender_id");
	}

	public function sender_collective() : BelongsTo {
		return $this->belongsTo(Collective::class, "sender_collective_id");
	}

	public function artwork() : BelongsTo {
		return $this->belongsTo(Artwork::class);
	}

	public function recipients() : BelongsToMany {
		return $this->belongsToMany(User::class, "notification_recipient", "notification_id", "recipient_id")->withTimestamps();
	}

	public function recipient_collective() : BelongsTo {
		return $this->belongsTo(Collective::class, "recipient_collective_id");
	}

	/* Utility */

	public function getDisplayHTML() {
		/* Type can be:
		 * [User] follow, fave, comment, message, ping etc.
		 * [Collective] co-join, co-accept, co-reject, co-invite, co-inv-accept, co-inv-reject, co-follow, co-comment etc.
		 */
		if($this->type == "fave") {
			return "<i class='fa fa-fw fa-heart'></i>&emsp;" . $this->getSenderHTML() . " favorited your artwork " . $this->getArtworkHTML();
		}
		if($this->type == "follow") {
			return "<i class='fa fa-fw fa-user-plus'></i>&emsp;" . $this->getSenderHTML() . " followed you";
		}
		if($this->type == "ping") {
			return "<i class='fa fa-fw fa-user-plus'></i>&emsp;" . $this->getSenderHTML() . " pinged you" . ($this->artwork_id ? " on <a href='" . route("art", ["path" => $this->artwork->path]) . "'>" . $this->artwork->title . "</a>" : "");
		}
		if($this->type == "co-reject") {
			return "<i class='fa fa-fw fa-user-group'></i>&emsp;" . $this->getSenderCollectiveHTML() . " rejected your join request.";
		}
		if($this->type == "co-accept") {
			return "<i class='fa fa-fw fa-user-group'></i>&emsp;" . $this->getSenderCollectiveHTML() . " accepted your join request.";
		}
		if($this->type == "co-inv-reject") {
			return "<i class='fa fa-fw fa-user-group'></i>&emsp;" . $this->getSenderHTML() . " rejected your invite to join " . $this->getRecipientCollectiveHTML();
		}
		if($this->type == "co-inv-accept") {
			return "<i class='fa fa-fw fa-user-group'></i>&emsp;" . $this->getSenderHTML() . " accepted your invite to join " . $this->getRecipientCollectiveHTML();
		}

		if($this->content) return $this->content;
		return "You received a notification of an unknown type.";
	}

	public function getSenderHTML() {
		return view("components.nametag", ["user" => $this->sender]);
	}

	public function getSenderCollectiveHTML() {
		return "<a href='" . route("collectives.show", ["collective" => $this->sender_collective]) . "'>" . $this->sender_collective->display_name . "</a>";
	}

	public function getRecipientCollectiveHTML() {
		return "<a href='" . route("collectives.show", ["collective" => $this->recipient_collective]) . "'>" . $this->recipient_collective->display_name . "</a>";
	}

	public function getArtworkHTML() {
		return "<a href=".route("art", ["path" => $this->artwork->path]).">" . $this->artwork->title . "</a>";
	}

	public function deleteFor(User $user) {
		if($this->recipients()->get()->pluck("id")->doesntContain($user->id)) return false;
		$this->recipients()->detach($user->id);
		if($this->recipients->count() == 0) $this->delete();
	}

	public static function dispatch(User $sender, Collection $recipients, Collection $props=null) {
		$params = collect([
			"sender_id" => $sender->id
		])->merge($props)->all();
		
		$notif = Notification::create($params);
		$notif->recipients()->attach($recipients->pluck("id")->reject($sender->id));
	}

	public static function dispatch_to_collective(User $sender, Collective $recipient_collective, Collection $props=null) {
		$params = collect([
			"sender_id" => $sender->id,
			"recipient_collective_id" => $recipient_collective->id
		])->merge($props)->all();
		Notification::create($params);
	}

	public static function dispatch_from_collective(User $sender, Collective $sender_collective, Collection $recipients, Collection $props=null) {
		$params = collect([
			"sender_id" => $sender->id,
			"sender_collective_id" => $sender_collective->id
		])->merge($props)->all();
		$notif = Notification::create($params);
		$notif->recipients()->attach($recipients->pluck("id"));
	}
}
