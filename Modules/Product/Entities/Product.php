<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = "product";

    protected $casts = [
           'image_path' => 'array',
        ];
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
      'measure',
      'brand',
      'model',
      'color',
      'hazardwarning',
      'warranty_type',
      'image_path',
      'user_id',
      'active',
    ];

    public function user()
    {
        return $this->belongsTo('Modules\Product\Entities\User','user_id','id');
    }

}
