<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'isAllowedToPublishPost',
        'isAllowedToAddPost',
        'isAllowedToDeleteOtherPost',
        'isAllowedToEditOtherPost',
        'isAllowedToAddBlogCategory',
        'isAllowedToUpdateCategory',
        'isAllowedToDeleteCategory',
        'isAllowedToPublishArticle',
        'isAllowedToDeleteArticle',
        'isAllowedToManageShopCategory',
        'isAllowedToCreateRights',
        'isAllowedToUpdateRights',
        'isAllowedToDeleteUser',
        'isAllowedToEditUser',
        'isAllowedToValidatePost',
        'isAllowedToAddComment',
        'isAllowedToEditOtherComment',
        'isAllowedToDeleteOtherComment',
        'isAllowedToValidateComment',
        'isAllowedToManageBills',
        'isAllowedToEditSlider',
        'isAllowedToEditSystem',
        'isAllowedToManageGroups',
        'isAllowedToManageFamily',
        'isAllowToManageScheduler',
        'isAllowedToChangeArticle',
        'isAllowedToViewBills',
        'isAllowedToManageUserGroups',
        'isAllowedToViewUser',
        'isAllowedToViewPastArticle',
        'isPorteOuverte',
        'isClickAsso'
    ];

    public function users()
    {
        return $this->hasMany(User::class,'role');
    }
    
 

   
}

