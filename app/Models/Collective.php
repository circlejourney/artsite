<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
			$model->members()->attach($model->founder_id);
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

	public function topFolder() : HasOne {
		return $this->hasOne(Folder::class, "collective_id");
	}

	public function members() : BelongsToMany {
		return $this->belongsToMany(User::class);
	}

	public function notifications(): HasMany {
		return $this->hasMany(Notification::class, "recipient_collective_id");
	}
}
