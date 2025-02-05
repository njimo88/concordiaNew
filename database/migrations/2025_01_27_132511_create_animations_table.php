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
        Schema::create('animations', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('animation_starttime');  // Changement de nom pour animation_starttime
            $table->time('duration');    // Ajout de la durée
            $table->integer("saison");                // ID Saison lié à la saison de "parametres"
            $table->integer("max_participants");      // Nombre d'inscriptions maximum
            $table->boolean('visibilite')->default(0); // Visibilité par défaut à 0
            $table->double('price');                  // Prix de l'inscription en € unité
            $table->timestamps();                     // Crée automatiquement created_at et updated_at
            $table->text('image_path')->nullable();  // Ajout de l'image (path), colonne nullable
            $table->foreignId('room_id')->constrained('rooms')->onDelete('set null'); // Place liée
            $table->foreignId('created_by')->constrained('users')->onDelete('set null'); // Créé par (utilisateur)
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // Dernière modif par
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animations');
    }
};
