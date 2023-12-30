<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Services\FolderListService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Collective extends Model
{
    use HasFactory;
	
	public function getRouteKeyName(){
		return 'url';
	}

	protected $fillable = [
		"url", "display_name", "avatar", "profile_html", "privacy_level_id"
	];
	
	public static function boot() {
		parent::boot();
		Static::creating(function($model){
			$model->founder_id = auth()->user()->id;
		});
		Static::created(function($model){
			$model->createTopFolder();
			$model->members()->attach([
				1 => ["user_id" => $model->founder_id, "role_id" => 2]
			]);
		});
	}

	public function artworks() {
		return Artwork::whereHas("folders", function($q) {
			$q->where("collective_id", $this->id);
		});
	}

	private function createTopFolder() {
		$folder = Folder::create([
			"title" => "Unsorted",
			"collective_id" => $this->id,
			"privacy_level_id" => $this->privacy_level_id
		]);
		$this->top_folder_id = $folder->id;
		$this->save();
	}

	public function folders() : HasMany {
		return $this->hasMany(Folder::class, "collective_id");
	}

	public function top_folder() : BelongsTo {
		return $this->belongsTo(Folder::class, "top_folder_id");
	}

	public function members() : BelongsToMany {
		return $this->belongsToMany(User::class);
	}

	public function notifications(): HasMany {
		return $this->hasMany(Notification::class, "recipient_collective_id");
	}

	public function getFolderTree($includeRoot, $maxPrivacyAllowed=5): Collection {
		return FolderListService::class($this->top_folder)->tree($includeRoot, $maxPrivacyAllowed);
	}
}
