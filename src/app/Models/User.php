<?php

namespace App\Models;

use App\Notifications\VerifyEmailCustom;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
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

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom);
    }

    // リレーション
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id')->withTimestamps();
    }

    public function likeItems($item_id)
    {
        return $this->likes()->where('item_id', $item_id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class)
            ->withPivot('last_read_message_id', 'last_read_at')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    public function ratedBy()
    {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }

    public function unreadChatsCount(): int
    {
        return $this->chats()
            ->whereColumn('chats.last_message_id', '>', 'chat_user.last_read_message_id')
            ->count();
    }

    public function unreadMessagesCount(): int
    {
        $totalUnreadCount = 0;

        foreach ($this->chats as $chat) {
            $lastReadMessageId = $chat->pivot->last_read_message_id;

            if (is_null($lastReadMessageId)) {
                $unreadCount = $chat->messages()
                    ->where('sender_id', '!=', $this->id)
                    ->count();
            } else {
                $unreadCount = $chat->messages()
                    ->where('id', '>', $lastReadMessageId)
                    ->where('sender_id', '!=', $this->id)
                    ->count();
            }

            $totalUnreadCount += $unreadCount;
        }

        return $totalUnreadCount;
    }
}
