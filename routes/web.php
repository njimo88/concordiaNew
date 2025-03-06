<?php

use App\Http\Controllers\AnimationsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\A_ControllerBlog;
use App\Http\Controllers\Controller_club;
use App\Http\Controllers\A_Controller_categorie;
use App\Http\Controllers\Controller_mention_legales;
use App\Http\Controllers\A_ControllerAdmin;
use App\Http\Controllers\BlogArticle_Controller;
use App\Models\A_Blog_Post;
use App\Http\Controllers\Member_History_Controller;
use App\Http\Controllers;
use App\Http\Controllers\Article_Controller;
use App\Http\Controllers\Controller_Stat;
use App\Http\Controllers\generatePDF;
use App\Http\Controllers\Controller_Quizz;
use App\Http\Controllers\Controller_Communication;
use App\Http\Controllers\Prendre_Contact_Controller;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\A_Controller;
use App\Http\Controllers\AnimationsPlacesController;
use App\Http\Controllers\Auth\ForgotUsernameController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ClickAssoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\n_AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\TraitementSupp;
use App\Http\Controllers\LogistiqueController;
use App\Models\old_bills;
use App\Http\Controllers\ProfessionnelsController;
use App\Mail\UserEmail;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArticlePostController;
use App\Http\Controllers\ServerBrowserController;
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


/*--------------------------------------Njimo------------------------------------------*/

Route::get('/test', function () {
    return view('DEV2023')->with('user', Auth::user());
});


Route::get('/passwordd', [App\Http\Controllers\UsersController::class, 'passwordd']);

Auth::routes();

/*Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/
Route::middleware(['auth'])->group(function () {
    /* Logistique */
    Route::get('/logistique/preparation', [LogistiqueController::class, 'preparation'])->name('logistique.preparation');
    Route::get('/logistique/distribution', [LogistiqueController::class, 'distribution'])->name('logistique.distribution');
    Route::post('/confirm-preparation', [LogistiqueController::class, 'confirmPreparation']);
    Route::post('/non-concerne', [LogistiqueController::class, 'nonConcerne']);
    Route::post('/confirm-distribution', [LogistiqueController::class, 'confirmDistribution']);
    Route::get('/get-user-liaisons', [LogistiqueController::class, 'getUserLiaisons']);
    Route::post('/return-product', [LogistiqueController::class, 'returnProduct']);

    Route::get('/users/family', [TraitementSupp::class, 'mafamille'])->name('users.family');
    Route::post('/users/family/addMember', [App\Http\Controllers\UsersController::class, 'addMember'])->name('users.addMember');
    Route::post('/users/family/addEnfant', [App\Http\Controllers\UsersController::class, 'addEnfant'])->name('users.addEnfant');
    Route::put('/users/family/editFamille/{user_id}', [App\Http\Controllers\UsersController::class, 'editFamille'])->name('users.editFamille');
    Route::get('/users/family/detailsUser/{user_id}', [App\Http\Controllers\UsersController::class, 'detailsUser']);
    Route::post('/upload/{user}', [UsersController::class, 'uploadImage']);
    Route::post('/uploadProfileImage', [UsersController::class, 'uploadProfileImage'])->name('uploadProfileImage');


    Route::get('/modif', [App\Http\Controllers\UsersController::class, 'editdata']);

    Route::get('/users/profils', [App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit-profil');
    Route::put('/users/profils', [App\Http\Controllers\UsersController::class, 'update'])->name('users.update-profil');

    Route::get('/users/factures-devis', [TraitementSupp::class, 'mesfactures'])->name('users.FactureUser');
    Route::get('/users/factures-devis/{id}', [App\Http\Controllers\UsersController::class, 'deleteFacture'])->name('users.deleteFacture');
    Route::get('/users/factures-devis/showBill/{id}', [TraitementSupp::class, 'mafacture'])->name('user.showBill');
    Route::post('/additional-charge/store', [TraitementSupp::class, 'storeAdditionalCharge'])->name('additional-charge.store');
    Route::get('/search', [BillsController::class, 'search']);
    Route::get('/family-members/{family_id}', [BillsController::class, 'familySearch']);
    Route::post('/create-bill', [BillsController::class, 'createBill'])->name('create-bill');
    Route::get('/products/current-season', [BillsController::class, 'currentSeason']);
    Route::post('/save-selection', [BillsController::class, 'saveSelection']);


    /*--------------------------PDF--------------------------*/
    Route::post('/generatePDFfacture/{id}', [generatePDF::class, 'generatePDFfacture'])->name('generatePDFfacture');
    Route::post('/generatePDFreduction_Fiscale/{id}', [generatePDF::class, 'generatePDFreduction_Fiscale'])->name('generatePDFreduction_Fiscale');
});
/*-----------Panier----------*/
Route::middleware(['auth'])->group(function () {
    Route::get('/panier', [TraitementSupp::class, 'basket'])->name('panier');
    Route::get('/payer_article', [App\Http\Controllers\UsersController::class, 'payer_article'])->name('payer_article');
    Route::get('/Vider_panier/{id}', [App\Http\Controllers\UsersController::class, 'Vider_panier'])->name('Vider_panier');
    Route::get('/payment_form/{nombre_virment}/{total}', [App\Http\Controllers\UsersController::class, 'showForm'])->name('payment_form');
    Route::get('/payment_formFrais/{nombre_virment}/{total}/{bill_id}', [App\Http\Controllers\UsersController::class, 'showFormFrais'])->name('payment_formFrais');
    Route::get('/payment-success', [App\Http\Controllers\UsersController::class, 'frais_paye'])->name('frais_paye');

    /*-----------Paiement----------*/
    Route::get('/detail_paiement/{id}', [App\Http\Controllers\UsersController::class, 'detail_paiement'])->name('detail_paiement');
});





/*-----------Admin----------*/

Route::middleware(['auth', 'role:20'])->group(function () {
    Route::get('/admin', [n_AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/members', [n_AdminController::class, 'members'])->name('utilisateurs.members');

    Route::get('/admin/members/user/{id}', [n_AdminController::class, 'specificUser'])->name('admin.showSpecificUser');
    Route::post('/admin/members/user/{id}', [n_AdminController::class, 'editSpecificUser'])->name('admin.editSpecificUser');

    Route::get('/admin/members/getDataForDataTable', [n_AdminController::class, 'getDataForDataTable']);
    Route::post('/admin/members/addUser', [n_AdminController::class, 'addUser'])->name('admin.addUser');
    Route::put('/admin/members/{user_id}', [n_AdminController::class, 'editUser'])->name('admin.editUser');
    Route::get('/admin/members/editUser/{user_id}', [n_AdminController::class, 'editUsermodal']);
    Route::get('/admin/members/delete/{user_id}', [n_AdminController::class, 'DeleteUser'])->name('admin.DeleteUser');
    Route::get('/admin/members/deletemodal/{user_id}', [n_AdminController::class, 'DeleteUsermodal']);
    Route::get('/admin/members/search', [n_AdminController::class, 'search'])->name('members.search'); # this triger a search of all user when the user press ENTER 
    Route::get('/admin/members/search_pagination', [n_AdminController::class, 'search_pagination'])->name('members.search_pagination'); #this triger a search while the user writing with pagination of 100 user max


    Route::get('/admin/portes', [n_AdminController::class, 'PortOuvindex'])->name('portesOuvertes');
    Route::get('/admin/usersget', [n_AdminController::class, 'getUsers'])->name('users.get');


    /*----------------------- ClickAsso ------------------------------ */
    Route::get('/admin/clickasso', [ClickAssoController::class, 'index'])->name('users.clickasso');
    Route::post('/sync-with-clickasso', [ClickAssoController::class, 'syncWithClickAsso']);
    Route::delete('/delete-all-members-for-year/{year}', [ClickAssoController::class, 'deleteAllMembersForYear']);

    /*----------------------- Factures ------------------------------ */
    Route::get('/admin/paiement/facture', [BillsController::class, 'index'])->name('paiement.facture');
    Route::get('/admin/paiement/facture/data', [BillsController::class, 'getData'])->name('paiement.facture.data');
    Route::get('/admin/paiement/facture/{id}', [BillsController::class, 'delete'])->name('paiement.deleteFacture');
    Route::get('/admin/paiement/factureFamille/{id}',  [BillsController::class, 'family'])->name('factureFamille');
    Route::get('/admin/paiement/facture/get-old-bills/{user_id}',  [BillsController::class, 'getOldBills']);
    Route::get('/admin/paiement/facture/showBill/{id}',  [BillsController::class, 'showBill'])->name('facture.showBill');
    Route::get('/paiement_immediat/{bill_id}', [BillsController::class, 'paiement_immediat'])->name('paiement_immediat');
    Route::post('/admin/paiement/facture/addShopMessage/{id}',  [BillsController::class, 'addShopMessage'])->name('addShopMessage');
    Route::put('/admin/paiement/facture/updateStatus/{id}',  [BillsController::class, 'updateStatus'])->name('facture.updateStatus');
    Route::put('/admin/paiement/facture/updateDes/{id}',  [BillsController::class, 'updateDes'])->name('facture.updateDes');
    Route::delete('/bill/{bill}', [BillsController::class, 'destroy'])->name('bill.destroy');
    Route::post('/stock/update', [BillsController::class, 'miseAjourStock'])->name('stock.update');
    Route::delete('/admin/supprimer-message/{id}', [BillsController::class, 'messageDestroy'])->name('message.destroy');
    Route::post('/update-addressee/{liaisonId}', [BillsController::class, 'updateAddressee'])->name('facture.updateAddressee');
    Route::delete('/delete-designation/{id}', [BillsController::class, 'deleteDesignation'])->name('delete-designation');
    Route::post('/update-bill-details', [BillsController::class, 'updateBillDetails']);





    /*----------------------- Reduction ------------------------------ */
    Route::get('/admin/paiement/reduction', [BillsController::class, 'reduction'])->name('paiement.reduction');
    Route::delete('/admin/paiement/reduction/{reduction}', [BillsController::class, 'deleteReduction'])->name('paiement.reduction.delete');
    Route::get('/reduction/{id}/edit', [BillsController::class, 'editReduction'])->name('edit.reduction');
    Route::put('/reduction/{id}/update', [BillsController::class, 'updateReduction'])->name('update.reduction');
    Route::post('/reduction/update_liaisons', [BillsController::class, 'updateLiaisons'])->name('update_liaisons');
    Route::get('/get-reduced-price/{userId}/{articleId}', [BillsController::class, 'getReducedPrice']);
    Route::get('/get-linked-users', [BillsController::class, 'getLinkedUsers']);
    Route::get('/search-users', [BillsController::class, 'searchUsers']);
    Route::delete('/remove-user-link', [BillsController::class, 'removeUserLink']);
    Route::post('/link-users-to-product', [BillsController::class, 'linkUsersToProduct']);
    Route::post('/admin/paiement/AddReductions', [BillsController::class, 'addReduction'])->name('reductionsadd');






    /*----------------------- Professionnels ------------------------------ */
    Route::get('/admin/Professionnels/gestion',  [ProfessionnelsController::class, 'gestion'])->name('Professionnels.gestion');
    Route::get('/admin/Professionnels/declarerHeure',  [ProfessionnelsController::class, 'declarerHeure'])->name('Professionnels.declarerHeure');
    Route::put('/admin/Professionnels/gestion/{user_id}',  [ProfessionnelsController::class, 'editPro'])->name('Professionnels.editPro');
    Route::put('/admin/Professionnels/gestion/addPro/{user_id}',  [ProfessionnelsController::class, 'addPro'])->name('Professionnels.addPro');
    Route::get('/admin/Professionnels/gestion/declarationList/{id_user}',  [ProfessionnelsController::class, 'declarationList']);
    Route::get('/admin/Professionnels/gestion/declarationHeures/{id}',  [ProfessionnelsController::class, 'declarationHeures'])->name('Professionnels.declarationHeures');
    Route::post('/admin/Professionnels/gestion/declaration/{id}',  [ProfessionnelsController::class, 'declaration'])->name('Professionnels.declaration');
    Route::get('/admin/Professionnels/gestion/calculSalary',  [ProfessionnelsController::class, 'calculSalary'])->name('proffesional.calculSalary');
    Route::post('/admin/Professionnels/gestion/modifySM',  [ProfessionnelsController::class, 'modifySM'])->name('proffesional.modifySM');
    Route::post('/admin/Professionnels/gestion/simuleSalary',  [ProfessionnelsController::class, 'simuleSalary'])->name('proffesional.simuleSalary');
    Route::get('/valider-heures', [ProfessionnelsController::class, 'valideHeure'])->name('proffesional.valideHeure');
    Route::post('/insertInfo', [ProfessionnelsController::class, 'saveDeclaration'])->name('saveDeclaration');
    Route::post('/engistrerDeclaration', [ProfessionnelsController::class, 'engistrerDeclaration'])->name('engistrerDeclaration');
    Route::post('/validateInfo', [ProfessionnelsController::class, 'validerDeclaration'])->name('validerDeclaration');
    Route::get('/voir_declaration/{declaration_id}', [ProfessionnelsController::class, 'voir_declaration'])->name('voir_declaration');
    Route::post('/refuser-declaration', [ProfessionnelsController::class, 'refuserDeclaration']);
    Route::post('/valider-declaration', [ProfessionnelsController::class, 'validerDec']);


    /*----------------------- END Professionnels ------------------------------ */
    Route::put('/admin/members/mdpUniversel/{user_id}', [n_AdminController::class, 'mdpUniversel'])->name('admin.mdpUniversel');
    Route::get('/admin/members/mdpUniverselmodal/{user_id}', [n_AdminController::class, 'mdpUniverselmodal']);
    Route::get('/admin/members/familleMembers/{user_id}', [n_AdminController::class, 'familleMembers']);
    /*----------------------- Systeme ------------------------------ */
    Route::post('/admin/update-system-setting', [n_AdminController::class, 'message_general'])->name('update_system_setting');
    Route::get('/admin/message-general', [n_AdminController::class, 'messageGeneral'])->name('message.general');
    Route::post('/admin/message-general', [n_AdminController::class, 'editMessageGeneral'])->name('message.general.edit');
    Route::get('/admin/message-maintenance', [n_AdminController::class, 'seeMessageMaintenance'])->name('message.maintenance.see');
    Route::post('/admin/message-maintenance', [n_AdminController::class, 'editMessageMaintenance'])->name(name: 'message.maintenance.edit');
    Route::get('/admin/modifier-carroussel', [n_AdminController::class, 'editCarroussel'])->name('carroussel.edit');
    Route::post('/admin/update-carousel', [n_AdminController::class, 'updateCarroussel'])->name('carroussel.update');
    Route::post('/admin/add-carousel', [n_AdminController::class, 'addCarroussel'])->name('carroussel.add');
    Route::post('/admin/delete-carousel/{id}', [n_AdminController::class, 'deleteCarroussel'])->name('carroussel.delete');
    Route::get('/admin/edit-image', [n_AdminController::class, 'editImage'])->name('edit.image');

    /*---------------------- Sécuriser le browsing du serveur */
    Route::get('/admin/server-browser', [ServerBrowserController::class, 'index'])->name('server.browser');
    Route::get('/elfinder', [ServerBrowserController::class, 'index']);
    Route::any('/elfinder/{any?}', [ServerBrowserController::class, 'index'])
        ->where('any', '.*');

    #-------------------------------- Valider Certificats ------------------------------

    Route::get('/admin/validationCertificats', [Controller_club::class, 'validationCertificats'])->name('validationCertificats');
    Route::post('/admin/validationCertificats', [Controller_club::class, 'validationCertificats'])->name('validationCertificats');

    #-------------------------------- Animations Admin ------------------------------
    Route::get('/admin/gestionAnimations', [AnimationsController::class, 'gestionAnimations'])->name('gestion.animations');
    Route::post('/admin/gestionAnimations/create', [AnimationsController::class, 'createAnimationBackend'])->name('gestion.animations.create');
    Route::post('/admin/gestionAnimations/edit/{id}', [AnimationsController::class, 'editAnimationBackend'])->name('gestion.animations.edit');
    Route::post('/admin/gestionAnimations/delete/{id}', [AnimationsController::class, 'deleteAnimationBackend'])->name('gestion.animations.delete');

    Route::get('/admin/gestionCategoriesAnimations', [AnimationsController::class, 'gestionCategoriesAnimations'])->name('gestion.categories.animations');
    Route::post('/admin/gestionCategory/create', [AnimationsController::class, 'createCategoryBackend'])->name('gestion.category.create');
    Route::post('/admin/gestionCategory/edit/{id}', [AnimationsController::class, 'editCategoryBackend'])->name('gestion.category.edit');
    Route::post('/admin/gestionCategory/delete/{id}', [AnimationsController::class, 'deleteCategoryBackend'])->name('gestion.category.delete');
});





/*---------------------------------ABBé------------------------------------------*/

Route::get('/', [TraitementSupp::class, 'carouselblog'])->name('A_blog')->middleware('visitor.counter');
Route::get('/fetch-posts', [A_ControllerBlog::class, 'fetchPosts']);

Route::get('/home', [TraitementSupp::class, 'carouselblog']);

#-------------------------------- Animations Visiteurs ------------------------------
Route::get('/animations', [AnimationsController::class, 'visiteursAnimation'])->name('visiteurs.animation');
Route::post('/animations/inscription', [AnimationsController::class, 'visiteursAnimationInscription'])->name('visiteurs.animation.inscription');
Route::post('/animations/desinscription', [AnimationsController::class, 'visiteursAnimationDesinscription'])->name('visiteurs.animation.desinscription');
Route::post('/animations/desinscription/{id}', [AnimationsController::class, 'visiteursAnimationDesinscriptionBackend'])->name('visiteurs.animation.desinscription.delete');


Route::middleware(['PageCounterMiddleware'])->group(function () {


    Route::get('/anniversaire', [A_ControllerBlog::class, 'anniversaire'])->name('anniversaire');
    Route::post('/sendBirthdayMail', [A_ControllerBlog::class, 'sendAnniversaire'])->name("send.birthday.mail");
    Route::get('/Simple_Post/{id}', [TraitementSupp::class, 'blog'])->name('Simple_Post');
    Route::get('/Affichage_categorie1/{id}', [A_ControllerBlog::class, 'recherche_par_cat1'])->name('A_blog_par_categorie1');
    Route::get('/Affichage_categorie2/{id}', [A_ControllerBlog::class, 'recherche_par_cat2'])->name('A_blog_par_categorie2');
    Route::get('/determinesection/count', [A_ControllerBlog::class, 'countdeterminesection'])->name('countdeterminesection');
});

/*---------------------------------Shop en backoffice------------------------------------------*/
Route::middleware(['auth'])->group(function () {



    Route::get('/Categorie_back', [A_Controller_categorie::class, 'index'])->name('A_Categorie');
    Route::post('/Categorie/save', [A_Controller_categorie::class, 'saveNestedCategories'])->name('save-categories');

    Route::post('category-subcategory/create', [A_Controller_categorie::class, 'create'])->name('create-categories');
    Route::post('category-subcategory/saveEditedCategory', [A_Controller_categorie::class, 'saveEditedCategory'])->name('saveEditedCategory');

    Route::post('category-subcategory/save', [A_Controller_categorie::class, 'store'])->name('category-subcategory.store');
    Route::get('category-subcategory/edit/{id_shop_category}', [A_Controller_categorie::class, 'edit_index'])->name('category-edit');
    Route::post('category-subcategory/edit/{id_shop_category}', [A_Controller_categorie::class, 'edit'])->name('edit');


    Route::get('category-subcategory/remove/{id_shop_category}', [A_Controller_categorie::class, 'remove'])->name('category-remove');


    Route::get('/mode_strict', [A_Controller_categorie::class, 'mode_strict'])->name('mode_strict');
    Route::get('/payment_selection', [A_Controller_categorie::class, 'payment_selection'])->name('payment_selection');
    Route::post('/process-payment', [A_Controller_categorie::class, 'processPayment'])->name('payment.process');
});

/*---------------------------------Shop en front office------------------------------------------*/

Route::middleware(['PageCounterMiddleware'])->group(function () {

    Route::get('/Categorie_front', [TraitementSupp::class, 'shop_categories'])->name('index_categorie');
    Route::get('/SubCategorie_front/{id}', [TraitementSupp::class, 'sub_shop_categories'])->name('sous_categorie');
    Route::get('/details_article/{id}', [TraitementSupp::class, 'singleProduct'])->name('details_article');
    Route::put('/commander_article/{id}', [A_Controller_categorie::class, 'commander_article'])->name('commander_article');
    Route::put('/Passer_au_paiement/{id}', [A_Controller_categorie::class, 'Passer_au_paiement'])->name('Passer_au_paiement');
    Route::get('/commanderModal/{shop_id}/{user_id}', [A_Controller_categorie::class, 'commanderModal']);
});




//Route::get('/test', [A_Controller_categorie::class, 'JsonProcess2']);


/*------------------------------ Article Back office ----------------------------------------- */

Route::middleware(['auth'])->group(function () {


    Route::get('/Article', [Article_Controller::class, 'index'])->name('index_article');
    Route::post('/Article/include-page', [Article_Controller::class, 'index_include'])->name('include-tab_articles');

    Route::post('/Article/edit/{id}', [Article_Controller::class, 'edit'])->name('edit_article');
    Route::get('/Article/edit/{id}', [Article_Controller::class, 'edit_index'])->name('edit_article_index');
    Route::post('/Article/updateSeance', [Article_Controller::class, 'updateLesson'])->name('lesson.update');


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
/*------------------------------ Article v2 Back office ----------------------------------------- */
Route::middleware(['auth'])->group(function () {
    Route::get('/article', [ArticlePostController::class, 'index'])->name('article');
    Route::get('/articles/fetch', [ArticlePostController::class, 'fetchArticles'])->name('Article2_fetchArticles');
    Route::post('/articles/fetch', [ArticlePostController::class, 'fetchArticles'])->name('Article2_fetchArticles');
});
/*------------------------------ BLOG BACK OFFICE ----------------------------------------- */

Route::middleware(['auth'])->group(function () {

    Route::get('/BlogArticle_index', [BlogArticle_Controller::class, 'index'])->name('index');
    Route::post('/BlogArticle_index', [BlogArticle_Controller::class, 'edit_color_user'])->name('edit_color_user');

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
    Route::post('/BlogArticle_category/edit/{id}', [BlogArticle_Controller::class, 'edit_cate'])->name('edit_cate');
});

/*----------------------------- Mention legales -------------------------------------------------- */
Route::middleware(['PageCounterMiddleware'])->group(function () {

    Route::get('/Mentions', [Controller_mention_legales::class, 'index'])->name('index_mentions_legales');
    Route::get('/Politique_de_confidentialite', [Controller_mention_legales::class, 'index_politique'])->name('index_politique');
});


Route::middleware(['auth'])->group(function () {

    /*------------------------------ Communication ----------------------------------------- */
    Route::get('/Communication', [Controller_Communication::class, 'index'])->name('index_communication');
    Route::get('/get-buyers-for-shop-article/{id}', [Controller_Communication::class, 'getBuyersForShopArticle']);
    Route::post('/get-emails', [Controller_Communication::class, 'getEmails']);
    Route::post('/send-emails', [Controller_Communication::class, 'sendEmails']);


    Route::post('/path/to/upload/endpoint', [Controller_Communication::class, 'uploadAttachment'])->name('attachment.upload');
    Route::post('/path/to/delete/endpoint', [Controller_Communication::class, 'deleteAttachment'])->name('attachment.delete');

    Route::get('/Communication/get_info/{article_id}', [Controller_Communication::class, 'get_info'])->name('get_communication');

    Route::post('/Communication/traitement', [Controller_Communication::class, 'traitement'])->name('traitement');

    Route::post('/Communication/email_sender', [Controller_Communication::class, 'email_sender'])->name('email_sender');

    Route::get('/Commnication/email_page', [Controller_Communication::class, 'email_page'])->name('email_page');

    Route::post('/display_saison', [Controller_Communication::class, 'display_by_saison'])->name('display_by_saison');

    Route::get('/Communication/historique', [Controller_Communication::class, 'historique'])->name('historique');
});




Route::middleware(['auth'])->group(function () {

    /*----------------------- Club - cours ------------------------------ */
    Route::get('/club/cours_index', [Controller_club::class, 'index_cours'])->name('index_cours');
    Route::get('/club/produits_index', [Controller_club::class, 'index_produits'])->name('index_produits');
    Route::get('/club/adhesions_index', [Controller_club::class, 'index_adhesions'])->name('index_adhesions');

    Route::get('/club/certifications-niveaux/{id}', [Controller_club::class, 'certifications_niveaux'])->name('certifications_niveaux');
    Route::post('/club/getUserCertifications', [Controller_club::class, 'get_user_certifications'])->name('getUserCertifications');
    Route::post('/club/certifications-niveaux/{id}', [Controller_club::class, 'certifications_niveaux_backend'])->name('certifications_niveaux_backend');

    Route::get('/generate-pdf/{id}', [Controller_club::class, 'generatePdf'])->name('generate.pdf');
    Route::get('/generate-combined-pdf', [Controller_club::class, 'generateCombinedPdf'])->name('generate.combined.pdf');

    Route::post('/get_data_table/{article_id}', [Controller_club::class, 'get_data_table'])->name('get_data_table');



    Route::post('/club/include-page', [Controller_club::class, 'index_include'])->name('include-page');

    //Route::get('/shop_article_cours_ajax/{id}', [Controller_club::class, 'display_form_cours'])->name('form_cours');

    Route::get('/form_appel/{id}', [Controller_club::class, 'form_appel_method'])->name('form_appel');

    Route::get('/club/display_modal/{id}', [Controller_club::class, 'display_info_user'])->name('info_appel');

    Route::post('/modal_post/{id}', [Controller_club::class, 'modif_user'])->name('modif_user');

    Route::post('/form_appel/{id}', [Controller_club::class, 'enregister_appel_method'])->name('enregistrer_appel');

    Route::get('/historique_appel/{id}', [Controller_club::class, 'display_historique_method'])->name('historique_appel');

    Route::get('/historique_appel/generate-pdf/{id}', [Controller_club::class, 'generate_historique_appel']);


    Route::get('/club/StatsExports', [Controller_club::class, 'StatsExports'])->name('StatsExports');

    Route::post('/club/export-csv', [Controller_club::class, 'exportCSV'])->name('export.csv');

    Route::post('/club/export-csvProduit', [Controller_club::class, 'exportCSVproduit'])->name('export.csvProduit');

    Route::delete('/delete-declinaison/{id}', [Controller_club::class, 'deleteMethod'])->name('delete.declinaison');

    Route::post('/club/declinaison/add', [Controller_club::class, 'addMethod'])->name('add.declinaison');

    #-------------------------------pdf generate-------------------

    #-------------------------------route pour gerer l'envoi de mail  generate-------------------

});





#-------------------------------- Statistiques ------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/Statistiques/Home_stat_teacher', [Controller_Stat::class, 'index'])->name('index_stat');
});
#-------------------------------- RESEET ------------------------------

Route::get('/username/reminder', [ForgotUsernameController::class, 'showRequestForm'])->name('username.reminder');
Route::post('/username/reminder', [ForgotUsernameController::class, 'sendUsernameEmail'])->name('username.email');

/*----------------------- Recherche ------------------------------ */

Route::get('/search/blog', [SearchController::class, 'searchBlog']);
Route::get('/search/shop', [SearchController::class, 'searchShop']);
Route::get('/search-results', [SearchController::class, 'searchResults']);


/*----------------------- Gestion des roles  ------------------------------ */
Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RolesController::class, 'indexRoles'])->name('index_roles');

    Route::post('/roles/{id}', [RolesController::class, 'modif_les_roles'])->name('modif_roles');

    Route::post('/creation', [RolesController::class, 'creation_roles'])->name('creation_roles');

    Route::get('/delete/{id}', [RolesController::class, 'methode_delete'])->name('delete_role');
    Route::get('/GestionSalles', [RolesController::class, 'index_salle'])->name('index_salle');
    Route::delete('/suprimer_salle/{id}', [RolesController::class, 'destroy'])->name('rooms.destroy');
    Route::get('/modifier_salle/{id}', [RolesController::class, 'edit'])->name('rooms.edit');
    Route::put('/modifier_salle/{id}', [RolesController::class, 'update'])->name('rooms.update');
});

/*--------------------- Member history --------------------------------------- */
Route::middleware(['auth'])->group(function () {
    Route::get('/member_history', [Member_History_Controller::class, 'index'])->name('history_index');

    Route::post('/member_history', [Member_History_Controller::class, 'save_history'])->name('save_history');


    Route::get('/member_historique', [Member_History_Controller::class, 'consult_historique'])->name('consult_historique');
    Route::post('/member_historique', [Member_History_Controller::class, 'history_include_data'])->name('consult_historique_post');
});


/*---------------------------------Maintenance------------------------------------------*/
Route::post('/verify-password', [MaintenanceController::class, 'verifyPassword'])->name('verify_password');

Route::middleware(['auth', 'role:20'])->group(function () {
    /*---------------------------------parametres------------------------------------------*/
    Route::get('/parametres', [ParametreController::class, 'index'])->name('parametres');
    Route::post('/setActiveSeason', [ParametreController::class, 'setActiveSeason'])->name('setActiveSeason');
    Route::get('/create-new-season', [ParametreController::class, 'createNewSeason'])->name('createNewSeason');
    Route::put('/saison/edit/{id}', [ParametreController::class, 'update'])->name('editSeason');
    Route::post('/saison/{season}/duplicate', [ParametreController::class, 'duplicateProducts'])->name('seasons.duplicate');
    Route::post('/upgradeArticles', [ParametreController::class, 'upgradeArticles'])->name('upgradeArticles');
});

//----questionnaire----------------//



Route::get('/masection', [QuestionnaireController::class, 'index'])->name('masection');
Route::get('/result', [QuestionnaireController::class, 'result'])->name('result');


/****-------------------Traitement Supplementaire  ----------*/
Route::get('/fusionsql', [TraitementSupp::class, 'fusionsql'])->name('fusionsql');
Route::get('/jsoncorrection', [TraitementSupp::class, 'jsoncorrection'])->name('jsoncorrection');
Route::get('/fusionnertable', [TraitementSupp::class, 'fusionnertable'])->name('fusionnertable');
Route::get('/carouselblog', [TraitementSupp::class, 'carouselblog'])->name('carouselblog');
Route::get('/tousLesArticles', [TraitementSupp::class, 'tousLesArticles'])->name('tousLesArticles');
Route::get('/blog/{id}', [TraitementSupp::class, 'blog'])->name('blog');
Route::get('/shop', [TraitementSupp::class, 'shop'])->name('shop');
Route::get('/shop_categories', [TraitementSupp::class, 'shop_categories'])->name('shop_categories');
Route::get('/sub_shop_categories/{id}', [TraitementSupp::class, 'sub_shop_categories'])->name('sub_shop_categories');
Route::get('/boutique/{id}', [TraitementSupp::class, 'boutique'])->name('boutique');
Route::get('/singleProduct/{id}', [TraitementSupp::class, 'singleProduct'])->name('singleProduct');
Route::get('/mesfactures', [TraitementSupp::class, 'mesfactures'])->name('mesfactures');
Route::get('/mafacture/{id}', [TraitementSupp::class, 'mafacture'])->name('mafacture');
Route::get('/mafamille', [TraitementSupp::class, 'mafamille'])->name('mafamille');
Route::get('/generatePDFboutique/{id}', [TraitementSupp::class, 'generatePDF'])->name('generatePDFboutique');
Route::post('/prendre_contact', [Prendre_Contact_Controller::class, 'traitement_prendre_contact'])->name('traitement_prendre_contact');
Route::middleware(['auth'])->group(function () {
    Route::get('/basket', [TraitementSupp::class, 'basket'])->name('basket');
    Route::get('/paiement', [TraitementSupp::class, 'paiement'])->name('paiement');
    Route::get('/fichepaiement/{id}/{nombre_cheques}', [TraitementSupp::class, 'fichepaiement'])->name('fichepaiement');
    Route::post('/choisir_place/{id}', [TraitementSupp::class, 'choisir_place'])->name('choisir_place');
});


/****------------------- spectacles  and seats  ----------*/

Route::middleware(['auth'])->group(function () {

    Route::get('/spectacles', [\App\Http\Controllers\SpectacleController::class, 'index'])->name('spectacles.index');
    Route::get('/spectacles/create', [\App\Http\Controllers\SpectacleController::class, 'create'])->name('spectacles.create');
    Route::post('/spectacles/store', [\App\Http\Controllers\SpectacleController::class, 'store'])->name('spectacles.store');
    Route::get('/spectacles/{spectacle}/edit', [\App\Http\Controllers\SpectacleController::class, 'edit'])->name('spectacles.edit');
    Route::put('/spectacles/{spectacle}/update', [\App\Http\Controllers\SpectacleController::class, 'update'])->name('spectacles.update');
    Route::delete('/spectacles/{spectacle}', [\App\Http\Controllers\SpectacleController::class, 'destroy'])->name('spectacles.destroy');
    Route::get('/spectacles/payment', [\App\Http\Controllers\SpectacleController::class, 'showFormSpect'])->name('spectacles.showFormSpect');
    Route::get('/spectacles/detail_paiement/{id}', [App\Http\Controllers\SpectacleController::class, 'detail_paiement'])->name('spectacle.detail_paiement');
    Route::match(['get', 'post'], '/spectacles/validation_Teckit', [App\Http\Controllers\SpectacleController::class, 'validateTicket'])->name('spectacle.validateTicket');


    Route::get('/spectacles/seats/{id}', [\App\Http\Controllers\SpectacleController::class, 'seats'])->name('spectacles.seats');
});