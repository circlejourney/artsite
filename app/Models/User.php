<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Artwork;
use App\Models\Folder;
use App\Models\Role;
use App\Services\UploadService;
use App\Services\SanitiseService;
use Illuminate\Http\UploadedFile;
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
		'profile_html'
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

	/**
	 * Get the artworks of the user
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

	public function createTopFolder() {
		$top_folder = Folder::create([
			"title" => $this->id."_topfolder",
			"user_id" => $this->id
		]);
		$this->top_folder_id = $top_folder->id;
		$this->save();
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
		$toprole = $this->getTopRole();
		return "<i class='user-flair fa fa-$toprole->default_flair'></i>";
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
		if($avatar_file)
		{
			if($this->avatar) UploadService::find($this->avatar)->delete();
			$avatar = UploadService::upload($avatar_file, "avatars/".$this->id)
				->resizeToFit(300)->getRelativePath();
			$this->avatar = $avatar;
		}
		return $this;
	}
}
