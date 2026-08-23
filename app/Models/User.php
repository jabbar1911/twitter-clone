<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $bio
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Automatically ensure username is lowercase.
     */
    protected function username(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? strtolower(trim($value)) : null,
        );
    }

    /**
     * Get the avatar URL or fallback avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=1d9bf0&color=ffffff&bold=true&size=128';
    }

    /**
     * Tweets published by the user.
     *
     * @return HasMany<Tweet, $this>
     */
    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class);
    }

    /**
     * Tweets liked by the user.
     *
     * @return BelongsToMany<Tweet, $this>
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Tweet::class, 'tweet_likes')->withTimestamps();
    }

    /**
     * Users that follow this user.
     *
     * @return BelongsToMany<User, $this>
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * Users that this user follows.
     *
     * @return BelongsToMany<User, $this>
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Check if this user is following the given user.
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Check if this user is followed by the given user.
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }
}
