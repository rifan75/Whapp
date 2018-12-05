<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;

use Awobaz\Compoships\Compoships;
use Hashids\Hashids;

class User extends Model
{
    use Compoships;
    
    protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['id']);
    }

}
