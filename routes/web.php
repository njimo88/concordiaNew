<?php

use App\Http\Controllers\A_ControllerBlog;
use App\Http\Controllers\Controller_club;
use App\Http\Controllers\A_Controller_categorie;
use App\Http\Controllers\Controller_mention_legales;
use App\Http\Controllers\A_ControllerAdmin;
use App\Http\Controllers\BlogArticle_Controller;
use App\Models\A_Blog_Post;
use App\Http\Controllers;
use App\Http\Controllers\Article_Controller;
use App\Http\Controllers\generatePDF;
use App\Http\Controllers\Controller_Quizz;
use App\Http\Controllers\Controller_Communication;
use App\Http\Controllers\Prendre_Contact_Controller;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\n_AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BillsController;
use App\Models\old_bills;
use App\Http\Controllers\ProfessionnelsController;
use App\Mail\UserEmail;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*---------------------------------Njimo------------------------------------------*/
Route::get('/test', function () {
    return view('DEV2023')->with('user', Auth::user());

});



Auth::routes();

/*Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/
Route::middleware(['auth'])->group(function () {
    Route::get('/users/family', [App\Http\Controllers\UsersController::class, 'family'])->name('users.family');
    Route::post('/users/family/addMember', [App\Http\Controllers\UsersController::class, 'addMember'])->name('users.addMember');
    Route::post('/users/family/addEnfant', [App\Http\Controllers\UsersController::class, 'addEnfant'])->name('users.addEnfant');
    Route::put('/users/family/editFamille/{user_id}', [App\Http\Controllers\UsersController::class, 'editFamille'])->name('users.editFamille');
    Route::get('/users/family/detailsUser/{user_id}', [App\Http\Controllers\UsersController::class, 'detailsUser']);
    Route::get('/modif', [App\Http\Controllers\UsersController::class, 'editdata']);

    Route::get('/users/profils', [App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit-profil');
Route::put('/users/profils', [App\Http\Controllers\UsersController::class, 'update'])->name('users.update-profil');

Route::get('/users/factures-devis', [App\Http\Controllers\UsersController::class, 'facture'])->name('users.FactureUser');
Route::get('/users/factures-devis/{id}', [App\Http\Controllers\UsersController::class, 'deleteFacture'])->name('users.deleteFacture');
Route::get('/users/factures-devis/showBill/{id}', [BillsController::class, 'showBill'])->name('user.showBill');


/*-----------PDF--------------------------*/

Route::post('/generatePDFfacture/{id}', [generatePDF::class, 'generatePDFfacture'])->name('generatePDFfacture');

});








    /*-----------Panier----------*/
    Route::middleware(['auth'])->group(function () {
    Route::get('/panier', [App\Http\Controllers\UsersController::class, 'panier'])->name('panier');
    Route::get('/payer_article', [App\Http\Controllers\UsersController::class, 'payer_article'])->name('payer_article');
    Route::get('/Vider_panier/{id}', [App\Http\Controllers\UsersController::class, 'Vider_panier'])->name('Vider_panier');
    
    /*-----------Paiement----------*/
    Route::get('/detail_paiement/{id}/{nombre_cheques}', [App\Http\Controllers\UsersController::class, 'detail_paiement'])->name('detail_paiement');

});





/*-----------Admin----------*/

Route::middleware(['auth', 'role:20'])->group(function () {
    Route::get('/admin', [n_AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/members', [n_AdminController::class, 'members'])->name('utilisateurs.members');
    Route::get('/admin/members/getDataForDataTable', [n_AdminController::class, 'getDataForDataTable']);
    Route::post('/admin/members/addUser', [n_AdminController::class, 'addUser'])->name('admin.addUser');
    Route::put('/admin/members/{user_id}', [n_AdminController::class, 'editUser'])->name('admin.editUser');
    Route::get('/admin/members/editUser/{user_id}', [n_AdminController::class, 'editUsermodal']);
    Route::get('/admin/members/delete/{user_id}', [n_AdminController::class, 'DeleteUser'])->name('admin.DeleteUser');
    Route::get('/admin/members/deletemodal/{user_id}', [n_AdminController::class, 'DeleteUsermodal']);

     /*----------------------- Factures ------------------------------ */
    Route::get('/admin/paiement/facture', [BillsController::class, 'index'])->name('paiement.facture');
    Route::get('/admin/paiement/facture/{id}', [BillsController::class, 'delete'])->name('paiement.deleteFacture');
    Route::get('/admin/paiement/factureFamille/{id}',  [BillsController::class, 'family'])->name('factureFamille');
    Route::get('/admin/paiement/facture/get-old-bills/{user_id}',  [BillsController::class, 'getOldBills']);
    Route::get('/admin/paiement/facture/showBill/{id}',  [BillsController::class, 'showBill'])->name('facture.showBill');
    Route::get('/paiement_immediat/{bill_id}', [BillsController::class, 'paiement_immediat'])->name('paiement_immediat');
    Route::post('/admin/paiement/facture/addShopMessage/{id}',  [BillsController::class, 'addShopMessage'])->name('addShopMessage');
    Route::put('/admin/paiement/facture/updateStatus/{id}',  [BillsController::class, 'updateStatus'])->name('facture.updateStatus');
    Route::put('/admin/paiement/facture/updateDes/{id}',  [BillsController::class, 'updateDes'])->name('facture.updateDes');

    /*----------------------- Professionnels ------------------------------ */
    Route::get('/admin/Professionnels/gestion',  [ProfessionnelsController::class, 'gestion'])->name('Professionnels.gestion');
    Route::put('/admin/Professionnels/gestion/{user_id}',  [ProfessionnelsController::class, 'editPro'])->name('Professionnels.editPro');
    Route::put('/admin/Professionnels/gestion/addPro/{user_id}',  [ProfessionnelsController::class, 'addPro'])->name('Professionnels.addPro');
    Route::get('/admin/Professionnels/gestion/declarationList/{id_user}',  [ProfessionnelsController::class, 'declarationList']);
    Route::get('/admin/Professionnels/gestion/declarationHeures/{id}',  [ProfessionnelsController::class, 'declarationHeures'])->name('Professionnels.declarationHeures');
    Route::post('/admin/Professionnels/gestion/declaration/{id}',  [ProfessionnelsController::class, 'declaration'])->name('Professionnels.declaration');
    Route::get('/admin/Professionnels/gestion/calculSalary',  [ProfessionnelsController::class, 'calculSalary'])->name('proffesional.calculSalary');
    Route::post('/admin/Professionnels/gestion/modifySM',  [ProfessionnelsController::class, 'modifySM'])->name('proffesional.modifySM');
    Route::post('/admin/Professionnels/gestion/simuleSalary',  [ProfessionnelsController::class, 'simuleSalary'])->name('proffesional.simuleSalary');
    Route::get('/valider-heures', [ProfessionnelsController::class, 'valideHeure'])->name('proffesional.valideHeure');
    /*----------------------- END Professionnels ------------------------------ */
    Route::put('/admin/members/mdpUniversel/{user_id}', [n_AdminController::class, 'mdpUniversel'])->name('admin.mdpUniversel');
    Route::get('/admin/members/mdpUniverselmodal/{user_id}', [n_AdminController::class, 'mdpUniverselmodal']);
    Route::get('/admin/members/familleMembers/{user_id}', [n_AdminController::class, 'familleMembers']);
    /*----------------------- Systeme ------------------------------ */
    Route::post('/admin/update-system-setting', [n_AdminController::class, 'message_general'])->name('update_system_setting');

}); 


    


/*---------------------------------ABBé------------------------------------------*/
Route::get('/', [A_ControllerBlog::class, 'a_fetchPost'])->name('A_blog');
Route::get('/home', [A_ControllerBlog::class, 'a_fetchPost']);
Route::get('/anniversaire', [A_ControllerBlog::class, 'anniversaire'])->name('anniversaire');
Route::get('/Simple_Post/{id}', [A_ControllerBlog::class, 'Simple_Post'])->name('Simple_Post');
Route::get('/Affichage_categorie1/{id}', [A_ControllerBlog::class, 'recherche_par_cat1'])->name('A_blog_par_categorie1');
Route::get('/Affichage_categorie2/{id}', [A_ControllerBlog::class, 'recherche_par_cat2'])->name('A_blog_par_categorie2');
Route::get('/questionnaire', [A_ControllerBlog::class, 'questionnaire'])->name('questionnaire');
Route::get('/determinesection/count', [A_ControllerBlog::class, 'countdeterminesection'])->name('countdeterminesection');



/*---------------------------------Shop en backoffice------------------------------------------*/
Route::middleware(['auth'])->group(function () {



Route::get('/Categorie_back', [A_Controller_categorie::class, 'index'])->name('A_Categorie');
Route::post('/Categorie/save', [A_Controller_categorie::class, 'saveNestedCategories'])->name('save-categories');

Route::post('category-subcategory/create', [A_Controller_categorie::class, 'create'])->name('create-categories');

Route::post('category-subcategory/save', [A_Controller_categorie::class, 'store'])->name('category-subcategory.store');
Route::get('category-subcategory/edit/{id_shop_category}', [A_Controller_categorie::class, 'edit_index'])->name('category-edit');
Route::post('category-subcategory/edit/{id_shop_category}', [A_Controller_categorie::class, 'edit'])->name('edit');


Route::get('category-subcategory/remove/{id_shop_category}', [A_Controller_categorie::class, 'remove'])->name('category-remove');


}); 

/*---------------------------------Shop en front office------------------------------------------*/

Route::get('/Categorie_front', [A_Controller_categorie::class, 'MainShop'])->name('index_categorie');
Route::get('/SubCategorie_front/{id}', [A_Controller_categorie::class, 'Shop_souscategorie'])->name('sous_categorie');
Route::get('/details_article/{id}', [A_Controller_categorie::class, 'Handle_details'])->name('details_article');
Route::put('/commander_article/{id}', [A_Controller_categorie::class, 'commander_article'])->name('commander_article');
Route::put('/Passer_au_paiement/{id}', [A_Controller_categorie::class, 'Passer_au_paiement'])->name('Passer_au_paiement');
Route::get('/commanderModal/{shop_id}/{user_id}', [A_Controller_categorie::class, 'commanderModal']);

//Route::get('/test', [A_Controller_categorie::class, 'JsonProcess2']);


/*------------------------------ Article Back office ----------------------------------------- */

Route::middleware(['auth'])->group(function () {


Route::get('/Article', [Article_Controller::class, 'index'])->name('index_article');
Route::post('/Article/include-page', [Article_Controller::class, 'index_include'])->name('include-tab_articles');

Route::post('/Article/edit/{id}', [Article_Controller::class, 'edit'])->name('edit_article');
Route::get('/Article/edit/{id}', [Article_Controller::class, 'edit_index'])->name('edit_article_index');


Route::post('images/upload', 'ImageController@upload')->name('ckeditor.upload');



// Dupliquer un article
Route::post('/Article/duplicate/{id}', [Article_Controller::class, 'duplicate'])->name('duplicate_article');
Route::get('/Article/duplicate/{id}', [Article_Controller::class, 'duplicate_index'])->name('duplicate_article_index');



Route::get('/Article/delete/{id}', [Article_Controller::class, 'delete'])->name('delete_article');


Route::post('/Article/create/member', [Article_Controller::class, 'inserer_article_member'])->name('create_article_member');
Route::post('/Article/create/produit', [Article_Controller::class, 'inserer_article_produit'])->name('create_article_produit');
Route::post('/Article/create/lesson', [Article_Controller::class, 'inserer_article_lesson'])->name('create_article_lesson');

Route::get('/Article/create/member', [Article_Controller::class, 'index_create_member'])->name('index_create_article_member');
Route::get('/Article/create/produit', [Article_Controller::class, 'index_create_produit'])->name('index_create_article_produit');
Route::get('/Article/create/lesson', [Article_Controller::class, 'index_create_lesson'])->name('index_create_article_lesson');






// route pour afficher le formulaire de facon dynamique sur la date des cours , declencher avec le boutons ajouter une seance
Route::get('/Article/createp', [Article_Controller::class, 'test_create'])->name('test_create_article');



}); 


/*------------------------------ BLOG BACK OFFICE ----------------------------------------- */

Route::middleware(['auth'])->group(function () {

Route::get('/BlogArticle_index', [BlogArticle_Controller::class, 'index'])->name('index');

//delete blog
Route::get('/BlogArticle_blog/delete/{id}', [BlogArticle_Controller::class, 'delete_blog'])->name('delete_blog');


// -------------------------------- partie creation de blog  -------------------------------- 

Route::get('/BlogArticle_redaction', [BlogArticle_Controller::class, 'index_article_redaction'])->name('index_article_redaction');
Route::Post('/BlogArticle_redaction', [BlogArticle_Controller::class, 'creation_article_blog'])->name('creation_article_blog');

// -------------------------------- partie edition de blog  -------------------------------- 

Route::get('/BlogArticle_blog/edit/{id}', [BlogArticle_Controller::class, 'edit_blog_index'])->name('edit_blog_index');
Route::Post('/BlogArticle_blog/edit/{id}', [BlogArticle_Controller::class, 'edit_blog'])->name('edit_blog');


// -------------------------------- partie categorie -------------------------------- 
Route::get('/BlogArticle_creation_cate/{type_cate}', [BlogArticle_Controller::class, 'index_creation_cate'])->name('index_creation_cate');
Route::post('/BlogArticle_creation_cate/{cate}', [BlogArticle_Controller::class, 'create_cate'])->name('create_cate');
Route::get('/BlogArticle_category', [BlogArticle_Controller::class, 'index_article_category'])->name('index_article_category');

//delete categories
Route::get('/BlogArticle_category/delete/{id}', [BlogArticle_Controller::class, 'delete'])->name('delete_cate');

//edit categories
Route::get('/BlogArticle_category/edit/{id}', [BlogArticle_Controller::class, 'edit_index'])->name('edit_index');
Route::post('/BlogArticle_category/edit/{id}',[BlogArticle_Controller::class, 'edit_cate'])->name('edit_cate');

}); 

/*----------------------------- Mention legales -------------------------------------------------- */
Route::get('/Mentions', [Controller_mention_legales::class, 'index'])->name('index_mentions_legales');
Route::get('/Politique_de_confidentialite', [Controller_mention_legales::class, 'index_politique'])->name('index_politique');




Route::middleware(['auth'])->group(function () {

/*------------------------------ Communication ----------------------------------------- */
Route::get('/Communication', [Controller_Communication::class, 'index'])->name('index_communication');

//Route::get('/Communication', [A_ControllerBlog::class, 'a_requetes2'])->name('index_communication');

Route::get('/Communication/get_info/{article_id}', [Controller_Communication::class, 'get_info'])->name('get_communication');

Route::post('/Communication/traitement', [Controller_Communication::class, 'traitement'])->name('traitement');

Route::post('/Communication/email_sender',[Controller_Communication::class,'email_sender'])->name('email_sender');

Route::get('/Commnication/email_page',[Controller_Communication::class,'email_page'])->name('email_page') ;

Route::post('/display_saison',[Controller_Communication::class,'display_by_saison'])->name('display_by_saison') ;



}); 




Route::middleware(['auth'])->group(function () {

/*----------------------- Club - cours ------------------------------ */
Route::get('/club/cours_index', [Controller_club::class, 'index_cours'])->name('index_cours');

Route::post('/get_data_table/{article_id}', [Controller_club::class, 'get_data_table'])->name('get_data_table');



Route::post('/club/include-page', [Controller_club::class, 'index_include'])->name('include-page');

//Route::get('/shop_article_cours_ajax/{id}', [Controller_club::class, 'display_form_cours'])->name('form_cours');

Route::get('/form_appel/{id}', [Controller_club::class, 'form_appel_method'])->name('form_appel');

Route::get('/club/display_modal/{id}', [Controller_club::class, 'display_info_user'])->name('info_appel');

Route::post('/modal_post/{id}', [Controller_club::class, 'modif_user'])->name('modif_user');

Route::post('/form_appel/{id}', [Controller_club::class, 'enregister_appel_method'])->name('enregistrer_appel');

Route::get('/historique_appel/{id}', [Controller_club::class, 'display_historique_method'])->name('historique_appel');

#-------------------------------pdf generate-------------------
Route::get('/generate-pdf/{id}',[PDF_Controller::class, 'generate'])->name('generate-pdf');

#-------------------------------route pour gerer l'envoi de mail  generate-------------------
Route::post('/prendre_contact',[Prendre_Contact_Controller::class, 'traitement_prendre_contact'])->name('traitement_prendre_contact');

}); 



#------------------------------- QUIZZ TEST DE PERSONNALITE --------------------------------------------------------

Route::get('/test_de_personnalite',[Controller_Quizz::class, 'index_quizz'])->name('index_quizz');
Route::post('/questionnaire', [Controller_Quizz::class, 'handle_questionnaire'])->name('include_slider_questionnaire');

Route::post('/questionnaire_baby', [Controller_Quizz::class, 'handle_questionnaire_baby'])->name('baby_formulaire');



