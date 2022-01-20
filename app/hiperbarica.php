<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hiperbarica extends Model
{
    protected $fillable = [
        'id_cliente', 'fecha', 'time', 'clinic','status_surgeries'
    ];

    protected $table         = 'hiperbarica';
    public    $timestamps    = false;
    protected $primaryKey    = 'id_hiperbarica';



    public function comments(){
        return $this->hasMany('App\Comments', 'id_event', 'id_hiperbarica')
                    ->where("comments.table", "hiperbarica")
                    ->select(array('comments.*'));
    }



}
