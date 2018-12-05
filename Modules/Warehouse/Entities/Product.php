<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;

use Awobaz\Compoships\Compoships;

class Product extends Model
{
    use Compoships;
    protected $table = "product";

    protected $casts = [
           'image_path' => 'array',
        ];

    public function user()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\User','user_id','id');
    }

}
