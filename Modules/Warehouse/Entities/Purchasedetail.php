<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Hashids\Hashids;

class Purchasedetail extends Model
{
    protected $table = "purchase_detail";

    protected $appends = ['hashproductid'];
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
        'purchase_id',
        'product_id',
        'quantity',
        'measure',
        'price',
        'sub_total',
        'user_id',
    ];

    public function getHashproductidAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['product_id']);
    }
    public function product()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Product','product_id','id');
    }

    public function purchase()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Purchase','purchase_id','id');
    }
}
