<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'P_Home';

$route['blog/search'] = 'P_Blog/searchBlog';
$route['shop/search'] = 'P_Blog/searchShop';
$route['users/search'] = 'P_Blog/searchUsers';

$route['searchPage'] = 'P_Home/searchPage';

$route['searchDetail'] = 'P_searchShop';
$route['searchDetail/savePDF'] = 'P_searchShop/save_en_pdf';

$route['shop'] = 'P_Shop';
$route['apkdll'] = 'P_Home/dlapk';

$route['home'] = 'P_Home';
$route['home/mobile'] = 'P_Home/MobileHome';

$route['Api/display_bill'] = 'api/Api/display_bill';

$route['reset-password'] = 'Members/M_ForgetPassword/generate_NewPass';

$route['erreur404'] = $route['default_controller'] . '/erreur404';
$route['403'] = '';

$route['firstConnexion'] = 'P_Home/firstConnexion';


$route['deconfinement'] = 'P_Lessons/getLessons/deconfinement';
/*$route['deconfinement/add'] = 'P_Lessons/add';
$route['deconfinement/join'] = 'P_Lessons/join';
$route['deconfinement/deleteS'] = 'P_Lessons/deleteStudent';
$route['deconfinement/deleteL'] = 'P_Lessons/deleteLesson';*/

$route['lesson'] = 'P_Lessons';
$route['lesson/(:any)'] = 'P_Lessons/getLessons/$1';
$route['lessons/add'] = 'P_Lessons/add';
$route['(:any)/lessons/add'] = 'P_Lessons/add';
$route['lessons/addT'] = 'P_Lessons/addCategory';
$route['lesson/lessons/addlocation'] = 'P_Lessons/addlocation';
$route['lessons/addlocation'] = 'P_Lessons/addlocation';
$route['(:any)/lessons/addT'] = 'P_Lessons/addCategory';
$route['lesson/(:any)/join'] = 'P_Lessons/join';
$route['lessons/join'] = 'P_Lessons/join';
$route['lesson/(:any)/deleteS'] = 'P_Lessons/deleteStudent';
$route['lesson/(:any)/deleteL'] = 'P_Lessons/deleteLesson';

$route['cours-covid'] = 'P_Lessons/getLessons/deconfinement';
//Config blog
$route['(:num)'] = $route['default_controller'] . '/index/$1';
$route['category/(:any)/(:any)'] = $route['default_controller'] . '/category/$1/$2';
$route['term/(:any)/(:any)'] = $route['default_controller'] . '/term/$1/$2';
$route['view/anniversaires'] = 'P_Anniversaire'; //Override pour redirection vers page Anniversaire plutôt que l'article
$route['view/(:any)'] = $route['default_controller'] . '/view/$1';
$route['delete-comment/(:any)/(:any)'] = $route['default_controller'] . '/delete_comment/$1/$2';

//Config shop
$route['shop/details/(:any)'] = 'P_Shop' . '/details/$1';
$route['shop/buy/(:any)/(:any)'] = 'P_Shop' . '/buy/$1/$2';
$route['shop/remove_article/(:any)/(:any)/(:any)'] = 'P_Shop' . '/remove_article/$1/$2/$3';
$route['shop/basket_content'] = 'P_Shop/basket_content';
$route['shop/basket_content/(:any)'] = 'P_Shop/basket_content/$1';
// $route['shop/category/(:any)/(:any)'] = 'P_Shop' . '/category/$1/$2';
$route['shop/category/(:any)'] = 'P_Shop' . '/category/$1';
$route['shop/update_content'] = 'P_Shop' . '/update_content';
$route['shop/paiement'] = 'P_Shop' . '/paiement';
$route['shop/paiement/(:any)'] = 'P_Shop' . '/paiement/$1';
$route['shop/paiement_type'] = 'P_Shop' . '/paiement_type';
$route['shop/paiement_accepted/(:any)'] = 'P_Shop' . '/paiement_accepted/$1';
$route['shop/paiement_accepted_bank/(:any)/(:any)'] = 'Bank_response' . '/paiement_accepted/$1/$2';
$route['viewquote/(:any)'] = 'Members/M_Basket/devisfrompaiement/$1';
$route['shop/(:any)'] = 'P_Shop' . '/index/$1';


//Gestion Gala
$route['admin/gala'] = 'Admin/A_Gala';
$route['gala/(:any)'] = 'P_Gala' . '/details/$1';

$route['admin/clickasso'] = 'Admin/A_Clickasso';
$route['function/clickasso/firstConnexion'] = "Admin/A_Clickasso/firstConnexion";



//Config admin blog
$route['franz'] = 'Admin/A_Home/TestASupprimer';
$route['admin'] = 'Admin/A_Home';
$route['admin/blog'] = 'Admin/A_Blog';
$route['admin/blog/comments-list'] = 'Admin/A_Blog/comments_list';
$route['admin/blog/togglecom'] = 'Admin/A_Blog/toggle_comments';
$route['admin/blog/edit-comment'] = 'Admin/A_Blog/edit-comment';
$route['admin/blog/edit-comment/(:any)'] = 'Admin/A_Blog/edit_comment/$1';
$route['admin/blog/edit-comment/(:any)/(:any)'] = 'Admin/A_Blog/edit_comment/$1/$2';
$route['admin/blog/delete-comment/(:any)'] = 'Admin/A_Blog/delete_comment/$1';
$route['admin/blog/terms-list'] = 'Admin/A_Blog/terms_list';
$route['admin/blog/edit-term'] = 'Admin/A_Blog/edit_term';
$route['admin/blog/edit-term/(:num)'] = 'Admin/A_Blog/edit_term/$1';
$route['admin/blog/delete-term/(:num)'] = 'Admin/A_Blog/delete_term/$1';
$route['admin/blog/posts-list'] = 'Admin/A_Blog/posts_list';
$route['admin/blog/edit-post'] = 'Admin/A_Blog/edit_post';
$route['admin/blog/edit-post/(:num)'] = 'Admin/A_Blog/edit_post/$1';
$route['admin/blog/delete-post/(:num)'] = 'Admin/A_Blog/delete_post/$1';
$route['admin/blog/categories-list'] = 'Admin/A_Blog/categories_list';
$route['admin/blog/edit-category'] = 'Admin/A_Blog/edit_category';
$route['admin/blog/edit-category/(:num)'] = 'Admin/A_Blog/edit_category/$1';
$route['admin/blog/delete-category/(:num)'] = 'Admin/A_Blog/delete_category/$1';
$route['admin/blog/delete-category-with-posts/(:num)'] = 'Admin/A_Blog/delete_category_with_posts/$1';
$route['admin/blog/caroussel/(:any)/(:num)'] = 'Admin/A_Blog/set_postToCaroussel/$1/$2';
$route['admin/blog/caroussel'] = 'Admin/A_Blog/manage_caroussel';
$route['admin/blog/add-caroussel'] = 'Admin/A_Blog/add_caroussel';
$route['admin/blog/edit-caroussel'] = 'Admin/A_Blog/edit_caroussel';
$route['admin/blog/delete-caroussel'] = 'Admin/A_Blog/delete_caroussel';

//Config admin shop
$route['admin/shop'] = 'Admin/A_Shop';
$route['admin/shop/articles-list'] = 'Admin/A_Shop/articles_list';
$route['admin/shop/articles-list-old'] = 'Admin/A_Shop/articles_list_old';
$route['admin/shop/edit-article'] = 'Admin/A_Shop/edit_article';
$route['admin/shop/edit-article/(:any)'] = 'Admin/A_Shop/edit_article/$1';
$route['admin/shop/edit-article/(:any)/(:any)'] = 'Admin/A_Shop/edit_article/$1/$2';
$route['admin/shop/edit-course-regular'] = 'Admin/A_Shop/edit_course_regular';
$route['admin/shop/edit-course-regular/(:any)'] = 'Admin/A_Shop/edit_course_regular/$1';
$route['admin/shop/edit-course-occasional'] = 'Admin/A_Shop/edit_course_occasional';
$route['admin/shop/edit-course-occasional/(:any)'] = 'Admin/A_Shop/edit_course_occasional/$1';
$route['admin/shop/delete-article/(:any)'] = 'Admin/A_Shop/delete_article/$1';
$route['admin/shop/categories-list'] = 'Admin/A_Shop/categories_list';
$route['admin/shop/modify-categories-list'] = 'Admin/A_Shop/modify_categories_list';
$route['admin/shop/edit-category'] = 'Admin/A_Shop/categories_list';
$route['admin/shop/edit-category/(:any)'] = 'Admin/A_Shop/edit_category/$1';
$route['admin/shop/updateHierarchy'] = 'Admin/A_Shop/updateCat';
$route['admin/shop/add-category'] = 'Admin/A_Shop/add_category';
$route['admin/shop/delete-category/(:any)'] = 'Admin/A_Shop/delete_category/$1';
$route['admin/bills'] = 'Admin/A_Bills/manage_bills';
$route['admin/oldbills'] = 'Admin/A_Bills/manage_oldbills';
$route['admin/otherbills'] = 'Admin/A_Bills/manage_otherbills';
$route['admin/quotes'] = 'Admin/A_Bills/manage_quotes';
$route['admin/reductions'] = 'Admin/A_Bills/manage_reductions';
$route['admin/edit-reduction'] = 'Admin/A_Bills/edit_reduction';
$route['admin/edit-reduction/(:any)'] = 'Admin/A_Bills/edit_reduction/$1';
$route['admin/edit-reduction/(:any)/(:any)'] = 'Admin/A_Bills/edit_reduction/$1/$2';
$route['admin/system'] = 'Admin/A_System/manage_system';
$route['admin/system/newseason'] = 'Admin/A_System/newseason';
$route['admin/system/exports'] = 'Admin/A_System/exports';
$route['admin/system/statistics'] = 'Admin/A_System/statistics';
$route['admin/system/statistics/(:any)'] = 'Admin/A_System/statistics/$1';
$route['admin/system/rooms-list'] = 'Admin/A_System/rooms_list';
$route['admin/system/edit-room'] = 'Admin/A_System/edit_room';
$route['admin/system/list-groups'] = 'Admin/A_System/A_Listes_Groupes';
$route['admin/system/edit-room/(:any)'] = 'Admin/A_System/edit_room/$1';
$route['admin/system/delete-room/(:any)'] = 'Admin/A_System/delete_room/$1';
$route['admin/system/duplication'] = 'Admin/A_System/duplication';
$route['admin/teacher_scheduler'] = 'Admin/A_TeacherScheduler/index';
$route['admin/admin_scheduler'] = 'Admin/A_Scheduler/index';
$route['admin/basket/empty'] = 'Admin/A_Shop/empty_carts';
$route['admin/m_general'] = 'Admin/A_message_general';
$route['admin/m_general/modify'] = 'Admin/A_message_general/modifyMessage';
$route['admin/m_general/Switch'] = 'Admin/A_message_general/Switch';
$route['admin/m_general/Background'] = 'Admin/A_message_general/Background';
$route['admin/m_general/Vente'] = 'Admin/A_message_general/Vente';

// $route['admin/A_Bills/delete_quote/$1'] = 'Shop_articles/delete_quote/$1';

//Config Admin user&permission
$route['admin/user-perm'] = 'Admin/A_Right';
$route['admin/perm'] = 'Admin/A_Right/page_right';
$route['admin/perm/modify/(:num)'] = 'Admin/A_Right/modify_right/$1';
$route['admin/perm/create'] = 'Admin/A_Right/create_right';
// --USER
$route['admin/perm-user'] = 'Admin/A_Users';
$route['admin/family/(:any)/(:num)'] = 'Admin/A_Users/modify_familyUsers/$1/$2';
$route['admin/users/delete/(:num)'] = 'Admin/A_Users/modify_Users/$1';
$route['admin/users/froze'] = 'Admin/A_Users/Froze_Users';
$route['admin/users/edit/(:num)'] = 'Admin/A_MemberEdit/index/$1';
$route['admin/family'] = 'Admin/A_Users/manage_family';
$route['admin/users'] = 'Admin/A_Users/manage_users';
$route['admin/portesouvertes'] = 'Admin/A_PortesOuvertes';
$route['admin/portesouvertes/modifyuser'] = 'Admin/A_PortesOuvertes/modifyUser';
$route['admin/teachers'] = 'Admin/A_Users/manage_teachers';
$route['admin/teacherstat/(:any)'] = 'Admin/A_Users/teacher_stat/$1';
$route['admin/familydetail/(:num)'] = 'Admin/A_Users/manage_familyDetail/$1';
// --USER GROUPS
$route['admin/usersgroups'] = 'Admin/A_UsersGroups';
$route['admin/usersgroups/groups'] = 'Admin/A_UsersGroups/UsersGroups';
$route['admin/usersgroups/groups/givegroup'] = 'Admin/A_UsersGroups/givegroup';
$route['admin/usersgroups/groups/create'] = 'Admin/A_UsersGroups/create_UserGroup';
$route['admin/usersgroups/groups/delete/(:num)'] = 'Admin/A_UsersGroups/delete_UserGroup/$1';
$route['admin/usersgroups/groups/modify/(:num)'] = 'Admin/A_UsersGroups/modify_UserGroup/$1';


// -- PROFESSIONAL
$route['admin/professional/manage'] = 'Admin/A_Professional';
$route['admin/professional/declarationList/(:any)'] = 'Admin/A_Professional/declarationList/$1';
$route['admin/professional/salary'] = 'Admin/A_Professional/calculSalary';
$route['admin/professional/declareHeure/(:any)'] = 'Admin/A_Professional/declareHeure/$1';
$route['admin/professional/chooseProfessional'] = 'Admin/A_Professional/chooseProfessional';
$route['admin/professional/valideHeure'] = 'Admin/A_Professional/valideHeure';
$route['admin/professional/validate'] = 'Admin/A_Professional/Validate';
$route['admin/professional/modify/(:any)'] = 'Admin/A_Professional/modifyProfessional/$1';
$route['admin/professional/add'] = 'Admin/A_Professional/addProfessionnal';
$route['admin/professional/modifySM'] = 'Admin/A_Professional/modifySM';
$route['admin/professional/simuleSalary'] = 'Admin/A_Professional/simuleSalary';
$route['admin/professional/newSeason'] = 'Admin/A_Professional/newSeason';

// --SECTION
$route['admin/section'] = 'Admin/A_Section';
$route['admin/pagesection'] = 'Admin/A_Section/page_section';

// -- ANIMATIONS
$route['admin/system/animations'] = 'Admin/A_Animations';
$route['admin/system/animations/modify/(:any)'] = 'Admin/A_Animations/modifyAnimation/$1';
$route['admin/system/animations/add'] = 'Admin/A_Animations/addAnimation';
$route['admin/system/animations/delete/(:any)'] = 'Admin/A_Animations/deleteAnimation/$1';

// -- QUESTIONNAIRE

$route['admin/questionnaire/manage'] = 'Admin/A_Questionnaire/manage_questionnaires';
$route['admin/questionnaire/test/(:num)'] = 'Admin/A_Questionnaire/display_questionnaire/$1';
$route['admin/questionnaire/add'] = 'Admin/A_Questionnaire/load_questionnaire_add';
$route['admin/questionnaire/add_questions/(:num)'] = 'Admin/A_Questionnaire/add_questions/$1';
$route['admin/questionnaire/edit/(:num)'] = 'Admin/A_Questionnaire/load_questionnaire_edit/$1';
$route['admin/questionnaire/delete/(:num)'] = 'Admin/A_Questionnaire/delete_questionnaire/$1';

// -- Mobile Prof

$route['mobile'] = 'Mobile/Mob_Login';
$route['mobile/home'] = 'Mobile/Mob_Home';
$route['mobile/logout'] = 'Mobile/Mob_Login/logout';
$route['mobile/courses'] = 'Mobile/Mob_Courses';
$route['mobile/mailing'] = 'Mobile/Mob_Messenger';
$route['mobile/smsing'] = 'Mobile/Mob_Messenger/smsmessenger';
$route['mobile/chatgroups'] = 'Mobile/Mob_Chat_Groups/chatmessenger';
$route['mobile/history'] = 'Mobile/Mob_History';
$route['mobile/add_private_group'] = 'Mobile/Mob_Chat_Groups/new_private_chat';
$route['mobile/add_private_group/(:any)'] = 'Mobile/Mob_Chat_Groups/create_private_group/$1';
$route['mobile/add_public_group'] = 'Mobile/Mob_Chat_Groups/create_public_group';
$route['mobile/members_group/(:any)'] = 'Mobile/Mob_Members_Group/loadPage/$1';
$route['mobile/add_members_group/(:any)'] = 'Mobile/Mob_Members_Group/add_members_group/$1';
$route['mobile/change_name/(:any)'] = 'Mobile/Mob_Members_Group/change_name/$1';
$route['mobile/delete_members_group/(:any)/(:any)'] = 'Mobile/Mob_Members_Group/delete_members_group/$1/$2';

// GoCardless routing
$route["gocardless"] = "P_GoCardless";
$route["gocardless/redirect"] = "P_GoCardless/redirect";
$route["gocarless/subscription"] = "P_GoCardless/subscription";

//Config member
$route['member'] = 'Members/M_Member';
$route['scheduler'] = 'Members/M_Scheduler';
$route['family'] = 'Members/M_Family';
$route['family/create'] = 'Members/M_Family/create_family';
$route['family/leave/(:any)'] = 'Members/M_Family/leave_family/$1';
$route['family/delete'] = 'Members/M_Family/delete_family';
$route['family/add-child'] = 'Members/M_Family/add_infant';
$route['family/add-parent/register'] = 'Members/M_Family/add_parent';
$route['family/add-parent/account'] = 'Members/M_Family/add_parentWithAccount';
$route['family/set-indep'] = 'Members/M_Family/set_independant';
$route['loginPage'] = 'Members/M_Login';
$route['login'] = 'Members/M_Login/login';
$route['logout'] = 'Members/M_Login/logout';
$route['register'] = 'Members/M_Register';
$route['registerForm/(:any)'] = 'Members/M_Register/displayForm/$1';
$route['register/sms'] = 'Members/M_Register/send_sms';
$route['register/register'] = 'Members/M_Register/register';
$route['register/mail_auto'] = 'Members/M_Register/mail_auto';
$route['basket'] = 'Members/M_Basket';
$route['bill'] = 'Members/M_Bill';
$route['message'] = 'Members/M_Message';
$route['chatgroup'] = 'Members/M_Chat_Group';
$route['mention'] = 'P_Legales';
$route['sms'] = 'Admin/A_System/send_sms';

$route['inscription'] = 'Members/M_Inscription';
$route['inscription/register'] = 'Members/M_Inscription/register';
$route['inscription/adulte'] = 'Members/M_Inscription/addunparent';
$route['inscription/enfant'] = 'Members/M_Inscription/addunenfant';

$route['notReadMsg'] = 'Members/M_Chat/notReadMsg';

$route['chat/getMessage'] = 'Members/M_Chat/getMessage';
$route['chat/saveMessage'] = 'Members/M_Chat/saveMessage';
$route['chat/sendNotification'] = 'Members/M_Chat/sendNotification';
$route['viewbasket/(:any)'] = 'Members/M_Basket/get_data_basket/$1';
$route['actionbasket/(:any)'] = 'Members/M_Basket/actionbasket/$1';
$route['viewbill/(:any)'] = 'Members/M_Bill/get_data_bill/$1';
$route['viewchat/(:any)'] = 'Members/M_Chat/get_data_chat/$1';
$route['groupchat/delete/(:any)'] = 'Members/M_Chat_Group/delete_group/$1';
$route['actionbill/(:any)'] = 'Members/M_Bill/actionbill/$1';
$route['404_override'] = '';
$route['403_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['validation'] = 'Members/M_Validation/accept_registration';
$route['confirm'] = 'Members/M_Validation';
$route['anniversary/create'] = 'Members/Anniversaries/createSlider';


$route['captcha'] = 'Members/M_Register/GenCaptcha';



$route['shop/bills/generateAllCertifs'] = 'Members/M_Bill/generateAllCertifs';


$route['admin/cleanup_natio_user'] = 'Admin/A_System/nettoyage_natio_user';


$route['blog_scroll'] = 'P_Blog/loadarticle';

//controlleur test
$route['test/gymetude'] = 'Test/gymetude';
$route['test/memobalises'] = 'Test/memobalises';
$route['tests/determinesectionjeune'] = 'Test/determinesectionjeune';
$route['test/pay/assu'] = 'Test/assu';
$route['test/pay/questionnaire'] = 'Test/questionnaire';
$route['test/pay/upcertif'] = 'Test/upcertif';
$route['test/pay/uploader'] = 'Test/uploader';
$route['test/pay/up_questions_majeur'] = 'Test/up_questions_majeur';
$route['test/pay/up_questions_mineur'] = 'Test/up_questions_mineur';
$route['test/pay/up_questions_mixte'] = 'Test/up_questions_mixte';
$route['test/reass_att'] = 'Test/reass_attestation';
$route['test/courses'] = 'Test/select_all_courses';
$route['test/attestation'] = 'Test/attestations_public';
$route['test/attestation/upload'] = 'Test/do_upload';

// Test du questionnaire
$route['aled'] = 'P_Questionnaire';

$route['determinesection'] = 'P_Lessons/determinesection';
$route['determinesection/count'] = 'P_Lessons/countdeterminesection';

$route['admin/bills/reass_att'] = 'Admin/A_Bills/reass_attestation';
$route['admin/quotes/deleteold'] = 'P_Shop/delete_old_quote';
$route['courses'] = 'Members/M_Family/select_all_courses';

$route['admin/attestation/admin'] = 'Admin/A_Users/A_all_certificates';
$route['admin/attestation/admin/update'] = 'Admin/A_Users/up_certif_valid';

$route['certificates'] = 'Members/M_Certificate/attestations_public';
$route['certificates/upload'] = 'Members/M_Certificate/do_upload';

$route['mobile/courses/add_certificate/(:any)/(:any)'] = 'Members/M_Certificate/admin_attest/$1/$2';
$route['mobile/courses/add_certificate/upload'] = 'Members/M_Certificate/A_do_upload';

$route['mobile/courses/appel/(:num)'] = 'Mobile/Mob_Courses/course_appel/$1';

$route['mobile/courses/historique/(:num)'] = 'Mobile/Mob_Courses/course_history/$1';

$route['erreur'] = 'Admin/A_System/display_error_page';

//Nouveau firstconnection
$route['first/saveBDD'] = 'FirstConnection/dumpBDD';
$route['first/delete_old_dump'] = 'FirstConnection/delete_old_dump';
$route['first/clean_carts'] = 'FirstConnection/clean_carts';
$route['first/delete_old_quote'] = 'FirstConnection/delete_old_quote';
$route['first/delete_old_users'] = 'FirstConnection/delete_old_users';
$route['first/slider'] = 'FirstConnection/slider';
$route['first/to_old_bills'] = 'FirstConnection/to_old_bills';
$route['first/nettoyage_cart'] = 'FirstConnection/nettoyage_cart';
$route['first/reass_attestation'] = 'FirstConnection/reass_attestation';
$route['first/plusdepelliculepourlesprofesseurs'] = 'FirstConnection/plusdepelliculepourlesprofesseurs';
$route['first/killennemiesofthecertificates'] = 'FirstConnection/killennemiesofthecertificates';

$route['login/mobile/(:num)'] = 'Mobile/Mob_Login/login_website/$1';
$route['register/mobile'] = 'Mobile/Mob_Login/registerMobile';


$route['inscriptionenfant'] = 'Members/M_Inscription/test';
$route['inscriptionadulte'] = 'Members/M_Inscription/test';
