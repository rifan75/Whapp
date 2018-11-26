<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;

use Hashids\Hashids;

class User extends Model
{

    protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['id']);
    }

}
