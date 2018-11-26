<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = "supplier";

    public function user()
    {
        return $this->belongsTo('Modules\Purchase\Entities\User','user_id','id');
    }
}
