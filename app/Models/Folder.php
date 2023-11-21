<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Artwork;
use App\Models\User;

class Folder extends Model
{
    use HasFactory;

	protected $fillable = [
		"title",
		"user_id",
		"parent_folder_id",
		"depth"
	];

	public function artworks() : HasMany {
		return $this->hasMany(Artwork::class);
	}
	
	public function user() : BelongsTo {
		return $this->belongsTo(User::class);
	}

	public function parent() : BelongsTo {
		return $this->belongsTo(Folder::class, "parent_folder_id");
	}
	
	public function children() : HasMany {
		return $this->hasMany(Folder::class, "parent_folder_id");
	}
}
