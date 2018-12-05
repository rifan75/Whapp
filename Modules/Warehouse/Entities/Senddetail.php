<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Awobaz\Compoships\Compoships;
use Hashids\Hashids;

class Senddetail extends Model
{
    use SoftDeletes;
    use Compoships;
    protected $table = "send_detail";
    protected $appends = ['hashproduct'];
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
        'send_id',
        'product_id',
        'quantity',
        'measure',
        'sub_total',
        'from',
        'user_id',
        'inventory_id',
    ];

    public function getHashproductAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['product_id']);
    }

    public function product()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Product','product_id','id');
    }

    public function send()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Send','send_id','id');
    }

    public function maks()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Inventorysum',['product_id','from'],['id_product','warehouse']);
    }
}
