<?php

namespace Modules\Delete\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table = "product";
    
    public function user()
    {
        return $this->belongsTo('Modules\Delete\Entities\User','user_id','id');
    }

}
