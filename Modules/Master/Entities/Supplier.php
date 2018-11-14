<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = "supplier";

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
        'name',
        'address',
        'city',
        'state',
        'country',
        'pos_code',
        'phone',
        'email',
        'contact_person',
        'user_id',
        'note',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo('Modules\Master\Entities\User','user_id','id');
    }
}
