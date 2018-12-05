<?php

namespace Modules\Delete\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $table = "warehouse";

    public function dataincharge()
    {
        return $this->belongsTo('Modules\Delete\Entities\User_link','incharge','id');
    }
}
