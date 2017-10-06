<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
global $wpdb;
    $table_menu = $wpdb->prefix . "sidemenu";
    $sql = "DROP TABLE IF EXISTS $table_menu;";
    $wpdb->query($sql);    
	delete_option("smenu_verify");
	delete_option("style_side_menu");
?>