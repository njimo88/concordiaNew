<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system'; 

    protected $primaryKey = 'id_system';

    protected $fillable = [
        'name',
        'value',
        'Message',
        'percentage',
    ];

    public $timestamps = false;

    public static function getValue($id){
        $setting = self::find($id);
        return $setting ? $setting->value : null;
    }

    
}
