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
}
