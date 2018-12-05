<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;

use Awobaz\Compoships\Compoships;
use Hashids\Hashids;

class Opnamedetail extends Model
{
    use Compoships;

    protected $table = "opnamedetail";
    protected $appends = ['hashproduct'];

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
        'opname_id',
    ];

    public function getHashproductAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['id_product']);
    }

    public function user()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\User','user_id','id');
    }

    public function product()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Product','id_product','id');
    }

    public function warehouse()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Warehouse','gudang','id');
    }

    public function maks()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Inventorysum',['id_product','warehouse'],['id_product','warehouse']);
    }

}
