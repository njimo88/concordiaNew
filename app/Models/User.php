<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use App\Models\Role;
use App\Models\Basket;
use App\Models\bills;
use App\Models\LiaisonShopArticlesBill;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements CanResetPassword
{
    
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'username',
        'name',
        'lastname',
        'email',
        'password',
        'phone',
        'profession',
        'gender',
        'birthdate',
        'nationality',
        'address',
        'zip',
        'city',
        'country',
        'family_id',
        'image',
        'role',
        'family_level',
        'color',
        'created_at',
        'updated_at',
        'licenceFFGYM',
        'initial_password',
    ];

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role');
    }

    public function paniers()
    {
        return $this->hasMany(Basket::class, 'user_id');
    }
    
    public function belongsToFamily($familyId)
{
    return $this->family_id === intval($familyId);
}

public function bills()
    {
        return $this->hasMany(bills::class, 'user_id');
    }

public function old_bills()
{
    return $this->hasMany(old_bills::class, 'user_id');
}

public function liaisonShopArticlesBill()
{
    return $this->hasMany(LiaisonShopArticlesBill::class, 'id_user');
}


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
