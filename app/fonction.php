<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\shop_article;



//fonction pour afficher la famille en fonction de l'id de la famille
function getUsersByFamilyId($family_id)
{
    $users = User::where('family_id', $family_id)->get();
    return $users;
}

//faire sortir la saison active en fonction de la table parametre
function saison_active()
{
    $saison = DB::table('parametre')
    ->where('activate', '=', 1)
    ->value('saison');
    return $saison;
}

//filtrer les articles en fonction de leur date de validité
function filterArticlesByValidityDate($articles) {
    $now = date('Y-m-d');

    $filteredArticles = collect($articles)->filter(function($article) use($now) {
        return ($article->startvalidity <= $now && $article->endvalidity >= $now);
    })->values();

    return $filteredArticles;
}

//filtrer les articles en fonction de selected_limit,Age & sexe
function getFilteredArticles($articles) {
    if (Auth::check()) {
        $user = Auth::user();
        $family = getUsersByFamilyId($user->family_id);
        $familyGender = $family->pluck('gender')->unique();
        $articleIds = collect($articles)->pluck('id_shop_article');
    
        $selectedArticles = DB::table('selected_limit')
                                ->whereIn('id_shop_article', $articleIds)
                                ->whereIn('user_id', $family->pluck('user_id'))
                                ->pluck('id_shop_article')
                                ->toArray();
    
        $filteredArticles = collect($articles)->filter(function($article) use ($selectedArticles, $user, $familyGender, $family) {
            if ($article->sex_limit && !$familyGender->contains($article->sex_limit)) {
                return false;
            }
    
            if ($article->selected_limit == 0) {
                return hasFamilyMemberWithAgeInRange($family, $article->agemin, $article->agemax) || in_array($article->id_shop_article, $selectedArticles);
                
            } else {
                return in_array($article->id_shop_article, $selectedArticles);
            }
        })->values();
    
    } else {
        $filteredArticles = collect($articles)->filter(function($article) {
            return ($article->selected_limit == 0 );
        })->values();
    }
    
    return $filteredArticles;
}

//filtrer les articles en fonction de l'age de la famille
function hasFamilyMemberWithAgeInRange($familyMembers, $agemin, $agemax) {
    foreach ($familyMembers as $member) {
        $age = Carbon::parse($member->birthdate)->age;
        if ($age >= $agemin && $age <= $agemax) {
            return true;
        }
    }
    return false;
}

//sortir les membres de la famille qui ont l'age requis
function getFamilyMembersMeetingAgeCriteria($family, $agemin, $agemax) {
    $members = collect($family)->filter(function($member) use ($agemin, $agemax) {
        $age = Carbon::parse($member->birthdate)->age;
        return ($age >= $agemin && $age <= $agemax);
    })->values();
    
    return $members;
}


// sortir les users qui peuvent acheter  l'article
function getArticleUsers($article) {
    $selectedArticles = [];
    $selectedUsers = [];

        $user = Auth::user();
        $family = getUsersByFamilyId($user->family_id);

        $selectedArticles = DB::table('selected_limit')
            ->where('id_shop_article', $article->id_shop_article)
            ->whereIn('user_id', $family->pluck('user_id'))
            ->pluck('id_shop_article')
            ->toArray();

        $selectedUsers = DB::table('selected_limit')
            ->where('id_shop_article', $article->id_shop_article)
            ->whereIn('user_id', $family->pluck('user_id'))
            ->pluck('user_id')
            ->toArray();

    $filteredUsers = collect($family)->filter(function($user) use ($selectedUsers, $article) {
        return in_array($user->user_id, $selectedUsers) && ($article->sex_limit == null || $user->gender == $article->sex_limit);
    })->values();

    $familyMembersMeetingAgeCriteria = getFamilyMembersMeetingAgeCriteria($family, $article->agemin, $article->agemax);
    $familyMembersMeetingAgeCriteria = $familyMembersMeetingAgeCriteria->filter(function($user) use ($article) {
        return ($article->sex_limit == null || $user->gender == $article->sex_limit);
    })->values();

    $filteredUsers = $filteredUsers->merge($familyMembersMeetingAgeCriteria);

    return $filteredUsers;


}





