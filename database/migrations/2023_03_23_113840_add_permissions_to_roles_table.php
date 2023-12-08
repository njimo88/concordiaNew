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
    Schema::table('roles', function (Blueprint $table) {
        $table->boolean('supprimer_edit_ajout_user')->default(1);
        $table->boolean('reinitialiser_mot_de_passe_user')->default(1);
        $table->boolean('supprimer_facture')->default(1);
        $table->boolean('changer_status_facture')->default(1);
        $table->boolean('paiement_immediat')->default(1);
        $table->boolean('changer_designation_facture')->default(1);
        $table->boolean('supprimer_edit_ajout_categorie')->default(1);
        $table->boolean('supprimer_edit_dupliquer_ajout_article')->default(1);
        $table->boolean('edit_ajout_professionnel')->default(1);
        $table->boolean('declarer_heure_professionnel')->default(1);
        $table->boolean('voirancien_declaration')->default(1);
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('roles', function (Blueprint $table) {
        $table->dropColumn([
            'supprimer_edit_ajout_user',
            'reinitialiser_mot_de_passe_user',
            'supprimer_facture',
            'changer_status_facture',
            'paiement_immediat',
            'changer_designation_facture',
            'supprimer_edit_ajout_categorie',
            'supprimer_edit_dupliquer_ajout_article',
            'edit_ajout_professionnel',
            'declarer_heure_professionnel',
            'voirancien_declaration',
        ]);
    });
}

};
