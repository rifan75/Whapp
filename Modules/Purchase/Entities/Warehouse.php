<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $table = "warehouse";

    public function getHashinchargeAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['incharge']);
    }

    public function user()
    {
        return $this->belongsTo('Modules\Purchase\Entities\User','user_id','id');
    }

    public function inchargedata()
    {
        return $this->belongsTo('Modules\Purchase\Entities\User','incharge','id');
    }


}
