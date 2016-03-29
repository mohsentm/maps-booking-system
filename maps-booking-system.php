<?php
/*
Plugin Name: maps-booking-system
Plugin URI: http://mohsentm.ir
Description: booking system on the top of google calendar
Version: 2.8.1 beta
Author: Seyed Mohsen Hosseini
Author URI: http://mohsentm.ir
License: GPL
*/

// update plugin library
require 'lib/update-checker/plugin-update-checker.php';
$className = PucFactory::getLatestClassVersion('PucGitHubChecker');
$myUpdateChecker = new $className(
	'https://github.com/mohsentm/maps-booking-system/',
	__FILE__,
	'master'
);

// plugin db connection function
require_once("lib/db_function.php");


// email function
require_once("lib/email_function.php");

// show the table booking list & event list
include_once("lib/show_table.php");


include_once("mbs_plugin.php");

include_once("lib/function.php");

include_once("ajax_control.php");


add_action( 'plugins_loaded', function () {
	MBS_Plugin::get_instance();
} );


/* Runs when plugin is activated */
register_activation_hook(__FILE__,'maps_booking_system_install');
/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'maps_booking_system_remove' );
/* Runs on plugin uninstall */
register_uninstall_hook( __FILE__, 'maps_booking_system_uninstall' );

// add the [maps_booking_system] shortcode
add_shortcode('maps_booking_system', 'maps_booking_system_shortcode');

add_action("wp_ajax_maps_booking_system_do_ajax", "do_ajax");
add_action("wp_ajax_nopriv_maps_booking_system_do_ajax", "do_ajax");
add_action("wp_ajax_maps_booking_setting_ajax", "maps_booking_setting_page_ajax");

add_action('active_reminding_mail', 'send_reminding_mail');

/**
 * This function run when the plugin activated
 */
function maps_booking_system_install() {
		
        // Create plugin table in the wordpress database
        create_plugin_table();
        // insert the default mail theme in the table
        insert_mail_default_theme();
}

/**
 * This function run when the plugin deactivation
 */
function maps_booking_system_remove() {

}
/**
 * This function run when the plugin uninstall
 */
function maps_booking_system_uninstall(){
	/* Deletes the database field */	

	// Drop plugin table in the wordpress database
	drop_plugin_table();
}

/**
 * This function is maps booking setting page in admin panel
 */
function maps_booking_setting_page_ajax(){
	if ( !is_super_admin() )
		exit;
	$Plugin_setting = new MBS_Plugin;
	$Plugin_setting->maps_booking_setting_page();
}

/**
  * This function show the calendar with shortcode in user side
  * 
  * @param int $calendar_id this the id`s of calendar 
  */
function maps_booking_system_shortcode($calendar_id) {

	include("theme/fullcalendar_user_side.php");
}

/**
 * This function response to ajax request
 */
function do_ajax(){
		
   	ajax_control();
   	die();//exit();
}
