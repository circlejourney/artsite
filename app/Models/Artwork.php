<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\UploadService;
use Illuminate\Http\UploadedFile;

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

	public function getText() : string {
		$relative_path = $this->text;
		if(!$relative_path) return "";
		$text = Storage::get($relative_path);
		return $text ?? "";
	}

	public function updateText($text) {
		if(!$relative_path = $this->text)
		{
			$relative_path = UploadService::create(Str::random(20) . ".txt", $text ?? "", "artwork-text/".$this->id)->getRelativePath();
			$this->text = $relative_path;
		} else
		{
			Storage::put($relative_path, $text ?? "");
		}
		return $this;
	}

	public function writeImage(int $i, UploadedFile $image, User $user) {
		if(isset($this->images[$i]) && $relative_path = $this->images[$i]) Storage::delete($relative_path);
		$imageupload = UploadService::upload($image, "art/".$user->id);
		$images = $this->images;
		$images[$i] = $imageupload->getRelativePath();
		$this->images = $images;
		return $this;
	}

	public function generateThumbnail() {
		if(isset($this->images[0])) {
			if($relative_path = $this->thumbnail) {
				Storage::delete($relative_path);
			}
			$this->thumbnail = UploadService::find($this->images[0])->makeThumbnail(300)->getRelativePath();
		}
		return $this;
	}

	public function deleteText() {
		if($relative_path = $this->text) {
			Storage::delete($relative_path);
		}
		$this->text = null;
		return $this;
	}

	public function deleteThumbnail() {
		if($relative_path = $this->thumbnail) {
			Storage::delete($relative_path);
		}
		$this->thumbnail = null;
		return $this;
	}

	public function deleteImage($i) {
		if(isset($this->images[$i]) && $relative_path = $this->images[$i]) Storage::delete($relative_path);
		$images = $this->images;
		unset($images[$i]);
		$this->images = array_values($images);
		return $this;
	}

	public function deleteAllImages() {
		foreach($this->images as $image) {
			if($image) Storage::delete($image);
		}
		$this->images = null;
		return $this;
	}
}
