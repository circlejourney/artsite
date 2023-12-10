<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtInvite extends Model
{
    use HasFactory;

	protected $fillable = ["user_id", "sender_id", "artwork_id"];
	protected $casts = ["art_invite_id"=>"integer"];

	public function user() : BelongsTo {
		return $this->belongsTo(User::class);
	}

	public function sender() : BelongsTo {
		return $this->belongsTo(User::class, "sender_id");
	}

	public function artwork() : BelongsTo {
		return $this->belongsTo(Artwork::class, "artwork_id");
	}
}
