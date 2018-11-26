<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;

class Send extends Model
{
    protected $table = "send";

    protected $dates = [
        'letter_date',
        'arr_date'
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
