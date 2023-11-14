<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

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
    ];


    // public function lastMessage()
    // {
    //     return $this->hasOne(UserMessage::class, 'id')
    //         ->where(function ($query) {
    //             $query->where('sender_id', $this->id)
    //                 ->orWhere('receiver_id', $this->id);
    //         })
    //         ->where('created_at', function ($query) {
    //             $query->select(DB::raw('MAX(created_at)'))
    //                 ->from('user_messages')
    //                 ->whereColumn('sender_id', 'user_messages.sender_id')
    //                 ->whereColumn('receiver_id', 'user_messages.receiver_id');
    //         });
    // }
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function senderMessage()
    {
        return $this->hasOne(UserMessage::class, 'sender_id')->latest();
    }

    public function allMessages()
    {
        $receiverMessage = $this->receiverMessage()->first();
        $senderMessage = $this->senderMessage()->first();
        dd($receiverMessage->toArray(), $senderMessage->toArray());
        $allMessages = collect([$receiverMessage, $senderMessage])->filter();

        $sortedMessages = $allMessages->sortByDesc('created_at')->values();

        return $sortedMessages;
    }
    // public function lastMessage()
    // {
    //     return $this->hasOne(UserMessage::class, 'receiver_id')
    //         ->where('sender_id', $this->id)
    //         ->orWhere(function ($query) {
    //             $query->where('sender_id', auth()->id())
    //                 ->where('receiver_id', $this->id);
    //         })
    //         ->orderBy('created_at', 'desc');
    // }
}
