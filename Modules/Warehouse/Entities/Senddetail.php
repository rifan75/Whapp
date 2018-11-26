<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;

class Senddetail extends Model
{
    protected $table = "send_detail";

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
        'user_id',
        'jenis',
        'maksimum',
        'inventory_id',
    ];

    public function product()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Product','product_id','id');
    }

    public function kirim()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Send','send_id','id');
    }
}
