<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = [
        'names', 'last_names', 'identification','birth_date', 'city', 'municipality', 'phone', 
        'email', 'address', 'fcmToken', 'password', 'service_provider', 'type_identification', 'photo_profile'
    ];

    protected $table         = 'clientes';
    protected $primaryKey    = 'id';
}
