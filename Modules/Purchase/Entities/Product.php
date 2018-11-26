<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "product";

    protected $casts = [
           'image_path' => 'array',
        ];

    public function user()
    {
        return $this->belongsTo('Modules\Purchase\Entities\User','user_id','id');
    }

}
