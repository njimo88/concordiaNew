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

        Schema::create('shop_reduction', function (Blueprint $table) {
            $table->text("code");
            $table->float("percentage");
            $table->float("value");
            $table->longtext("description");
            $table->id("id_shop_reduction");
            $table->integer("max_per_user");
            $table->tinyInteger("active");
            $table->date("startvalidity");
            $table->date("endvalidity");
            $table->tinyInteger("automatic");
            $table->text("image");
            $table->json("limited_user");
            $table->json("limited_shop_article");
            
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
