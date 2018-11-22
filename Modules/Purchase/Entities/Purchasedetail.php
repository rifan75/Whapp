<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;

class Purchasedetail extends Model
{
    protected $table = "lerp_purchase_detail";

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

    public function product()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purchase','purchase_id','id');
    }
}
