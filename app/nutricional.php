<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nutricional extends Model
{
    protected $fillable = [
        'id_cliente', 'fecha', 'time', 'clinic','status_surgeries','nutri'
    ];

    protected $table         = 'nutricional';
    public    $timestamps    = false;
    protected $primaryKey    = 'id_nutricional';



    public function comments(){
        return $this->hasMany('App\Comments', 'id_event', 'id_nutricional')
                    ->where("comments.table", "nutricional")
                    ->select(array('comments.*'));
    }



}
