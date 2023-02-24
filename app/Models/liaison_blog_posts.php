<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class liaison_blog_posts extends Model
{
    use HasFactory;

    protected $table = 'liaison_blog_posts_blog_categories';
    protected $primarykey = 'id_blog_post';

    protected $fillable = [
        'id_blog_post',
        'id_blog_category'

    ];
}
