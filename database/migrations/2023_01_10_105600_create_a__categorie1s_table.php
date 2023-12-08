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
        Schema::create('a__categorie1s', function (Blueprint $table) {
            $table->id("Id_categorie1");
            $table->text("nom_categorie");
            $table->text("image");
            $table->string("categorie_URL",255);
            $table->longtext("description");
            $table->text("visibilite");

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
        Schema::dropIfExists('a__categorie1s');
    }
};
