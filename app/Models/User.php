<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'last_seen_at',
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
            'is_admin' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    public function isOnline(): bool
    {
        return $this->last_seen_at !== null && $this->last_seen_at->gte(now()->subSeconds(30));
    }

    public function likedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_likes')->withPivot('created_at');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function ratingsReceived(): HasMany
    {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }

    public function averageRating(): ?float
    {
        $avg = $this->ratingsReceived()->avg('stars');
        return $avg !== null ? round((float) $avg, 1) : null;
    }

    public function ratingsCount(): int
    {
        return $this->ratingsReceived()->count();
    }

    public function unreadThreadsCount(): int
    {
        if ($this->is_admin) {
            $lastIds = Message::selectRaw('MAX(id) as id')
                ->groupBy('product_id', 'thread_user_id')
                ->pluck('id');
        } else {
            // Como comprador: hilos donde el usuario es el thread_user
            $buyerIds = Message::selectRaw('MAX(id) as id')
                ->where('thread_user_id', $this->id)
                ->groupBy('product_id', 'thread_user_id')
                ->pluck('id');

            // Como vendedor: hilos de sus productos donde el comprador es otro
            $sellerIds = Message::selectRaw('MAX(id) as id')
                ->whereHas('product', fn ($q) => $q->where('user_id', $this->id))
                ->where('thread_user_id', '!=', $this->id)
                ->groupBy('product_id', 'thread_user_id')
                ->pluck('id');

            $lastIds = $buyerIds->merge($sellerIds)->unique();
        }

        return Message::whereIn('id', $lastIds)
            ->whereNull('read_at')
            ->where('sender_id', '!=', $this->id)
            ->count();
    }
}
