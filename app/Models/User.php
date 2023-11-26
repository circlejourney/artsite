<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Artwork;
use App\Models\Folder;
use App\Models\Role;
use App\Models\Invite;
use App\Services\UploadService;
use App\Services\SanitiseService;
use App\Services\FolderListService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
		'display_name',
		'avatar',
		'banner',
		'profile_html',
		'custom_flair'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

	/* Boot */

	public static function boot() {
		parent::boot();
		Static::created(function($model){
			$model->createTopFolder();
			$user_role = Role::where("name", "user")->first()->id;
			$model->roles()->attach($user_role);
		});
	}

	/**
	 * Relations
	 */
	public function artworks(): BelongsToMany {
		return $this->belongsToMany(Artwork::class);
	}

	public function folders(): HasMany {
		return $this->hasMany(Folder::class);
	}

	public function roles(): BelongsToMany {
		return $this->belongsToMany(Role::class)->withTimestamps();
	}

	public function invites(): HasMany {
		return $this->hasMany(Invite::class, "creator_id");
	}

	/* Utility */

	public function createTopFolder() {
		$top_folder = Folder::create([
			"title" => "Unsorted",
			"user_id" => $this->id
		]);
		$this->top_folder_id = $top_folder->id;
		$this->save();
	}

	public function getTopFolder() {
		return Folder::where("id", $this->top_folder_id)->first();
	}

	public function getFolderTree($includeRoot, $maxPrivacyAllowed=5): Collection {
		return FolderListService::class($this->getTopFolder())->tree($includeRoot, $maxPrivacyAllowed);
	}
	
	public function hasRole($role) {
		return $this->roles()->pluck("name")->contains($role);
	}

	public function controlsRoles() {
		if($this->hasRole("founder")) return Role::all();
		$toproleid = $this->getTopRole()->id;
		return Role::all()->sortBy("id")->slice($toproleid);
	}
	
	public function getTopRole() {
		return $this->roles()->orderBy("id", "asc")->first();
	}

	public function getTags() {
		$tags = $this->artworks()->with(["tags"])->get()->pluck("tags")->flatten();
		$tags = $tags->unique("id")->sortByDesc(function($i) use($tags) {
			return $tags->pluck("id")->countBy()->get($i->id);
		})->values();
		return $tags;
	}
	
	public function hasPermissions($permission) {
		return $this->roles()->get()->pluck($permission)->contains(true);
	}

	public function getProfileHTML(): string {
		if(!$this->profile_html) return "";
		$profile_html_content = Storage::get($this->profile_html);
		if(!$profile_html_content) return "";
		return SanitiseService::sanitiseHTML($profile_html_content);
	}

	public function getFlairHTML(): string {
		$faString = $this->custom_flair ?? $this->getTopRole()->default_flair;
		return "<i class='user-flair fa fa-$faString'></i>";
	}

	public function getAvatarURL() {
		if(!$this->avatar) return "/images/user.png";
		return Storage::url($this->avatar);
	}

	public function getBannerURL() {
		if(!$this->banner) return "/images/defaultbanner.png";
		return Storage::url($this->banner);
	}

	public function updateProfileHTML(string $profile_html) {
		$relative_path = $this->profile_html;
		if(!$relative_path)
		{
			$relative_path = UploadService::create(Str::random(20) . ".txt", $profile_html, "profiles/".$this->id)->getRelativePath();
			$this->profile_html = $relative_path;
		} else {
			Storage::put($relative_path, $profile_html);
		}
		return $this;
	}

	public function updateAvatar(UploadedFile $avatar_file) {
		if(!$avatar_file) return $this;
		if($this->avatar) UploadService::find($this->avatar)->delete();
		$avatar = UploadService::upload($avatar_file, "avatars/".$this->id)
			->resizeToFit(300)->getRelativePath();
		$this->avatar = $avatar;
		return $this;
	}

	public function updateBanner(UploadedFile $banner_file) {
		if(!$banner_file) return $this;
		if($this->banner) UploadService::find($this->banner)->delete();
		$banner = UploadService::upload($banner_file, "banners/".$this->id)
			->resizeToFit(1200)->getRelativePath();
		$this->banner = $banner;
		return $this;
	}
}
