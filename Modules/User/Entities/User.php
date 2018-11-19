<?php

namespace Modules\User\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Hashids\Hashids;

class User extends Authenticatable
{
    use Notifiable;

    protected $appends = ['hashid'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','level', 'active', 'picture_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getHashidAttribute()
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        return $hashids->encode($this->attributes['id']);
    }

    public function levelin()
    {
        return $this->belongsTo('Modules\User\Entities\Level','level','id');
    }

    public function profile()
    {
        return $this->hasOne('Modules\User\Entities\Profile');
    }
}
