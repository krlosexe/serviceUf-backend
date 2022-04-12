<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestChangeCategoryItems extends Model
{
    protected $fillable = [
        'id_request_chage_category', 'id_category', 'value'
    ];

    protected $table = 'request_chage_category_items';


}
