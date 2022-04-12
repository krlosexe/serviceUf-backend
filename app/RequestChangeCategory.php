<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestChangeCategory extends Model
{
    protected $fillable = [
        'id_client', 'status'
    ];

    protected $table = 'request_chage_category';


    public function items()
    {
        return $this->hasMany('App\RequestChangeCategoryItems', 'id_request_chage_category')
                    ->join('category', 'category.id', '=', 'request_chage_category_items.id_category');
    }


}
