<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class A_Blog_Post extends Model
{
    use HasFactory;

    
    protected $table = 'blog_posts';

    //protected $primarykey = '';
    protected $primaryKey = 'id_blog_post_primaire';

    protected $fillable = [
        'id_blog_post_primaire',
        'date_post', 
        'titre',
        'contenu', 
        'categorie1',
        'categorie2',
        'status',
        'post_url Index',
        'id_user',
        'id_last_editor',
        'highlighted',
        'highlight_img',
        'private'

    ];
}
