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

global $raton_version, $installedVersion, $raton_dir;

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
    "JS" => plugin_dir_path(__FILE__) . "fe/js/",
    "FE_HTML" => plugin_dir_path(__FILE__) . "fe/partial/"
);

require_once($raton_dir["CORE"] . 'Capabilities.php');

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
    require_once($raton_dir["CONTROLLER"] . "FilterTypeRestController.php");
    require_once($raton_dir["CONTROLLER"] . "FilterRestController.php");
    require_once($raton_dir["CONTROLLER"] . "VoteTypeRestController.php");


    $itemRestCtrl = new ItemRestController($raton_version);
    $itemRestCtrl -> register_routes();

    $catRestCtrl = new CategoryRestController($raton_version);
    $catRestCtrl -> register_routes();

    $filterTypeCtrl = new FilterTypeRestController($raton_version);
    $filterTypeCtrl -> register_routes();

    $filterCtrl = new FilterRestController($raton_version);
    $filterCtrl -> register_routes();

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

    wp_register_script( 'angular', plugins_url( '/fe/js/library/angular/angular.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), $raton_version, true );
    wp_register_script( 'angular-resource', plugins_url( '/fe/js/library/angular/angular-resource.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'angular-route', plugins_url( '/fe/js/library/angular/angular-route.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'tree-repeat', plugins_url( '/fe/js/library/tree-repeat.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'angular-ui', plugins_url( '/fe/js/library/bootstrap/ui-bootstrap-tpls-1.2.4.js', __FILE__ ), array( 'jquery','angular'), $raton_version, true );

    wp_register_script( 'categoryCtrl', plugins_url( '/fe/js/controller/categoryCtrl.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'categoryCmp', plugins_url( '/fe/js/directive/categoryCmp.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'confService', plugins_url( '/fe/js/service/confService.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'loaderService', plugins_url( '/fe/js/service/loaderService.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'categoryResource', plugins_url( '/fe/js/service/category/categoryResource.js', __FILE__ ), array( 'angular','raton-app'), $raton_version, true );
    wp_register_script( 'categoryService', plugins_url( '/fe/js/service/category/categoryService.js', __FILE__ ), array( 'angular','raton-app','categoryResource'), $raton_version, true );
    wp_register_script( 'categoryUtils', plugins_url( '/fe/js/service/category/categoryUtils.js', __FILE__ ), array( 'angular','raton-app','categoryService'), $raton_version, true );


    wp_register_script( 'raton-app', plugins_url( '/fe/js/raton-admin-app.js', __FILE__ ),
        array( 'wp-api', 'jquery', 'jquery-ui-core', 'angular', 'angular-resource' , 'angular-ui', 'tree-repeat'), $raton_version, true );

    wp_register_style("bootstrap",plugins_url( '/fe/css/bootstrap.css', __FILE__ ),array(),$raton_version,"all");
    wp_register_style("bootstrap-theme",plugins_url( '/fe/css/bootstrap-theme.css', __FILE__ ),array('bootstrap'),$raton_version,"all");
    wp_register_style("raton",plugins_url( '/fe/css/raton.css', __FILE__ ),array('bootstrap','bootstrap-theme'),$raton_version,"all");
}

function raton_admin_menu() {
    /* Add our plugin submenu and administration screen */
    $page_hook_suffix = add_submenu_page( 'tools.php', // The parent page of this submenu
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
            'RATON_FE_URL' => plugins_url( '/fe', __FILE__ ),
            'nonce' => wp_create_nonce( 'wp_rest' ) ));

    wp_enqueue_script( 'angular' );
    wp_enqueue_script( 'angular-resource' );
    wp_enqueue_script( 'angular-route' );
    wp_enqueue_script( 'angular-ui' );
    wp_enqueue_script( 'tree-repeat' );

    wp_enqueue_script( 'categoryCtrl' );
    wp_enqueue_script( 'categoryCmp' );
    wp_enqueue_script( 'confService' );
    wp_enqueue_script( 'loaderService' );

    wp_enqueue_script( 'categoryResource' );
    wp_enqueue_script( 'categoryService' );
    wp_enqueue_script( 'categoryUtils' );

    wp_enqueue_script( 'raton-app' );
}

function raton_manage_menu() {

    global $raton_dir;

    $adminPagePath = $raton_dir["FE_HTML"] . "adminPage.html";

    readfile($adminPagePath);
}

function raton_activation_hook() {

    raton_update_db_check_hook();

    Capabilities::addCapabilites();
}

function raton_deactivation_hook() {

    Capabilities::removeCapabilites();

    deregisterScriptAndCSS();
}

add_action( 'admin_init', 'registerScriptAndCSS' );
add_action( 'admin_menu', 'raton_admin_menu' );
add_action( 'plugins_loaded', 'raton_update_db_check_hook' );

register_activation_hook( __FILE__, 'raton_activation_hook' );
register_deactivation_hook(__FILE__, 'raton_deactivation_hook');

add_action( 'rest_api_init', function(){register_api_hook();} );