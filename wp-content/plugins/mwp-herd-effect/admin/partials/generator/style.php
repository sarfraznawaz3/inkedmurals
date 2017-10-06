#mwp-heard-<?php echo $val->id; ?>{	
	padding:10px; 
	border: 0px solid #eeeeee; 
	z-index:9999999999; 
	position: fixed; 
	top:10%;
	right:-10%;
	border-radius:0px;	
	height: 80px;
	background: rgba(10,10,10,0.75);  
	box-sizing: border-box;
	font-size:12px;
	font-family:Verdana;
	opacity:0;
}
#heard-close-<?php echo $val->id; ?>{
	color: #ffffff;
}
<?php if (!empty($val->mobil_show)){ ?>
@media screen and (max-width: <?php echo $val->screen; ?>px) { 
#mwp-heard-<?php echo $val->id; ?> {display:none;}
}
<?php } ?>