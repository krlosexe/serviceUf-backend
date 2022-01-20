<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    protected $fillable = [
        'id_client', 'id_category', 'date', 'phone', 'address', 'comments', 'photo'
    ];

    protected $table         = 'request_service';
}
