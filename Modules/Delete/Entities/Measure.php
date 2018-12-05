<?php

namespace Modules\Delete\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measure extends Model
{
    use SoftDeletes;

    protected $table = "measure";
}
