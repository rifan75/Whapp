<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;

class Stockopname extends Model
{
    protected $table = "stockopname";

    protected $dates = [
        'stockopname_date',
    ];

    protected $fillable = [
        'no_stockopname',
        'user_id',
        'note',
        'location',
        'stockopname_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\User','user_id','id');
    }

    public function warehouse()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Warehouse','location','code');
    }

}
