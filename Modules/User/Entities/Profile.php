<?php

namespace Modules\User\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Profile extends Authenticatable
{
    use Notifiable;
   	protected $table = "user_detail";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'department',
        'hire_date',
        'birth_date',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'phone',
        'pos_code',
        'recorder'
    ];

    public function user()
    {
        return $this->belongsTo('Modules\User\Entities\User','user_id','id');
    }

    public function recorderdata()
    {
        return $this->belongsTo('Modules\User\Entities\User','recorder','id');
    }
}
