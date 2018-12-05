<?php

namespace Modules\Delete\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $table = "inventory";
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function user()
    {
        return $this->belongsTo('Modules\Delete\Entities\User','user_id','id');
    }

    public function product()
    {
        return $this->belongsTo('Modules\Delete\Entities\Product','id_product','id');
    }

    public function warehouse_data()
    {
        return $this->belongsTo('Modules\Delete\Entities\Warehouse','warehouse','id');
    }
}
