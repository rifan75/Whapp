<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Hashids\Hashids;

class Send extends Model
{
    use SoftDeletes;
    protected $table = "send";
    protected $appends = ['hashid'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'letter_date',
        'arr_date',
        'deleted_at'
    ];

    protected $fillable = [
        'no_letter',
        'user_id',
        'note',
        'from',
        'sendto',
        'letter_date',
        'arr_date',
    ];

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

    public function warehousefrom()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Warehouse','from','code');
    }

    public function warehousesendto()
    {
        return $this->belongsTo('Modules\Warehouse\Entities\Warehouse','sendto','code');
    }

    public function senddetail()
    {
        return $this->hasMany('Modules\Warehouse\Entities\Senddetail','send_id','id');
    }

}
