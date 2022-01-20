<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestOfferts extends Model
{
    protected $fillable = [
        'id_service', 'price', 'comments', 'time', 'status'
    ];

    protected $table         = 'request_offerts';
}
