<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Folder;
use App\Models\Tag;
use App\Services\SanitiseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;

class Artwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
		'images',
		'thumbnail',
		'path',
		'searchable'
    ];

	protected $casts = [
        'images' => 'array',
		'searchable' => 'boolean'
    ];

	public static function boot() {
		parent::boot();
	}

	/**
	 * Get the users (creators) of the artwork
	 */
	public function users(): BelongsToMany {
		return $this->belongsToMany(User::class);
	}

	public function oldest_user(): User {
		return $this->users()->orderByPivot("id", "asc")->first();
	}

	public function folders(): BelongsToMany {
		return $this->belongsToMany(Folder::class)->withTimestamps();
	}

	public function tags(): BelongsToMany {
		return $this->belongsToMany(Tag::class);
	}

	public function faved_by(): BelongsToMany {
		return $this->belongsToMany(User::class, "faves")->withTimestamps();
	}

	public static function byPath($path) : Artwork {
		return Artwork::Where("path", $path)->first();
	}

	public function getText() : string {
		$relative_path = $this->text;
		if(!$relative_path) return "";
		$text = Storage::get($relative_path) ?? "";
		$text = SanitiseService::of($text)->sanitiseHTML()->formatPings()->get();
		return $text;
	}

	public function getPlainText() : string {
		$relative_path = $this->text;
		if(!$relative_path) return "";
		$text = Storage::get($relative_path);
		$text = SanitiseService::of($text)->stripHTML()->get();
		return $text ?? "";
	}

	public function getThumbnailURL() : string {
		if(!$relative_path = $this->thumbnail) return "";
		$thumbnail = Storage::url($relative_path);
		return $thumbnail ?? "";
	}

	public function getImageURL(int $index=0) : string {
		if(!($images = $this->images) || !isset($images[$index])) return "";
		$relative_path = Storage::url($images[$index]);
		return $relative_path ?? "";
	}

	public function getImageURLs() {
		$image_urls = array_map(function($image){
			return Storage::url($image);
		}, $this->images);
		return $image_urls;
	}
	
	public function getJoinedTags() : string {
		return $this->tags()->orderBy("artwork_tag.created_at", "desc")->pluck("name")->join(", ");
	}

	public function getOwners() {
		return $this->users()->get()->pluck("id");
	}

	public function getPrivacyLevel() {
		$artworkPrivacy = $this->folders()->get()->reduce(function($carry, $folder) {
			return max($folder->privacy_level_id, $carry);
		});
		return $artworkPrivacy;
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

	public function inviteForeignUser(User $user) {
		/*ArtInvite::create([
			"user_id" => $user->id,
			"sender_id" => auth()->user()->id,
			"artwork_id" => $this->id
		]);*/
		$notification = Notification::create([
			"type" => "art-invite",
			"sender_id" => auth()->user()->id,
			"artwork_id" => $this->id
		]);
		$notification->recipients()->attach($user->id);
	}

	public function addForeignUser(User $user) {
		// This is just a helper function, verification that the user isn't already an owner should happen in the controller logic
		$this->users()->attach($user);
		$userfolder = Folder::where("id", $user->top_folder_id)->firstOrFail();
		$this->folders()->attach($userfolder);
		return $this;
	}

	public function removeForeignUser(User $user) {
		$this->users()->detach($user);
		$updatedFolders = $this->folders()->get()->reject(function($i) use($user){ return $i->user == $user; });
		$this->folders()->sync($updatedFolders);
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

	public function uploadImage(UploadedFile $image, User $user) {
		return UploadService::upload($image, "art/".$user->id);
	}

	public function updateThumbnail() {
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
