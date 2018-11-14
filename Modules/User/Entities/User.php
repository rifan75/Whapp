<?php

namespace Modules\Master\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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

    public function levelin()
    {
        return $this->belongsTo('Modules\Master\Entities\Level','level','id');
    }

    public function profile()
    {
        return $this->hasOne('Modules\Master\Entities\Profile');
    }
}
