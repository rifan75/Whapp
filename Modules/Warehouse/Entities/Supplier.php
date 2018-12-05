<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = "supplier";

    public function user()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\User','user_id','id');
    }
}
