<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\User;
use App\InviteCreating;

class Invite extends Model
{
    use HasFactory;
	protected $primaryKey = "id";
	public $incrementing = false;
	protected $keyType = "string";

	protected $fillable = [
		"id",
		"creator_id"
	];

	public function creator(): BelongsTo {
		return $this->belongsTo(User::class, "creator_id");
	}

	public static function boot()
	{
		parent::boot();
		Static::creating(function ($model){
			if(!$model->id) $model->id = Str::random(10);
		});
	}
}
