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
use App\Models\Collective;
use App\Services\FolderListService;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

class Folder extends Model
{
    use HasFactory;

	protected $fillable = [
		"title",
		"user_id",
		"parent_folder_id",
		"privacy_level_id",
		"collective_id"
	];

	public function artworks() : BelongsToMany {
		return $this->belongsToMany(Artwork::class)->withTimestamps();
	}
	
	public function user() : BelongsTo {
		return $this->belongsTo(User::class);
	}
	
	public function collective() : BelongsTo {
		return $this->belongsTo(Collective::class);
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

	public function tags() {
		$tags = collect([]);
		foreach($this->artworks()->with("tags")->get() as $artwork) {
			$tags = $tags->concat($artwork->tags);
		}
		return $tags;
	}

	public function getTree($includeRoot, $maxPrivacyAllowed=5, $withArtworks=false): Collection {
		return FolderListService::class($this)->tree($includeRoot, $maxPrivacyAllowed, $withArtworks);
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
		if($this->user) return $this->id == $this->user->top_folder_id;
		else return $this->id == $this->collective->top_folder_id;
	}

	public function getDisplayName() {
		if($this->isTopFolder()) return "Unsorted";
		return $this->title;
	}
}
