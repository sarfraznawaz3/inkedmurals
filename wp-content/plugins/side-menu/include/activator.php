<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	global $wpdb;
    global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');	
	$table = $wpdb->prefix . 'sidemenu';	
	$sql = "CREATE TABLE " . $table." (	
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	title VARCHAR(200) NOT NULL,
	menu_type TEXT,
	menu_link TEXT,
	menu_id  TEXT,
	menu_icon  TEXT,
	menu_order  TEXT,				
	UNIQUE KEY id (id)
	) DEFAULT CHARSET=utf8;";
	
	dbDelta($sql);
	
	$style_side_menu = array(
	'position' => 'left'		
	);
	add_option('style_side_menu', $style_side_menu);
?>