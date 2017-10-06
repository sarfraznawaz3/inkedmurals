<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php  
if(empty($val->menu_link )){
	$link = "#"; }
else{
	$link = $val->menu_link;
	} 
if(empty($val->title )){
	$title = "Menu - ".$val->id; }
else{
	$title = $val->title;
	}
if(empty($val->menu_id )){
	$menu_id = ""; }
else{
	$menu_id = 'id="'.$val->menu_id.'"';
	}
     $modal = '';
	 if ($val->menu_type == 'link'){
		 $modal .= '<a href="'.$val->menu_link.'" class="wp-side-menu-item"><span>'.$title.'</span><i class="fa '.$val->menu_icon.' wo-icon" aria-hidden="true"></i></a>';
		 	 }
	 if ($val->menu_type == 'block'){
		 $modal .= '<div '.$menu_id.' class="wp-side-menu-item"><span>'.$title.'</span><i class="fa '.$val->menu_icon.' wo-icon" aria-hidden="true"></i></div>';		 
	 }	 
	 echo $modal;	 
?>