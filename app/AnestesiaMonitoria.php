<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnestesiaMonitoria extends Model
{
   protected $fillable = ['id_history_cliente_anestesico','mon_monitoria'

    ];



    protected $table = 'ane_monitoria_tabla';

}
