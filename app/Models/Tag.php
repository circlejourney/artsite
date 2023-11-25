<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Artwork;

class Tag extends Model
{
    use HasFactory;
	protected $fillable = [
		"id"
	];

	// Each Tag can belong to many Artworks and each Artwork can belong to many tags.
	public function artworks() : BelongsToMany {
		return $this->BelongsToMany(Artwork::class);
	}
}
