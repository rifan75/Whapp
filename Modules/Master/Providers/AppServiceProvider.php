<?php

namespace Modules\Master\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Modules\Master\Entities\Warehouse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
      $events->listen(BuildingMenu::class, function (BuildingMenu $event) {

          $items = Warehouse::all()->map(function (Warehouse $warehouse) {
              return [
                  'text' => $warehouse['name'],
                  'icon' => 'home',
                  'submenu' => [
                                  [
                                    'text' => 'Local Inventory',
                                    'url'  => 'admin/db-admin',
                                    'icon' => 'user',
                                  ],
                                  [
                                    'text' => 'Sending Stock',
                                    'url'  => 'admin/db-admin',
                                    'icon' => 'user',
                                  ],
                                  [
                                    'text' => 'Accept Stock',
                                    'url'  => 'admin/db-admin',
                                    'icon' => 'user',
                                  ]
                              ]
              ];
          });

          $event->menu->add(...$items);
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
