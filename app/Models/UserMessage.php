<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserMessage extends Model
{
    protected $table = 'user_messages';

    public function messages()
    {
        return $this->hasOne(Message::class, 'id', 'message_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function message_group()
    {
        return $this->belongsTo(MessageGroup::class);
    }

   // Define the relationship with users table as sender
   public function sender()
   {
       return $this->belongsTo(User::class, 'sender_id');
   }

   // Define the relationship with users table as receiver
   public function receiver()
   {
       return $this->belongsTo(User::class, 'receiver_id');
   }
    // Define the relationship with messages table
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
