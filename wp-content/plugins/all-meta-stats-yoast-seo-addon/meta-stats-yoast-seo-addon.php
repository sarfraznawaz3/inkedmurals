<?php
/*
Plugin Name: All Meta Stats Yoast SEO Addon
Plugin URI: http://www.netattingo.com/
Description: We have developed add-on for Yoast SEO plugin that enhances the current feature to next level.
Author: NetAttingo Technologies
Version: 1.0.0
Author URI: http://www.netattingo.com/
*/


//initilize constant
define('MSYSA_DIR', plugin_dir_path(__FILE__));
define('MSYSA_URL', plugin_dir_url(__FILE__));
define('MSYSA_PAGE_DIR', plugin_dir_path(__FILE__).'pages/');
define('MSYSA_INCLUDE_URL', plugin_dir_url(__FILE__).'includes/');

//Include menu and assign page
function msysa_plugin_menu() {
	add_menu_page("Meta Stats Yoast", "Meta Stats Yoast", "administrator", "keyword-stats", "msysa_plugin_pages", 'dashicons-analytics' ,30);
	add_submenu_page("keyword-stats", "Keywords Stats", "Keywords Stats", "administrator", "keyword-stats", "msysa_plugin_pages");
	add_submenu_page("keyword-stats", "Descriptions Stats", "Descriptions Stats", "administrator", "description-stats", "msysa_plugin_pages");
	add_submenu_page("keyword-stats", "Titles Stats", "Titles Stats", "administrator", "title-stats", "msysa_plugin_pages");
	add_submenu_page("keyword-stats", "About Us", "About Us", "administrator", "about-us", "msysa_plugin_pages");
}
add_action("admin_menu", "msysa_plugin_menu");

function msysa_plugin_pages() {

   $itm = MSYSA_PAGE_DIR.$_GET["page"].'.php';
   include($itm);
}

//add admin css
function msysa_admin_css() {
  wp_register_style('admin_css', plugins_url('includes/admin-style.css',__FILE__ ));
  wp_enqueue_style('admin_css');
}
add_action( 'admin_init','msysa_admin_css');



?>