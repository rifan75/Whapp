<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hashids\Hashids;

class Inventory extends Model
{
    use SoftDeletes;

    protected $table = "inventory";
    protected $appends = ['hashproduct','hashwh'];
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
    protected $fillable = [
        'id_product',
        'user_id',
        'in_out_qty',
        'measure',
        'note',
        'warehouse',
        'sub_total',
        'type',
        'arr_date',
        'opname',
    ];

    public function getHashproductAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['id_product']);
    }
    public function getHashwhAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['warehouse']);
    }
    public function user()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\User','user_id','id');
    }

    public function product()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Product','id_product','id');
    }

    public function warehousedata()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Warehouse','warehouse','id');
    }

    public function typedata()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Type','type','id');
    }

}
