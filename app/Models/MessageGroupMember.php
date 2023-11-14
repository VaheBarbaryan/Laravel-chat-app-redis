<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageGroupMember extends Model
{
    protected $table = 'message_groups_members';

    protected $fillable = [
        'user_id', 'message_group_id', 'status'
    ];

    public function message_groups()
    {
        return $this->belongsTo(MessageGroup::class);
    }
}
