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
		"type", "sender_id", "sender_collective_id", "artwork_id", "content"
	];

	public function getDisplayHTML() {
		/* Type can be:
		 * [User] follow, fave, comment, message, ping
		 * [Collective] collective-join, collective-follow, collective-comment
		 * ...more to be added
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
		if($this->content) return $this->content;
		return "You received a notification of an unknown type.";
	}

	public function getSenderHTML() {
		return view("components.nametag", ["user" => $this->sender]);
	}

	public function getArtworkHTML() {
		return "<a href=".route("art", ["path" => $this->artwork->path]).">" . $this->artwork->title . "</a>";
	}

	public function sender() : BelongsTo {
		return $this->belongsTo(User::class, "sender_id");
	}

	public function artwork() : BelongsTo {
		return $this->belongsTo(Artwork::class);
	}

	public function recipients() : BelongsToMany {
		return $this->belongsToMany(User::class, "notification_recipient", "notification_id", "recipient_id")->withTimestamps();
	}

	public function recipientCollectives() : BelongsToMany {
		return $this->belongsToMany(User::class, "notification_recipient", "notification_id", "recipient_collective_id")->withTimestamps();
	}

	/* Utility */
	public function deleteFor(User $user) {
		if($this->recipients()->get()->pluck("id")->doesntContain($user->id)) return false;
		$this->recipients()->detach($user->id);
		if($this->recipients->count() == 0) $this->delete();
	}

	public static function dispatch(User $sender, Collection $recipients, Collection $props) {
		$params = collect([
			"sender_id" => $sender->id,
			"type" => "fave"
		])->merge($props)->all();
		
		$notif = Notification::create($params);
		$notif->recipients()->attach($recipients->pluck("id")->reject($sender->id));
	}
}
