<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Folder;

class Artwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
		'images',
		'thumbnail',
		'path'
    ];

	protected $casts = [
        'images' => 'array'
    ];

	/**
	 * Get the users (creators) of the artwork
	 */
	public function users(): BelongsToMany {
		return $this->belongsToMany(User::class);
	}

	public function folders(): BelongsToMany {
		return $this->belongsToMany(Folder::class);
	}

	public static function byPath($path) : Artwork {
		return Artwork::Where("path", $path)->first();
	}
}
