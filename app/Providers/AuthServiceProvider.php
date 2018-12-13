<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isSuperAdmin', function ($user) {
          if ($user->level == '1'  )
          {
            return true;
          }
          return false;
        });
        Gate::define('isAdmin', function ($user) {
          if ($user->level == '1' || $user->level == '2')
          {
            return true;
          }
          return false;
        });
        Gate::define('isManager', function ($user) {
          if ($user->level == '1' || $user->level == '2' || $user->level == '3')
          {
            return true;
          }
          return false;
        });
        Gate::define('isStaff', function ($user) {
          if ($user->level == '1' || $user->level == '2' || $user->level == '3' || $user->level == '4')
          {
            return true;
          }
          return false;
        });

    }
}
