<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measure extends Model
{
    use SoftDeletes;

    protected $table = "measure";

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
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('Modules\Master\Entities\User','user_id','id');
    }
}
