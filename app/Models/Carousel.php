<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;

    // Spécifie la table si elle est différente du nom du modèle (optionnel ici car elle suit la convention)
    protected $table = 'carousel';

    // Définir les champs qui peuvent être remplis massivement (pour la sécurité)
    protected $fillable = [
        'image_link',
        'click_link',
        'active',
        'locked',
        'image_order',
    ];

    // Optionnel : si tu veux gérer la création automatique des champs `created_at` et `updated_at`, tu n'as pas besoin de le spécifier.
    public $timestamps = true;
}