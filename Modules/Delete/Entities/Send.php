<?php

namespace Modules\Delete\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Hashids\Hashids;

class Send extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    use SoftDeletes;
    protected $table = "send";
    protected $softCascade = ['senddetail'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('Modules\Delete\Entities\User','user_id','id');
    }

    public function warehousefrom()
    {
        return $this->belongsTo('Modules\Delete\Entities\Warehouse','from','code');
    }

    public function senddetail()
    {
        return $this->hasMany('Modules\Delete\Entities\Senddetail','send_id','id');
    }
}
