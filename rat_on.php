<?php
/**
 * @package RatingOn
 * @version 1.0
 */
/*
Plugin Name: Rating-On
Description: Rating on service plugin
Author: Milo Donati, Danilo Caruso
Version: 1.0
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $raton_version, $installedVersion, $raton_dir;

$installedVersion = null;

$raton_version = '1_0';

$raton_dir = array(
    "MAIN" => plugin_dir_path(__FILE__) . "php/",
    "DB" => plugin_dir_path(__FILE__) . "php/db_update/",
    "SERVICE" => plugin_dir_path(__FILE__) . "php/rest_service/",
    "MODEL" => plugin_dir_path(__FILE__) . "php/model/",
    "CONTROLLER" => plugin_dir_path(__FILE__) . "php/rest_controller/",
    "CORE" => plugin_dir_path(__FILE__) . "php/core/",
    "JS" => plugin_dir_path(__FILE__) . "js/"
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

    (new ItemRestController($raton_version)) -> register_routes();
}

function deregisterScriptAndCSS() {

    wp_deregister_script("angular");
    wp_deregister_script("angular-resource");
    wp_deregister_script("raton-app");

}

function registerScriptAndCSS() {

    global $raton_version, $raton_dir;

    wp_localize_script( 'wp-api', 'WP_API_Settings', array( 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ) ));

    wp_register_script( 'angular', plugins_url( '/js/library/angular/angular.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), $raton_version, true );
    wp_register_script( 'angular-resource', plugins_url( '/js/library/angular/angular-resource.js', __FILE__ ), array( 'angular'), $raton_version, true );
    wp_register_script( 'raton-app', plugins_url( '/js/raton-admin-app.js', __FILE__ ),
        array( 'wp-api', 'jquery', 'jquery-ui-core', 'angular', 'angular-resource' ), $raton_version, true );


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
}

function ratonEnqueueScript() {
    wp_enqueue_script( 'angular' );
    wp_enqueue_script( 'angular-resource' );
    wp_enqueue_script( 'raton-app' );
}

function raton_manage_menu() {

    global $raton_dir;

    $adminPagePath = $raton_dir["JS"] . "views/adminPage.html";

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