<?php

namespace Modules\Delete\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Senddetail extends Model
{
    use SoftDeletes;
    protected $table = "send_detail";
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->belongsTo('Modules\Delete\Entities\Product','product_id','id');
    }
}
