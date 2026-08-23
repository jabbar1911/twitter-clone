<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class Tweet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'message',
        'image',
    ];

    /**
     * Get the full URL for the tweet image if present.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/'.$this->image);
    }

    /**
     * User who authored the tweet.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Users who liked the tweet.
     *
     * @return BelongsToMany<User, $this>
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tweet_likes')->withTimestamps();
    }

    /**
     * Check if the tweet is liked by a given user.
     */
    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($this->relationLoaded('likes')) {
            return $this->likes->contains('id', $user->id);
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Parse @mentions and #hashtags into clickable links safely.
     */
    public function formattedMessage(): string
    {
        $escaped = e($this->message);

        // Single-pass replacement for mentions and hashtags to prevent hex color collision
        $formatted = preg_replace_callback(
            '/(?<!\w)([@#])([a-zA-Z0-9_]+)/',
            function ($matches) {
                $type = $matches[1];
                $slug = $matches[2];

                if ($type === '@') {
                    return '<a href="/@'.$slug.'" class="text-[#1d9bf0] hover:underline font-normal" onclick="event.stopPropagation()">@'.$slug.'</a>';
                }

                return '<a href="/?search='.urlencode('#'.$slug).'" class="text-[#1d9bf0] hover:underline font-normal" onclick="event.stopPropagation()">#'.$slug.'</a>';
            },
            $escaped
        );

        return nl2br($formatted ?? $escaped);
    }
}
