<?php

namespace Modules\User\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Level extends Authenticatable
{
    use Notifiable;
   	protected $table = "level";
}
