<?php


use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type')->insert(array(
            [
            'name' => 'Beginning Balance',
            ],
            [
            'name' => 'Subtraction-(exp : missing item, broken item, etc)',
            ],
            [
            'name' => 'Added-(exp : gift, retur, etc)',
            ],
            [
            'name' => 'Adjusting - Stock Opname',
            ],
        ));
    }
}
