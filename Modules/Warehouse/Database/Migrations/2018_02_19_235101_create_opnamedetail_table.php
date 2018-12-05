<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpnamedetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opnamedetail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_product')->unsigned();
            $table->foreign('id_product')->references('id')->on('product')->onDelete('restrict');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->integer('in_out_qty');
            $table->string('measure');
            $table->integer('warehouse')->unsigned();
            $table->integer('sub_total');
            $table->string('type');
            $table->dateTime('arr_date');
            $table->integer('opname_id')->unsigned();
            $table->foreign('opname_id')->references('id')->on('stockopname')->onDelete('cascade');
            $table->string('note');
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
        Schema::dropIfExists('opnamedetail');
    }
}
