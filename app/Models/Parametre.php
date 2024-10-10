<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parametre';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'saison_index',
        'saison',
        'date_de_rentree',
        'fichier_inscription1',
        'fichier_inscription2',
        'fichier_inscription3',
        'fichier_inscription4',
        'articles_licence1',
        'articles_licence2',
        'articles_licence3',
        'articles_licence4',
        'reduction_famille',
        'pagination',
        'activate',
        'id_article_inscription',
        'is_off',
        'determinesection',
        'totalInscrits',
    ];
}
