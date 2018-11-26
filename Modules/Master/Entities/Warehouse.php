<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $table = "warehouse";
    protected $appends = ['hashid',
                          'hashincharge'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        'country',
        'pos_code',
        'phone',
        'email',
        'user_id',
        'incharge',
        'note',
        'active',
    ];
    public function getHashidAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['id']);
    }

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

    public function inchargedata()
    {
        return $this->belongsTo('Modules\Master\Entities\User','incharge','id');
    }


}
