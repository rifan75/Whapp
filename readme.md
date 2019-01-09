Warehouse Application (Whapp) is an app to manage warehouse process, from input such as : purchasing and output such as : sending items. This application built with the best PHP Framework Laravel and can be modify easily, because this application is module base and no built in hidden function.

I. Requirement For PHP

    - PHP >= 7.1.3
    - OpenSSL PHP Extension
    - PDO PHP Extension
    - Mbstring PHP Extension
    - Tokenizer PHP Extension
    - XML PHP Extension
    - Ctype PHP Extension
    - JSON PHP Extension


II. Requirement For Database

    - MySQl >= 5.7


III. Install Application

    - Copy Application Archieve to Web Server Folder and Extract it
    - Change file .env.example to .env, and change data in there with your data

    - Run Code :
	1. php artisan key:generate
	   this command will generate key in .env fila

	2. php artisan migrate --seed
	   this command will generate table in mysql

    - By default this application will run on local dev/ http, but if you want to use force https,
      modify ENFORCE_SSL value to true and uncomment this code :

	if(config('app.ssl')) {
                 	    $url->forceScheme('https');
          }

     at AppServiceProvider.php file.

IV. Login Credentials

By default, Warehouse Application is already has super admin, admin, manager and staff credentials

    - Credential For Super Admin :
        Username : super_admin@admin.com
        Password : secret
    - Credential For Admin :
        Username : admin@admin.com
        Password : secret
    - Credential For Manager :
        Username : manager@admin.com
        Password : secret
    - Credential For Staff :
        Username : staff@admin.com
        Password : secret

V. Modules

- Master

  This Modules Handle :

      Brand Create, Read, Update, Delete
      Measure Create, Read, Update, Delete
      Supplier Create, Read, Update, Delete
      Warehouse Create, Read, Update, Delete


- Delete

  This Modules Handle :

      Soft Delete to Permanently Delete


- Product

  This Modules Handle :

      Product Create, Read, Update, Delete
      Input Manual Inventory
      Product Image Create, Read, Update, Delete


- Purchase

  This Modules Handle :

      Purchase Create, Read, Update, Delete


- User

  This Modules Handle :

      Login and Register
      Profile
      Change Password
      Add & Update User


- Warehouse

  This Modules Handle :

      Accepting Items From Other Warehouse
      Sending Items From Other Warehouse
      Inventory Process
      Stock Opname



VI. Plugins

- Admin LTE

  You can find find anything about this plugin in this address, https://github.com/jeroennoten/Laravel-AdminLTE

- Laravel Modules

  You can find find anything about this plugin in this address, https://github.com/nWidart/laravel-modules


For demo you can go to : https://warehouse-demo.baihaqie.com, and for documentation : https://warehouse-doc.baihaqie.com

License

The Laravel framework is open-sourced software licensed under the MIT license.
