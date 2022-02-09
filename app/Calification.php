<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calification extends Model
{
    protected $fillable = [
        'id_service', 'id_service_provider', 'rating', 'comments'
    ];


    protected $table = 'rating';
}
