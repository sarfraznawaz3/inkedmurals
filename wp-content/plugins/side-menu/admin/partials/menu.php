<?php
	global $wpdb;
	$table_menu = $wpdb->prefix . "sidemenu";
	$info = (isset($_REQUEST["info"])) ? $_REQUEST["info"] : '';
	if ($info == "saved") {
		echo "<div class='updated' id='message'><p><strong>". __("Item Add", "wow-marketings")."</strong>.</p></div>";
	}
	if ($info == "update") {
		echo "<div class='updated' id='message'><p><strong>". __("Record Updated", "wow-marketings")."</strong>.</p></div>";
	}
	if ($info == "del") {
		$delid = $_GET["did"];
		$wpdb->query("delete from " . $table_menu . " where id=" . $delid);
		echo "<div class='updated' id='message'><p><strong>". __("Record Deleted", "wow-marketings")."</strong>.</p></div>";
	}
	$resultat = $wpdb->get_results("SELECT * FROM " . $table_menu . " order by id asc");
	$count = count($resultat);
?>
<div class="wow">
	<h1><?php esc_attr_e("Side Menu", "wow-marketings") ?> <a href='https://www.facebook.com/wowaffect/' target="_blank" title="Join us on Facebook"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></h1>
	<ul class="wow-admin-menu">
		<li><a href='admin.php?page=side-menu'><?php esc_attr_e("List", "wow-marketings") ?></a></li>
		<li>
			<?php if($count<3){?>
				<a href='admin.php?page=side-menu&wow=add' ><?php esc_attr_e("Add New", "wow-marketings") ?></a>
			<?php } ?>
		</li>
		<li><a href='admin.php?page=side-menu&wow=style'><?php esc_attr_e("Style", "wow-marketings") ?></a></li>
		<li><a href='admin.php?page=side-menu&wow=discount'><b><?php esc_attr_e("Pro version", "wow-marketings") ?></b></a></li>
		<li><a href='admin.php?page=side-menu&wow=items'><b><?php esc_attr_e("Free Plugins", "wow-marketings") ?></b></a></li>
		<li><a href='admin.php?page=side-menu&wow=faq'><b><?php esc_attr_e("FAQ", "wow-marketings") ?></b></a></li>
		
	</ul>	