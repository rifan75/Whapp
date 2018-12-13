<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array(
            [
            'name' => 'Super Admin',
            'email' => 'super_admin@admin.com',
            'level' => 1,
            'password' => bcrypt('secret'),
            'active' => '1',
            'picture_path' => "users/images/picture.jpg",
            ],
            [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'level' => 2,
            'password' => bcrypt('secret'),
            'active' => '1',
            'picture_path' => "users/images/picture.jpg",
            ],
            [
            'name' => 'Manager',
            'email' => 'manager@admin.com',
            'level' => 3,
            'password' => bcrypt('secret'),
            'active' => '1',
            'picture_path' => "users/images/picture.jpg",
            ],
            [
            'name' => 'Staff',
            'email' => 'staff@admin.com',
            'level' => 4,
            'password' => bcrypt('secret'),
            'active' => '1',
            'picture_path' => "users/images/picture.jpg",
            ],

        ));
        DB::table('user_detail')->insert(array(
            ['user_id' => 1,'recorder' => 1,],
            ['user_id' => 2,'recorder' => 1,],
            ['user_id' => 3,'recorder' => 1,],
            ['user_id' => 4,'recorder' => 1,],
        ));
        DB::table('warehouse')->insert(array(
            [
            'user_id' => 1,
            'name' => 'Warehouse',
            'code' => 'WH1',
            'incharge' => 1,
            ]
        ));
        DB::table('level')->insert(array(
            ['name' => 'Super Admin',],
            ['name' => 'Admin',],
            ['name' => 'Manager',],
            ['name' => 'Staff',],
        ));
    }
}
