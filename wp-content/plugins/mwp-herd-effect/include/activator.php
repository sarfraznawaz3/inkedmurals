<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
global $wpdb;
$wpdb->mwp_herd_free = $wpdb->prefix . 'mwp_herd_free';
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$sql = "CREATE TABLE " . $wpdb->mwp_herd_free . " (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  herd_title TEXT,
  herd_custom TEXT,
  menu_icon TEXT,
  herd_custom_link TEXT,
  herd_text TEXT,
  herd_name TEXT,
  herd_city TEXT,
  amount_min TEXT,
  amount_max TEXT,
  speed TEXT,
  mobil_show TEXT,
  screen TEXT,
  UNIQUE KEY id (id)
) DEFAULT CHARSET=utf8;";
dbDelta($sql);
?>
