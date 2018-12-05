<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Hashids\Hashids;

class Purchase extends Model
{
    use SoftDeletes;
    protected $table = "purchase";

    protected $appends = ['hashid'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'order_date',
        'send_date',
        'deleted_at'
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

    public function getHashidAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['id']);
    }

    public function purchasedetail()
    {
        return $this->hasMany('Modules\Warehouse\Entities\Purchasedetail','purchase_id','id');
    }

    public function user()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\User','user_id','id');
    }

    public function supplier()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Supplier','supplier_id','id');
    }
    public function warehouse()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Warehouse','sendto','id');
    }
}
