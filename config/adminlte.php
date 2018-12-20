<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'Warehouse App 1.0',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Warehouse</b> App',

    'logo_mini' => '<b>W</b>App',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'blue',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'master/home',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        [
            'text' => 'Master Data',
            'icon' => 'info',
            'can'  => 'isAdmin',
            'submenu' => [

              [
                  'text' => 'User',
                  'icon' => 'user',
                  'can'  => 'isAdmin',
                  'submenu' => [
                      [
                          'text' => 'List & Add User',
                          'icon' => 'caret-right',
                          'url'  => 'user/register',
                      ],
                      [
                          'text' => 'Edit Profile',
                          'icon' => 'caret-right',
                          'url'  => 'user/profileedit',
                      ],
                  ],
              ],
              [
                  'text' => 'Brand',
                  'url'  => 'master/brand',
                  'can'  => 'isAdmin',
                  'icon' => 'caret-right',
              ],

              [
                  'text' => 'Measure',
                  'url'  => 'master/measure',
                  'can'  => 'isAdmin',
                  'icon' => 'caret-right',
              ],
              [
                  'text' => 'Supplier',
                  'url'  => 'master/supplier',
                  'icon' => 'caret-right',
                  'can'  => 'isStaff',
              ],
              [
                  'text' => 'Warehouse Data',
                  'url'  => 'master/warehouse',
                  'can'  => 'isAdmin',
                  'icon' => 'caret-right',
              ],
            ],
        ],
        [
            'text' => 'Delete Data',
            'icon' => 'trash',
            'can'  => 'isSuperAdmin',
            'submenu' => [
                [
                    'text' => 'User',
                    'icon' => 'caret-right',
                    'url'  => '/delete/user',
                ],
                [
                    'text' => 'Brand',
                    'icon' => 'caret-right',
                    'url'  => '/delete/brand',
                ],
                [
                    'text' => 'Measure',
                    'icon' => 'caret-right',
                    'url'  => '/delete/measure',
                ],
                [
                    'text' => 'Supplier',
                    'icon' => 'caret-right',
                    'url'  => '/delete/supplier',
                ],
                [
                    'text' => 'Warehouse Data',
                    'icon' => 'caret-right',
                    'url'  => '/delete/warehouse',
                ],
                [
                    'text' => 'Product',
                    'icon' => 'caret-right',
                    'url'  => '/delete/product',
                ],
                [
                    'text' => 'Purchase',
                    'icon' => 'caret-right',
                    'url'  => '/delete/purchase',
                ],
                [
                    'text' => 'Send',
                    'icon' => 'caret-right',
                    'url'  => '/delete/send',
                ],
                [
                    'text' => 'Inventory',
                    'icon' => 'caret-right',
                    'url'  => '/delete/inventory',
                ],
            ],
        ],

        'APPLICATION',
        [
            'text' => 'Profile',
            'icon' => 'user',
            'submenu' => [
                [
                    'text' => 'Show Profile',
                    'icon' => 'caret-right',
                    'url'  => 'user/profile',
                ],
                [
                    'text' => 'Change Password',
                    'icon' => 'caret-right',
                    'url'  => 'user/changepasswd',
                ],
            ],
        ],
        [
            'text' => 'Product',
            'icon' => 'product-hunt',
            'submenu' => [
                [
                    'text' => 'Product List',
                    'icon' => 'caret-right',
                    'url'  => 'product',
                    'can'  => 'isStaff',
                ],
                [
                    'text' => 'Manual Input',
                    'icon' => 'caret-right',
                    'url'  => 'product/manual',
                    'can'  => 'isManager',
                ],
                [
                    'text' => 'Product Image',
                    'icon' => 'caret-right',
                    'url'  => 'product/image',
                    'can'  => 'isStaff',
                ],
            ],
        ],
        [
            'text' => 'Purchase',
            'icon' => 'money',
            'submenu' => [
                [
                    'text' => 'Purchase List',
                    'icon' => 'caret-right',
                    'url'  => 'purchase',
                    'can'  => 'isStaff',
                ],
                [
                    'text' => 'Purchase Order',
                    'icon' => 'caret-right',
                    'url'  => 'purchase/order',
                    'can'  => 'isStaff',
                ],
            ],
        ],
        [
            'text' => 'Stock Opname',
            'url'  => '/stockopname',
            'icon' => 'sticky-note',
            'can'  => 'isManager',
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
        'select2'    => true,
        'chartjs'    => true,
    ],
];
