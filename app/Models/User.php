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
use App\Services\PrivacyLevelService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
		'custom_flair',
		'invited_by',
		'highlights',
		'invitee_count'
    ];

	public function getRouteKeyName()
	{
		return "name";
	}

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
        'highlights' => 'array'
    ];

	/* Boot */

	public static function boot() {
		parent::boot();
		Static::created(function($model){
			$model->createTopFolder();
			
			if(User::count() === 1) {
				$user_role = [
					Role::where("name", "founder")->first()->id,
					Role::where("name", "admin")->first()->id
				];
			} else {
				$user_role = Role::where("name", "user")->first()->id;
			}

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

	public function top_folder(): BelongsTo {
		return $this->belongsTo(Folder::class, "top_folder_id");
	}

	public function roles(): BelongsToMany {
		return $this->belongsToMany(Role::class)->withTimestamps();
	}

	public function tags(): HasMany {
		return $this->hasMany(Tag::class);
	}

	public function tag_highlights(): HasManyThrough {
		return $this->hasManyThrough(TagHighlight::class, Tag::class);
	}

	public function notifications(): BelongsToMany {
		return $this->belongsToMany(Notification::class, "notification_recipient", "recipient_id", "notification_id")->withTimestamps()->withPivot("read");
	}

	public function collective_notifications() : BelongsToMany {
		return $this->belongsToMany(Notification::class, "notification_recipient", "recipient_id", "notification_id")->where("type", "like", "co-%")->withPivot("read");
	}

	public function art_invite_notifications() : BelongsToMany {
		return $this->belongsToMany(Notification::class, "notification_recipient", "recipient_id", "notification_id")->where("type", "like", "art-inv%")->withPivot("read");
	}

	public function invites(): HasMany {
		return $this->hasMany(Invite::class, "creator_id");
	}

	public function invitees(): HasMany {
		return $this->hasMany(User::class, "invited_by");
	}

	public function inviter(): BelongsTo {
		return $this->belongsTo(User::class, "invited_by");
	}

	public function faves(): BelongsToMany {
		return $this->belongsToMany(Artwork::class, "faves")->withTimestamps();
	}

	public function follows() : BelongsToMany {
		return $this->belongsToMany(User::class, "follows", "follower_id", "followed_id")->withTimestamps();
	}

	public function followers() : BelongsToMany {
		return $this->belongsToMany(User::class, "follows", "followed_id", "follower_id")->withTimestamps();
	}

	public function messages() : HasMany {
		return $this->hasMany(Message::class, "recipient_id");
	}

	public function sent_messages() : HasMany {
		return $this->hasMany(Message::class, "sender_id");
	}

	public function collectives() : BelongsToMany {
		return $this->belongsToMany(Collective::class);
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
		return FolderListService::class($this->top_folder)->tree($includeRoot, $maxPrivacyAllowed);
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

	public function getArtWithTag($tag) {
		$taggedArtworks = $this->artworks()->whereHas("tags", function($q) use($tag) {
			return $q->where("id", $tag);
		})->get();
		return $taggedArtworks;
	}
	
	public function hasPermissions($permission) {
		return $this->roles()->get()->pluck($permission)->contains(true);
	}

	public function getProfileHTML(): string {
		if(!$this->profile_html) return "";
		$profile_html_content = Storage::get($this->profile_html);
		if(!$profile_html_content) return "";
		$cleanHTML = SanitiseService::of($profile_html_content)->sanitiseHTML()->get();
		return $cleanHTML;
	}

	public function getFlair(): string {
		return $this->custom_flair ?? $this->getTopRole()->default_flair;
	}

	public function getFlairHTML(): string {
		$faString = $this->custom_flair ?? $this->getTopRole()->default_flair;
		return "<i class='user-flair fa fa-$faString'></i>";
	}

	public function getNametag() {
		return view("components.nametag", ["user" => $this]);
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

	public function syncHighlights(array $toSync) {
		$highlights = $this->highlights ?? array();
		foreach($toSync as $id => $doHighlight) {
			if($this->artworks->pluck("id")->doesntContain($id)) abort(403);
			if(!$highlights || $doHighlight && !in_array($id, $highlights)) array_push($highlights, $id);
			else if(!$doHighlight && $index = array_search($id, $highlights)) unset($highlights[$index]);
		}
		
		$this->update([
			"highlights" => $highlights
		]);
		return $this;
	}
}
