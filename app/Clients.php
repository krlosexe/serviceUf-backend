<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = [
        'names', 'last_names', 'identification','birth_date', 'city', 'phone', 'email', 'address', 'fcmToken', 'password'
    ];

    protected $table         = 'clientes';
    protected $primaryKey    = 'id';
}
