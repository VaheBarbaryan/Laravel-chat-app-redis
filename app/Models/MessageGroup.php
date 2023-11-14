<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageGroup extends Model
{
    protected $table = 'message_groups';

    protected $fillable = [
        'user_id', 'name'
    ];

    public function message_groups_member()
    {
        return $this->hasMany(MessageGroupMember::class, 'message_group_id', 'id');
    }

    public function user_messages() {
        return $this->hasMany(UserMessage::class);
    }
}
