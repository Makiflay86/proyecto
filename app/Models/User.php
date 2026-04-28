<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        ];
    }

    public function likedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_likes')->withPivot('created_at');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function unreadThreadsCount(): int
    {
        $lastIds = Message::selectRaw('MAX(id) as id')
            ->when(! $this->is_admin, fn ($q) => $q->where('thread_user_id', $this->id))
            ->groupBy('product_id', 'thread_user_id')
            ->pluck('id');

        return Message::whereIn('id', $lastIds)
            ->whereNull('read_at')
            ->where('sender_id', '!=', $this->id)
            ->count();
    }
}
