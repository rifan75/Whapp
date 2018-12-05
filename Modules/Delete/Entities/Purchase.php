<?php

namespace Modules\Delete\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    use SoftDeletes;

    protected $softCascade = ['purchasedetail'];
    protected $table = "purchase";

    public function purchasedetail()
    {
        return $this->hasMany('Modules\Delete\Entities\Purchasedetail','purchase_id','id');
    }

    public function supplier()
    {
        return $this->belongsTo('Modules\Delete\Entities\Supplier','supplier_id','id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function($deleted) {
          if ($deleted->isForceDeleting()) {
              $deleted->purchasedetail()->forceDelete();
            }
        });
    }
}
