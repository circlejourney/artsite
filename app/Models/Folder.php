<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Artwork;
use App\Models\User;
use App\Services\FolderListService;
use Illuminate\Support\Collection;

class Folder extends Model
{
    use HasFactory;

	protected $fillable = [
		"title",
		"user_id",
		"parent_folder_id",
		"privacy_level_id"
	];

	public function artworks() : BelongsToMany {
		return $this->belongsToMany(Artwork::class)->withTimestamps();
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

	public function allChildren() {
		return $this->children()->with("allChildren");
	}

	public function ancestors() {
		return $this->parent()->with("ancestors");
	}

	public function getTree($includeRoot, $maxPrivacyAllowed=5): Collection {
		return FolderListService::class($this)->tree($includeRoot, $maxPrivacyAllowed);
	}

	public function getLineage() {
		return FolderListService::class($this)->lineage();
	}

	public function getLineagePrivacyLevel() {
		return $this->getLineage()->pluck("privacy_level_id")->max();
	}

	public function getChildKeys(): Collection {
		$thisfolder = $this->with("allChildren")->first();
		return FolderListService::class($thisfolder)->tree(false, 5);
	}

	public function isTopFolder() {
		return $this->id == $this->user()->first()->top_folder_id;
	}

	public function getDisplayName() {
		if($this->isTopFolder()) return $this->user()->first()->name . "'s Gallery";
		return $this->title;
	}
}
