<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
if($val->herd_custom == '1' ){
	$icon = '<img src="'.$val->herd_custom_link.'"  width="35" height="35">'; 
	}
else{
	$icon = '<i class="fa '.$val->menu_icon.' mwp-heard-icon" style="color:#ffffff" aria-hidden="true"></i>';
	}	
	 $mwpherd = '';
	 $mwpherd .= '<div id="mwp-heard-'.$val->id.'">';	 
	 $mwpherd .= '<div class="mwp-heard-close" id="heard-close-'.$val->id.'">X</div>';     
	 $mwpherd .= '<div class="mwp-heard-block">';
	 $mwpherd .= '<div class="mwp-heard-img">'.$icon.'</div>';
	 $mwpherd .= '<div class="mwp-heard-text" style="color:#ffffff">';
	 $mwpherd .= '<div class="mwp-heard-title">'.$val->herd_title.'</div>';	 
	 $mwpherd .= '<div id="mwp-heard-text-'.$val->id.'"></div>';
	 $mwpherd .= '</div></div></div>';		 
	 echo $mwpherd;	 
?>