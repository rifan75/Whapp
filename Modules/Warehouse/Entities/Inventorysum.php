<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class Inventorysum extends Model
{
    use Compoships;
    protected $table = "view_sumqty_inventory";

    public function product()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Product','id_product','id');
    }
}
