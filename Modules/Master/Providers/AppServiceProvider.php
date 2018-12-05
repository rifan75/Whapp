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
                                    'text' => 'Inventory',
                                    'url'  => url('warehouse/'.$warehouse['hashid']),
                                    'icon' => 'user',
                                  ],
                                  [
                                    'text' => 'Sending Stock',
                                    'icon' => 'user',
                                    'submenu' => [
                                        [
                                            'text' => 'List of Sending Stock',
                                            'icon' => 'caret-right',
                                            'url'  => url('warehouse/'.$warehouse['hashid']).'/send',
                                        ],
                                        [
                                            'text' => 'Add Sending Stock',
                                            'icon' => 'caret-right',
                                            'url'  => url('warehouse/addsend/'.$warehouse['code']),
                                        ],
                                    ],
                                  ],
                                  [
                                    'text' => 'Accepting Stock',
                                    'url'  => url('warehouse/'.$warehouse['hashid']).'/accept',
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
