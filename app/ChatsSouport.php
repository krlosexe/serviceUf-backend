<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatsSouport extends Model
{
    protected $fillable = [
        'sender', 'receiver', 'message'
    ];


    protected $table = 'chats_souport';
}
