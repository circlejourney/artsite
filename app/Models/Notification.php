<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notification extends Model
{
    use HasFactory;

	protected $fillable = [
		"type", "sender_id", "sender_collective_id", "artwork_id"
	];

	public function getDisplayHTML() {
		/* Type can be:
		 * [User] follow, fave, comment, message, collective-accept-join
		 * [Collective] collective-join, collective-follow, collective-comment
		 * ...more to be added
		 */
		if($this->type == "fave") {
			return "<i class='fa fa-heart'></i> " . $this->getSenderHTML() . " faved your artwork " . $this->getArtworkHTML();
		}
	}

	public function getSenderHTML() {
		return "<a href=".route("user", ["username" => $this->sender->name]).">" . $this->sender->name . "</a>";
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
}
