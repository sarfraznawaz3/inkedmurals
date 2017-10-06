<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
$wowpage = 'wow-herd-effects';
$act = (isset($_REQUEST["act"])) ? $_REQUEST["act"] : '';
if ($act == "update") {
	$recid = $_REQUEST["id"];
	$result = $wpdb->get_row("SELECT * FROM $data WHERE id=$recid");
	if ($result){
        $id = $result->id;
        $title = $result->title;
		$herd_title = $result->herd_title;
		$herd_custom = $result->herd_custom;
		$menu_icon = $result->menu_icon;
		$herd_custom_link = $result->herd_custom_link;
		$herd_text = $result->herd_text;		
		$herd_name = $result->herd_name;
		$herd_city = $result->herd_city;
		$amount_min = $result->amount_min;
		$amount_max = $result->amount_max;
		$speed = isset($result->speed) ? $result->speed : '7';
		$screen = isset($result->screen) ? $result->screen : '480';
		$mobil_show = isset($result->mobil_show) ? $result->mobil_show : '';
		$btn = "Update";
        $hidval = 2;
    }
}
else if ($act == "duplicate") {
	$recid = $_REQUEST["id"];
	$result = $wpdb->get_row("SELECT * FROM $data WHERE id=$recid");
	if ($result){
        $id = "";
        $title = "";
		$herd_title = $result->herd_title;
		$herd_custom = $result->herd_custom;
		$menu_icon = $result->menu_icon;
		$herd_custom_link = $result->herd_custom_link;
		$herd_text = $result->herd_text;		
		$herd_name = $result->herd_name;
		$herd_city = $result->herd_city;
		$amount_min = $result->amount_min;
		$amount_max = $result->amount_max;
		$speed = isset($result->speed) ? $result->speed : '7'; 
		$screen = isset($result->screen) ? $result->screen : '480';
		$mobil_show = isset($result->mobil_show) ? $result->mobil_show : '';
		$btn = "Save";
        $hidval = 1;
    }
}
 else {
        $btn = "Save";
        $id = "";
        $title = "";
		$herd_title = "New Order";
		$herd_custom = "";
		$menu_icon = "";
		$herd_custom_link = "";
		$herd_text = "[name] from [city] has just placed an order for $[amount].";		
		$herd_name = "";
		$herd_city = "";
		$amount_min = "1000";
		$amount_max = "2500";
		$speed = "7";
		$mobil_show = "";
		$screen = "480";
		$hidval = 1;
}
?>