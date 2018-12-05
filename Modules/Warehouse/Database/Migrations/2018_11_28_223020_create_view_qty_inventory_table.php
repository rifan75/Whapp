<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewQtyInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE  VIEW `view_sumqty_inventory` AS select any_value(`inventory`.`id`) AS `id`,
        `inventory`.`id_product` AS `id_product`,sum(`inventory`.`in_out_qty`) AS `quantity`,
        sum(`inventory`.`sub_total`)/sum(`inventory`.`in_out_qty`) AS `price`,
        `inventory`.`measure` AS `measure`,`inventory`.`warehouse` AS `warehouse` from `inventory`
        where `inventory`.`deleted_at` IS NULL
        group by `inventory`.`id_product`,
        `inventory`.`measure`,`inventory`.`warehouse`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW view_sumqty_inventory");
    }
}
