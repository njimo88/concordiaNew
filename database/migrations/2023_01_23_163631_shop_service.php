<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('shop_service', function (Blueprint $table) {

            $table->id("id_shop_service");
            $table->json("stock_ini");
            $table->json("stock_actuel");
            $table->json("teacher");
            $table->json("lesson");



        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
