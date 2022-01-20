<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalificationsAdvisers extends Model
{

    protected $fillable = [
        'id_user', 'type', 'description', 'fecha', 'evidence'
    ];

    protected $table         = 'califications_advisers';
    public    $timestamps    = true;


}
