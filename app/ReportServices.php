<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportServices extends Model
{
    protected $fillable = [
        'id_service', 'id_client', 'comments'
    ];

    protected $table = 'reports_services';
}
