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
use App\Models\Shop_article;
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
        'color',
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

    public function medicalCertificate()
    {
        return $this->hasOne(MedicalCertificates::class, 'user_id');
    }

    public function certifications()
    {
        return $this->hasMany(UsersLevels::class, 'user_id');
    }

    public function warning()
    {
        return $this->hasOne(Warning::class, 'family_id', 'family_id');
    }


    public function adhesions()
    {
        return $this->hasManyThrough(
            Shop_article::class,
            LiaisonShopArticlesBill::class,
            'id_user',
            'id_shop_article',
            'user_id',
            'id_shop_article'
        )
            ->leftJoin('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
            ->leftJoin('old_bills', 'liaison_shop_articles_bills.bill_id', '=', 'old_bills.id')
            ->whereIn('shop_article.type_article', ['0', '1'])
            ->select('shop_article.*', 'bills.status as bill_status', 'old_bills.status as old_bill_status');
    }

    public function familyParents()
    {
        return $this->hasMany(User::class, 'family_id', 'family_id')
            ->where('family_level', '=', 'parent');
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
