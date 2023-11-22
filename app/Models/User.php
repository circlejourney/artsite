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
		$hasRole = $this->roles()->where("name", $role)->get()->count();
		error_log($hasRole);
		return $hasRole;
	}
}
