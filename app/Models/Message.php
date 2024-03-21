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

    protected static array $relationships = ['user', 'privateMessageRecipients', 'replies'];
    protected $fillable = [
        'type',
        'content',
        'user_id',
    ];

    protected $casts = [
        'type' => MessageTypeEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function privateMessageRecipients(): HasMany
    {
        return $this->hasMany(PrivateMessageRecipient::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }
    protected static function booted(): void
    {
        static::saving(function (Message $message){
            $message->user_id = $message->user_id ?: auth()->id();
        });
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

}
