<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
function show_side_menu() { 
$options = get_option('style_side_menu');   	
    global $wpdb;    
	$table_menu = $wpdb->prefix . "sidemenu";
    $sSQL = "select * from $table_menu ORDER BY menu_order ASC";
    $arrresult = $wpdb->get_results($sSQL); 
    if (count($arrresult) > 0 && count($arrresult) < 4) {
		echo '<div class="wp-side-menu">';
        foreach ($arrresult as $key => $val) {
			if ($options['position'] == 'left'){			
			include( 'partials/left.php' );	
			}
			else {
				include( 'partials/right.php' );
			}
        }
		echo '</div>';
    }	
	return;
}
add_action( 'wp_footer', 'show_side_menu');
function side_menu_head() {
	wp_enqueue_script( 'side-menu-js', plugin_dir_url( __FILE__ ) . 'js/side-menu.js');
	wp_enqueue_style( 'side-menu-main-css', plugin_dir_url( __FILE__ ) . 'css/style.css');
	$options = get_option('style_side_menu');
	if ($options['position'] == 'left'){
		wp_enqueue_style( 'side-menu-css', plugin_dir_url( __FILE__ ) . 'css/left.css');
	}
	else {
		wp_enqueue_style( 'side-menu-css', plugin_dir_url( __FILE__ ) . 'css/right.css');
	}
	
	wp_enqueue_style( 'side-menu-font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome/css/font-awesome.min.css');
	
}
add_action( 'wp_head', 'side_menu_head');