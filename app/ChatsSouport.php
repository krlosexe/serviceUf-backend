<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatsSouport extends Model
{
    protected $fillable = [
        'id_service', 'sender', 'receiver', 'message'
    ];


    protected $table = 'chats';
}
