<?php
/**
 * @package RatingOn
 * @version 1.0
 */
/*
Plugin Name: Rating-On
Description: Rating on service plugin
Author: Milo Donati
Version: 1.0
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $raton_version, $installedVersion, $raton_dir, $menu_name, $raton_page_title;

$menu_name = 'Raton User Menu';
$raton_page_title = "Raton Main User Page";

$installedVersion = null;

$raton_version = '1_0';

$raton_dir = array(
    "MAIN" => plugin_dir_path(__FILE__) . "be/",
    "DB" => plugin_dir_path(__FILE__) . "be/db_update/",
    "DAO" => plugin_dir_path(__FILE__) . "be/core/dao/",
    "SERVICE" => plugin_dir_path(__FILE__) . "be/rest_service/",
    "MODEL" => plugin_dir_path(__FILE__) . "be/core/model/",
    "CONTROLLER" => plugin_dir_path(__FILE__) . "be/rest_controller/",
    "CORE" => plugin_dir_path(__FILE__) . "be/core/",
    "JS" => plugin_dir_path(__FILE__) . "fe/admin/js/",
    "FE_HTML" => plugin_dir_path(__FILE__) . "fe/admin/partial/"
);

/***
 * Esegue il file di update indicato dalla versione
 *
 * @param $version versione dal db da eseguire
 */
function install_db($version, $installedVersion) {

    global $wpdb, $raton_dir;

    require_once($raton_dir["DB"] . 'db_' . $version . ".php");
    $functionName = 'update_' . $version;

    $functionName($wpdb, $installedVersion);

}

function br_trigger_error($message, $errno) {

    if(isset($_GET['action'])
        && $_GET['action'] == 'error_scrape') {

        echo '<strong>' . $message . '</strong>';

        exit;

    } else {

        trigger_error($message, $errno);

    }

}

function raton_db_install_hook ($version, $installedVersion) {
    install_db($version, $installedVersion);
    add_option( 'raton_db_version', $version );
}

function raton_update_db_check_hook() {
    global $raton_version, $installedVersion;

    $installedVersion = get_site_option( 'raton_db_version' );
    $installedVersion_noDot = str_replace(".","_",$installedVersion);

    if ( $installedVersion_noDot != $raton_version ) {
        raton_db_install_hook ($raton_version);
    }
}

function register_api_hook() {
    global $raton_version,  $raton_dir;

    require_once($raton_dir["CONTROLLER"] . "ItemRestController.php");
    require_once($raton_dir["CONTROLLER"] . "CategoryRestController.php");
    require_once($raton_dir["CONTROLLER"] . "VoteTypeRestController.php");


    $itemRestCtrl = new ItemRestController($raton_version);
    $itemRestCtrl -> register_routes();

    $catRestCtrl = new CategoryRestController($raton_version);
    $catRestCtrl -> register_routes();

    $voteTypeCtrl = new VoteTypeRestController($raton_version);
    $voteTypeCtrl -> register_routes();
}

function deregisterScriptAndCSS() {

    wp_deregister_script("raton-app");

    wp_deregister_style("bootstrap");
    wp_deregister_style("bootstrap-theme");
    wp_deregister_style("raton");

}

function registerScriptAndCSS() {

    global $raton_version, $raton_dir;

    wp_register_script( 'bootstrap', plugins_url( '/fe/common/js/library/bootstrap/bootstrap.js', __FILE__ ), array( 'jquery'), $raton_version, true );
    wp_register_script( 'angular', plugins_url( '/fe/common/js/library/angular/angular.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), $raton_version, true );
    wp_register_script( 'angular-resource', plugins_url( '/fe/common/js/library/angular/angular-resource.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'angular-animate', plugins_url( '/fe/common/js/library/angular/angular-animate.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'angular-storage', plugins_url( '/fe/common/js/library/angular/angular-storage.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'angular-route', plugins_url( '/fe/common/js/library/angular/angular-route.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'tree-repeat', plugins_url( '/fe/common/js/library/tree-repeat.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'angular-ui', plugins_url( '/fe/common/js/library/bootstrap/ui-bootstrap-tpls-1.2.4.js', __FILE__ ), array( 'jquery','angular'), $raton_version, true );

    wp_register_script( 'raton-common', plugins_url( '/fe/common/js/raton-common-module.js', __FILE__ ), array( 'angular', 'tree-repeat'), $raton_version, true );

    wp_register_script( 'categoryCmp', plugins_url( '/fe/admin/js/directive/categoryCmp.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'confService', plugins_url( '/fe/common/js/service/confService.js', __FILE__ ), array( 'angular','raton-common'), $raton_version, true );
    wp_register_script( 'loaderService', plugins_url( '/fe/common/js/service/loaderService.js', __FILE__ ), array( 'angular','raton-common'), $raton_version, true );
    wp_register_script( 'categoryResource', plugins_url( '/fe/common/js/service/category/categoryResource.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'categoryService', plugins_url( '/fe/common/js/service/category/categoryService.js', __FILE__ ), array( 'angular','raton-app','categoryResource'), $raton_version, true );
    wp_register_script( 'categoryUtils', plugins_url( '/fe/common/js/service/category/categoryUtils.js', __FILE__ ), array( 'angular','raton-app','categoryService'), $raton_version, true );
    wp_register_script( 'itemResource', plugins_url( '/fe/common/js/service/item/itemResource.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'itemService', plugins_url( '/fe/common/js/service/item/itemService.js', __FILE__ ), array( 'angular','raton-app','itemResource'), $raton_version, true );
    wp_register_script( 'voteTypeResource', plugins_url( '/fe/common/js/service/voteType/voteTypeResource.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'voteTypeService', plugins_url( '/fe/common/js/service/voteType/voteTypeService.js', __FILE__ ), array( 'angular','raton-app','voteTypeResource'), $raton_version, true );

    wp_register_script( 'adminMainCtrl', plugins_url( '/fe/admin/js/controller/mainAdminCtrl.js', __FILE__ ), array( 'angular','raton-app','confService'), $raton_version, true );
    wp_register_script( 'categoryCtrl', plugins_url( '/fe/admin/js/controller/categoryCtrl.js', __FILE__ ), array( 'angular','raton-app','categoryService'), $raton_version, true );
    wp_register_script( 'itemCtrl', plugins_url( '/fe/admin/js/controller/itemCtrl.js', __FILE__ ), array( 'angular','raton-app','itemService'), $raton_version, true );
    wp_register_script( 'voteTypeCtrl', plugins_url( '/fe/admin/js/controller/voteTypeCtrl.js', __FILE__ ), array( 'angular','raton-app','voteTypeService','categoryService','categoryUtils'), $raton_version, true );

    wp_register_script( 'raton-app', plugins_url( '/fe/admin/js/raton-admin-app.js', __FILE__ ),
        array( 'wp-api', 'jquery', 'jquery-ui-core', 'angular', 'angular-resource' , 'angular-animate', 'angular-ui', 'tree-repeat', 'raton-common'), $raton_version, true );

    wp_register_style("bootstrap",plugins_url( '/fe/admin/css/bootstrap.css', __FILE__ ),array(),$raton_version,"all");
    wp_register_style("bootstrap-theme",plugins_url( '/fe/admin/css/bootstrap-theme.css', __FILE__ ),array('bootstrap'),$raton_version,"all");
    wp_register_style("raton",plugins_url( '/fe/admin/css/raton.css', __FILE__ ),array('bootstrap','bootstrap-theme'),$raton_version,"all");
}

function raton_admin_menu() {
    /* Add our plugin submenu and administration screen */
    $page_hook_suffix = add_submenu_page( 'options-general.php', // The parent page of this submenu
        __( 'RatingOn', 'ratingoOn' ), // The submenu title
        __( 'Rating On Settings', 'ratingoOn' ), // The screen title
        'manage_options', // The capability required for access to this submenu
        'raton-options', // The slug to use in the URL of the screen
        'raton_manage_menu' // The function to call to display the screen
    );

    /*
      * Use the retrieved $page_hook_suffix to hook the function that links our script.
      * This hook invokes the function only on our plugin administration screen,
      * see: http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix
      */
    add_action('admin_print_scripts-' . $page_hook_suffix, 'ratonEnqueueScript');
    add_action('admin_print_styles-' . $page_hook_suffix, 'ratonEnqueueCss');
}

function ratonEnqueueCss() {
    wp_enqueue_style("bootstrap");
    wp_enqueue_style("bootstrap-theme");
    wp_enqueue_style("raton");
}

function ratonEnqueueScript() {

    wp_localize_script( 'wp-api', 'WP_API_Settings',
        array(
            'root' => esc_url_raw( rest_url() ),
            'RATON_ROOT_URL' => get_home_url(),
            'RATON_FE_URL' => plugins_url( '/fe/admin', __FILE__ ),
            'nonce' => wp_create_nonce( 'wp_rest' ) ));

    wp_enqueue_script( 'bootstrap' );
    wp_enqueue_script( 'angular' );
    wp_enqueue_script( 'angular-resource' );
    wp_enqueue_script( 'angular-storage' );
    wp_enqueue_script( 'angular-animate' );
    wp_enqueue_script( 'angular-route' );
    wp_enqueue_script( 'angular-ui' );
    wp_enqueue_script( 'tree-repeat' );

    wp_enqueue_script( 'categoryCmp' );
    wp_enqueue_script( 'confService' );
    wp_enqueue_script( 'loaderService' );

    wp_enqueue_script( 'categoryResource' );
    wp_enqueue_script( 'categoryService' );
    wp_enqueue_script( 'itemResource' );
    wp_enqueue_script( 'itemService' );
    wp_enqueue_script( 'voteTypeResource' );
    wp_enqueue_script( 'voteTypeService' );
    wp_enqueue_script( 'categoryUtils' );

    wp_enqueue_script( 'adminMainCtrl' );
    wp_enqueue_script( 'categoryCtrl' );
    wp_enqueue_script( 'voteTypeCtrl' );
    wp_enqueue_script( 'itemCtrl' );
    wp_enqueue_script( 'raton-app' );
    wp_enqueue_script( 'raton-common' );
}

function raton_manage_menu() {

    global $raton_dir;

    $adminPagePath = $raton_dir["FE_HTML"] . "adminPage.html";

    readfile($adminPagePath);
}

function removeUserData() {

    // Check if the menu exists
    global $menu_name, $raton_page_title;
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if( $menu_exists) {
        wp_delete_nav_menu($menu_name);
    }

    $page_exist = get_page_by_title( $raton_page_title );
    if( $page_exist) {
        wp_delete_post($page_exist->ID, true);
    }
}

function createUserData() {

    global $menu_name, $raton_page_title;

    // Check if the menu exists
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    // If it doesn't exist, let's create it.
    if( !$menu_exists){
        $menu_id = wp_create_nav_menu($menu_name);

        // Set up default menu items
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Home'),
            'menu-item-classes' => 'home',
            'menu-item-url' => home_url( '/' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Crea item'),
            'menu-item-url' => home_url( './#createItem' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Le mie recensioni'),
            'menu-item-url' => home_url( './#myReview' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Faq'),
            'menu-item-url' => home_url( './#faq' ),
            'menu-item-status' => 'publish'));
    }

    // Create raton main page post object if no exist
    $page_exist = get_page_by_title( $raton_page_title );

    // If it doesn't exist, let's create it.
    if($page_exist == null) {

        $user_id = get_current_user_id();
        $raton_post = array(
            'post_title' => $raton_page_title,
            'post_content' => "",
            'post_status' => 'publish',
            'post_author' => $user_id,
            'post_name' => "ratonmainpost",
            'post_type' => 'page'
        );

        wp_insert_post( $raton_post );
    }


}

function raton_activation_hook() {

    raton_update_db_check_hook();
    createUserData();
}

function raton_deactivation_hook() {

    deregisterScriptAndCSS();
    removeUserData();
}

add_action( 'admin_init', 'registerScriptAndCSS' );
add_action( 'admin_menu', 'raton_admin_menu' );
add_action( 'plugins_loaded', 'raton_update_db_check_hook' );

register_activation_hook( __FILE__, 'raton_activation_hook' );
register_deactivation_hook(__FILE__, 'raton_deactivation_hook');

add_action( 'rest_api_init', function(){register_api_hook();} );