<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientsServicesCategory extends Model
{
    protected $fillable = [
        'id_client', 'id_category'
    ];

    protected $table         = 'clientes_services_category';
    protected $primaryKey    = 'id';
}
