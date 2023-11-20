<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Artwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'caption'
    ];

	/**
	 * Get the users (creators) of the artwork
	 */
	public function artworks(): BelongsToMany {
		return $this->belongsToMany(User::class);
	}
}
