<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

	protected $fillable = [
		"default_flair"
	];

	function users(): BelongsToMany {
		return $this->belongsToMany(User::class)->withTimestamps();
	}

	function getDefaultFlairHTML() {
		return "<i class='fa fa-$this->default_flair'></i>";
	}
}
