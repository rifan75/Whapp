<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = "lerp_purchase";

    protected $dates = [
        'order_date',
        'send_date'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'supplier_id',
        'order_date',
        'total',
        'note',
        'user_id',
        'send_date',
        'payment',
        'sendto',
        'imageinvoice_path',
    ];

    public function purchasedetail()
    {
        return $this->hasMany('App\Purchasedetail','purchase_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier','supplier_id','id');
    }
    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','sendto','id');
    }
}
