<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    protected $table = "warehouse";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'state',
        'pos_code',
        'phone',
        'email',
        'user_id',
        'incharge',
        'note',
        'active',
    ];

    public function getHashinchargeAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['incharge']);
    }

    public function user()
    {
        return $this->belongsTo('Modules\Master\Entities\User','user_id','id');
    }

    public function incharge()
    {
        return $this->belongsTo('Modules\Master\Entities\User','incharge','id');
    }


}
