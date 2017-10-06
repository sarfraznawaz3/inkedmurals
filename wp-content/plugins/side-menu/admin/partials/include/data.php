<?php
$wowpage = "side-menu";
$act = (isset($_REQUEST["act"])) ? $_REQUEST["act"] : '';
if ($act == "update") {
    $recid = $_REQUEST["id"];
    $result = $wpdb->get_row("SELECT * FROM $data WHERE id=$recid");
    if ($result){
        $id = $result->id;
        $title = $result->title;
		$menu_type = $result->menu_type;
		$menu_link = $result->menu_link;
		$menu_id = $result->menu_id;
		$menu_icon = $result->menu_icon;
		$menu_order = $result->menu_order;
        $btn = __("Update Item", "wow-marketings");
        $hidval = 2;
    }
} else {
    $btn = __("Add New Item", "wow-marketings");
    $id = "";
    $title = "";
	$menu_type = "";
	$menu_link = "";
	$menu_id = "";
	$menu_icon = "";
	$menu_order = "";
    $hidval = 1;
}
?>