<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'estAutoriserDeVoirMembres',
        'estAutoriserDeVoirClickAsso',
        'estAutoriserDeVoirArticles',
        'estAutoriserDeRedigerArticle',
        'estautoriserDeSupprimerBlogArticle',
        'estAutoriserDeVoirCategories',
        'estAutoriserDeVoirFacture',
        'estAutoriserDeVoirReduction',
        'estAutoriserDeVoirArticleBoutique',
        'estAutoriserDeVoirCategorieBoutique',
        'estAutoriserDeVoirMessages',
        'estAutoriserDeVoirHistorique',
        'estAutoriserDeVoirCours',
        'estAutoriserDeVoirAnimations',
        'estAutoriserDeVoirStatsExports',
        'estAutoriserDeVoirValiderCertificats',
        'estAutoriserDeVoirGestionProfessionnels',
        'estAutoriserDeVoirCalculDesSalaires',
        'estAutoriserDeVoirValiderLesHeures',
        'estAutoriserDeVoirGestionDesDroits',
        'estAutoriserDeVoirParametresGeneraux',
        'estAutoriserDeVoirSalles',
        'estAutoriserDeVoirMessageGeneral',
        'supprimer_edit_ajout_user',
        'reinitialiser_mot_de_passe_user',
        'supprimer_edit_facture',
        'changer_status_facture',
        'paiement_immediat',
        'changer_designation_facture',
        'supprimer_edit_ajout_categorie',
        'supprimer_edit_dupliquer_ajout_article',
        'edit_ajout_professionnel',
        'declarer_heure_professionnel',
        'voir_declaration_professionnel',
        'estAutoriserDeVoirFichiers',
        'estAutoriserDeVoirMessageMaintenance',
        'estAutoriserDeVoirModeStrict',
        'estAutoriserDeVoirParametrage',
        'estAutoriserDeVoirProduitsClub',
        'estAutoriserDeVoirAdhesionsClub',
        'estAutoriserDeVoirDiplomesClub'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role');
    }
}
