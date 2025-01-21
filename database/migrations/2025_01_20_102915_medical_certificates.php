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
        Schema::create('medical_certificates', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque certificat
            $table->unsignedBigInteger('user_id'); // ID de l'utilisateur (clé étrangère)
            $table->date('expiration_date')->default(date('Y-m-d')); // Date d'expiration, par défaut aujourd'hui
            $table->string('file_path'); // Chemin du fichier (image)
            $table->boolean('validated')->default(1); // Validation par défaut à 1

            // Définir la clé étrangère
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_certificates');
    }
};