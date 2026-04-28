<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'thread_user_id', 'sender_id', 'body', 'created_at'];

    protected $casts = [
        'read_at'    => 'datetime',
        'created_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function threadUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'thread_user_id');
    }
}
