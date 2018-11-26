<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Hashids\Hashids;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $table = "warehouse";

    protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['id']);
    }

    public function user()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\User','user_id','id');
    }

    public function inchargedata()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\User','incharge','id');
    }


}
