<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    public function user_messages() {
        return $this->hasMany(UserMessage::class,'message_id','id');
    }

    public function users() {
        return $this->belongsToMany(User::class,'user_messages','message_id','sender_id')
            ->withTimestamps();
    }

}
