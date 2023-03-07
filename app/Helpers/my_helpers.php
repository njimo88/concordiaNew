<?php
/**
 * Created by PhpStorm.
 * User: MathR
 * Date: 31/05/2015
 * Time: 16:10
 *
 * Auth est un helper d'authentification fonctionnement sur un principe de droit depuis la BDD.
 *
 *
 * Fonctionnement :
 *  -
 *  -
 */

if(! function_exists('call_Right')) {
    function call_Right($session, $tag)
    {

        /* Permet la gestion des droits en fonction du niveau de l'utilisateur
         *
         * Cette fonction peut s'employer dans une vue et/ou un controlleur
         *
         * -----
         * $this->load->helper('Auth');
         *
         * call_Right( Session, 'addBlog');
         * -----
         *
         * Les TAG :
         * #Blog
         * addBlog
         * publishBlog
         * deleteBlog
         * editBlog
         *
         * #Blog Catégorie
         * addBCat
         * deleteBCat
         * editBCat
         *
         *
         * #Shop Catégorie
         * addSCat
         * deleteSCat
         * editSCat
         *
         * #Shop
         * addArticle
         * publishArticle
         * deleteArticle
         * editArticle
         *
         * #Users / right (admin panel)  access
         * editAusers
         * editAright
         * editAfamily
         *
         *
         * #Teacher access
         * teacher
         */

        // _________________________________________________
        //
        // BLOG Parts
        // _________________________________________________

        if ($tag == 'accessAdmin'){
            if($session['isTeacher'] == 1 || $session['isAllowedToPublishPost'] == 1 || $session['isAllowedToAddPost'] == 1 || $session['isAllowedToEditOtherPost'] == 1
                || $session['isAllowedToDeleteOtherPost'] == 1 || $session['isAllowedToAddBlogCategory'] == 1 || $session['isAllowedToUpdateCategory'] == 1
                || $session['isAllowedToPublishArticle'] == 1 || $session['isAllowedToDeleteArticle'] == 1 || $session['isAllowedToManageShopCategory'] == 1
                || $session['isAllowedToCreateRights'] == 1 || $session['isAllowedToUpdateRights'] == 1 || $session['isAllowedToDeleteUser'] == 1
                || $session['isAllowedToValidatePost'] == 1 || $session['isAllowedToAddComment'] == 1 || $session['isAllowedToEditOtherComment'] == 1
                || $session['isAllowedToDeleteOtherComment'] == 1 || $session['isAllowedToValidateComment'] == 1 || $session['isPorteOuverte'] == 1
                || $session['isClickAsso'] == 1

                /* ^ Rajouter des droits ^ */
            ) {


                return true;

            }
            else {

                return false;

            }
        }
        if ($tag == 'manageScheduler') {
            if ($session['isAllowToManageScheduler'] == 1) {

                return true;

            } else {

                return false;
            }
        }
        if ($tag == 'addBlog') {
            if ($session['isAllowedToAddPost'] == 1) {

                return true;

            } else {

                return false;
            }
        }
        if ($tag == 'publishBlog') {
            if ($session['isAllowedToPublishPost'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'editOtherBlog') {
            if ($session['isAllowedToEditOtherPost'] == 1) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'deleteOtherBlog') {
            if ($session['isAllowedToDeleteOtherPost'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'validateBlog') {
            if ($session['isAllowedToValidatePost'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'editSlider') {
            if ($session['isAllowedToEditSlider'] == True) {

                return true;

            } else {

                return false;
            }
        }

        // _________________________________________________
        //
        // BLOG Comments Parts
        // _________________________________________________

        if ($tag == 'editOtherComment') {
            if ($session['isAllowedToEditOtherComment'] == 1) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'deleteOtherComment') {
            if ($session['isAllowedToDeleteOtherComment'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'addComment') {
            if ($session['isAllowedToAddComment'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'validateComment') {
            if ($session['isAllowedToValidateComment'] == True) {

                return true;

            } else {

                return false;
            }
        }

        // _________________________________________________
        //
        // BLOG Cat Parts
        // _________________________________________________


        if ($tag == 'addBCat') {
            if ($session['isAllowedToAddBlogCategory'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'editBCat') {
            if ($session['isAllowedToUpdateCategory'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'deleteBCat') {
            if ($session['isAllowedToDeleteCategory'] == True) {

                return true;

            } else {

                return false;
            }
        }

        //_______________________________________________
        //
        // USER & Right
        //
        //_______________________________________________

        if ($tag == 'createRight') {
            if ($session['isAllowedToCreateRights'] == True) {

                return true;

            } else {

                return false;
            }
        }
        if ($tag == 'updateRight') {
            if ($session['isAllowedToUpdateRights'] == True) {

                return true;

            } else {

                return false;
            }
        }
        if ($tag == 'manageFamily') {
            if ($session['isAllowedToManageFamily'] == True) {

                return true;

            } else {

                return false;
            }

        }
        if ($tag == 'editUser'){
            if ($session['isAllowedToEditUser'] == True) {

                return true;

            } else {

                return false;
            }
        }
        if ($tag == 'viewUser'){
            if ($session['isAllowedToViewUser'] == True) {

                return true;

            } else {

                return false;
            }
        }
        if ($tag == 'deleteUser'){
            if ($session['isAllowedToDeleteUser'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'manageGroup') {
            if ($session['isAllowedToManageGroups'] == True) {

                return true;

            } else {

                return false;
            }
        }
        if ($tag == 'manageUserGroup') {
            if ($session['isAllowedToManageUserGroups'] == True) {

                return true;

            } else {

                return false;
            }
        }




        // _________________________________________________
        //
        // Shop articles Parts
        // _________________________________________________

        if ($tag == 'addArticle') {
            if ($session['isAllowedToPublishArticle'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'deleteArticle') {
            if ($session['isAllowedToDeleteArticle'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'manageCategory') {
            if ($session['isAllowedToManageShopCategory'] == True) {

                return true;

            } else {

                return false;
            }
        }

        // _________________________________________________
        //
        // Bills and paiement Parts
        // _________________________________________________

        if ($tag == 'manageBills') {
            if ($session['isAllowedToManageBills'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'viewBills') {
            if ($session['isAllowedToViewBills'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'changeArticle') {
            if ($session['isAllowedToChangeArticle'] == True) {

                return true;

            } else {

                return false;
            }
        }

        if ($tag == 'viewPastArticle') {
            if ($session['isAllowedToViewPastArticle'] == True) {

                return true;

            } else {

                return false;
            }
        }

        // _________________________________________________
        //
        // System Part
        // _________________________________________________

        if ($tag == 'editSystem') {
            if (isset($session) && $session['isAllowedToEditSystem'] == true) {

                return true;

            } else {

                return false;
            }
        }

        //___________________________________________________
        //
        // Teacher
        //
        //___________________________________________________

        if ($tag == 'teacher') {
            if ($session['isTeacher'] == 1) {

                return true;


            } else {

                return false;
            }
        }

        if ($tag == 'PorteOuverte') {
            if ($session['isPorteOuverte'] == 1) {

                return true;


            } else {

                return false;
            }
        }
        
        if ($tag == 'ClickAsso') {
            if ($session['isClickAsso'] == 1) {

                return true;


            } else {

                return false;
            }
        }
    }
}
