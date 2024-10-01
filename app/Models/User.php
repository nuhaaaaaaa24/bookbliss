<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',  // Ensure bio is fillable
        'genre',  // Ensure genre is fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'genre' => 'array',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'users_groups', 'user_id', 'group_id');
    }
    public function books()
    {
        return $this->hasMany(Books::class, 'user_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function createdChallenges()
    {
        return $this->hasMany(Challenge::class);
    }

    public function joinedChallenges()
    {
        return $this->belongsToMany(Challenge::class, 'challenge_user')->withPivot('status', 'progress')->withTimestamps();
    }
    /**
     * Accessor for bio attribute.
     *
     * You can add any specific formatting or manipulation if needed.
     */
    public function getBioAttribute($value)
    {
        return $value ?? ''; // Default to an empty string if bio is null
    }

    /**
     * Accessor for genres attribute.
     *
     * This ensures the genres are returned as an array.
     */
    public function getGenreAttribute($value)
    {
        return $value ? json_decode($value, true) : []; // Ensure we return an array
    }

    public function setGenreAttribute($value)
    {
        $this->attributes['genre'] = json_encode($value); // Convert array to JSON string
    }
}
