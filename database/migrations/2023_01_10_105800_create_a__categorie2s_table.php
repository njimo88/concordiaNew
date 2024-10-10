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
        Schema::create('a__categorie2s', function (Blueprint $table) {
            $table->id("Id_categorie2");
            $table->string("nom_categorie",100);
            $table->longtext("description");
            $table->string("categorie_URL",100);
            $table->string("image",100);
          
           

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
        Schema::dropIfExists('a__categorie2s');
    }
};
