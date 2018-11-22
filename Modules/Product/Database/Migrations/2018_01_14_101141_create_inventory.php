<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('inventory', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('id_product')->unsigned();
        $table->foreign('id_product')->references('id')->on('product')->onDelete('restrict');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        $table->integer('in_out_qty');
        $table->string('measure');
        $table->integer('warehouse')->unsigned();
        $table->foreign('warehouse')->references('id')->on('warehouse')->onDelete('restrict');
        $table->integer('sub_total');
        $table->string('type');
        $table->dateTime('arr_date');
        $table->string('note');
        $table->string('opname')->nullable();
        $table->softDeletes();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::dropIfExists('inventory');
    }
}
