<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tag;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TagHighlight extends Model
{
    use HasFactory;
	protected $fillable = [ "text", "thumbnail", "tag_id" ];
	public $primaryKey = "tag_id";
	public $incrementing = false;

	public function tag() : BelongsTo {
		return $this->belongsTo(Tag::class);
	}
	
	public function user() : HasOneThrough {
		return $this->hasOneThrough(User::class, Tag::class);
	}

	public function uploadThumbnail(UploadedFile $image, User $user) {
		$imageupload = UploadService::upload($image, "tag-thumbnails/".$user->id)->resizeToFit(300);
		$this->update(["thumbnail" => $imageupload->getRelativePath()]);
		return $this;
	}

	public function getThumbnailURL() {
		return $this->thumbnail ? Storage::url($this->thumbnail) : "";
	}
}
