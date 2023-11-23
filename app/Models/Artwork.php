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

	public function updateText(string $text) {
		$relative_path = $this->text;
		if(!$relative_path)
		{
			$relative_path = UploadService::create(Str::random(20) . ".txt", $text, "profiles/".$this->id)->getRelativePath();
			$this->profile_html = $relative_path;
		} else
		{
			Storage::put($relative_path, $text);
		}
		return $this;
	}
}
