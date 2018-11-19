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
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'level' => 1,
            'password' => bcrypt('secret'),
            'active' => '1',
            'picture_path' => "users/images/picture.jpg",
            ]
        ));
        DB::table('user_detail')->insert(array(
            [
            'user_id' => 1,
            'recorder' => 1,
            ]
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
            [
            'name' => 'Super Admin',
            ],
            [
            'name' => 'Admin',
            ],
            [
            'name' => 'Manager',
            ],
            [
            'name' => 'Staff',
            ],
        ));
    }
}
