<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shop_article_1 extends Model
{
    use HasFactory;

    protected $table = 'shop_article_1';
    protected $primaryKey = 'id_shop_article';
    public $incrementing = false;
    

    protected $fillable = [  
            'stock_ini',
            'stock_actuel',
            'teacher',
            'lesson',
            'created_at',
            'updated_at'
    ];

    public static function hasMultipleTeachers($id_article) {
        $article = self::find($id_article);
    
        if(!$article) {
            throw new \Exception('Article not found');
        }
    
        $teachers = json_decode($article->teacher, true);
    
        return count($teachers) > 1;
    }

    public static function isUserTeacher($user_id) {
        $articles = self::all();
        foreach ($articles as $article) {
            $teachers = json_decode($article->teacher, true);
    
            if(in_array($user_id, $teachers)) {
                return true;
            }
        }
        return false;
    }
    
    
    







}
