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
        Schema::create('shop_category', function (Blueprint $table) {
            $table->id("id_shop_category");
            $table->text("name");
            $table->text("image");
            $table->longtext("description");
            $table->integer("id_shop_category_parent");
            $table->string("url_shop_category",250);
            $table->integer("order_category");
            $table->integer("active");
                 

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
