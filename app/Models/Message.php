<?php

namespace App\Models;

use App\Enums\MessageTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'content',
        'sender_id',
        'recipient_id'
    ];

    protected $casts = [
        'type' => MessageTypeEnum::class,
    ];

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }
    protected static function booted(): void
    {
        static::saving(function (Message $message){
            $message->sender_id = $message->sender_id ?: auth()->id();
        });
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->sender_id === $user->id;
    }

}
