<?php

namespace Modules\Delete\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    use SoftDeletes;

    protected $softCascade = ['profile'];
    protected $table = "users";

    public function profile()
    {
        return $this->hasOne('Modules\Delete\Entities\Profile','user_id','id');
    }
}
