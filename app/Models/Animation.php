<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animation extends Model
{
    use HasFactory;

    // Indique la table associée au modèle
    protected $table = 'animations';

    // Indique les attributs qui peuvent être affectés en masse (mass assignable)
    protected $fillable = [
        'title',
        'description',
        'teacher_id',
        'animation_starttime',
        'duration',
        'saison',
        'max_participants',
        'visibilite',
        'price',
        'image_path',
        'room_id',
        'category_id',
        'created_by',
        'updated_by',
    ];

    // Définit les relations avec d'autres modèles

    /**
     * Relation avec le modèle User (enseignant)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relation avec le modèle AnimationPlace (place de l'animation)
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function category()
    {
        return $this->belongsTo(AnimationsCategories::class, 'category_id');
    }

    public function participants()
    {
        return $this->hasMany(AnimationsRegistrations::class, 'animation_id');
    }

    /**
     * Relation avec le modèle User (créé par)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec le modèle User (mis à jour par)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
