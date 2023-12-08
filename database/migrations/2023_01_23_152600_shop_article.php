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

        Schema::create('shop_article', function (Blueprint $table) {
            $table->id("id_shop_article");
            $table->text("title");
            $table->double("price");
            $table->longtext("description");
            $table->text("image");
            $table->integer("stock_actuel");
            $table->string("url_shop_article",250);           
            $table->string("ref",100);

            $table->integer('type_article');
            $table->integer("max_per_user");
            $table->boolean("need_member");
            $table->date("startvalidity");
            $table->date("endvalidity");
            $table->tinyInteger("alert_stock");
            $table->tinyInteger("afiscale");
            $table->float("agemin");
            $table->float("agemax");
            $table->string("sex_limit",4);
            $table->boolean("selected_limit");
            $table->float("price_indicative");

            $table->text("short_description");
            $table->float("totalprice");
            $table->integer("saison");
            $table->integer("nouveaute");
            $table->json("buyers");




            
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
