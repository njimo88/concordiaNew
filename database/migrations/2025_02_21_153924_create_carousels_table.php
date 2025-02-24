<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarouselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carousels', function (Blueprint $table) {
            $table->id(); // Crée un champ id auto-incrémenté
            $table->string('image_link'); // Lien de l'image
            $table->string('click_link')->nullable(); // Lien de redirection (nullable)
            $table->boolean('active')->default(1); // Statut actif ou non
            $table->int('order')->default(0); // Position de l'image dans le carousel
            $table->timestamps(); // Created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carousels');
    }
}