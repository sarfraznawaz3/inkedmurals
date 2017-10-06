<?php 
$variable1 = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $val->herd_name);
$variable1 = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $variable1);
$variable2 = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $val->herd_city);
$variable2 = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $variable2);
?> 
jQuery(document).ready(function($) {
	heardClick<?php echo $val->id; ?> = '';	
	stop_heard_effect<?php echo $val->id; ?> = 0;
	heard_name_<?php echo $val->id; ?> = ["<?php echo str_replace(',','","',$variable1); ?>"];
	heard_city_<?php echo $val->id; ?> = ["<?php echo str_replace(',','","',$variable2); ?>"];	
	$('#heard-close-<?php echo $val->id;?>').click( function(){
		$('#mwp-heard-<?php echo $val->id; ?>').animate({opacity: 0, right: '-10%'}, 400); 
		heardClick<?php echo $val->id; ?> = 1;
	});	
	run_heard_open_<?php echo $val->id; ?> ();
});
function run_heard_open_<?php echo $val->id; ?>() {
	if (heardClick<?php echo $val->id; ?> != 1){
	var open_heard = <?php if (empty($val->speed)) { echo '7'; } else {echo $val->speed;} ;?>*1000; 
	setTimeout(function(){
			jQuery('#mwp-heard-<?php echo $val->id; ?>').animate({opacity: 1, right: '3%'}, 400); 
			var rand_heard_name = Math.floor(Math.random() * heard_name_<?php echo $val->id; ?>.length);
		var rand_heard_city = Math.floor(Math.random() * heard_city_<?php echo $val->id; ?>.length);
		var rand_heard_number = Math.floor(Math.random() * (<?php echo $val->amount_min;?> - <?php echo $val->amount_max;?>)) + <?php echo $val->amount_max;?>;
		var heard_text = "<?php echo $val->herd_text; ?>";
		var heard_text_name = heard_text.replace("[name]", heard_name_<?php echo $val->id; ?>[rand_heard_name]);
		var heard_text_city = heard_text_name.replace("[city]", heard_city_<?php echo $val->id; ?>[rand_heard_city]);
		var heard_text_new = heard_text_city.replace("[amount]", rand_heard_number);
		jQuery('#mwp-heard-text-<?php echo $val->id; ?>').html(heard_text_new);
		run_heard_close_<?php echo $val->id; ?>();
		}, open_heard); 
		}
}
function run_heard_close_<?php echo $val->id; ?>() {
	stop_heard_effect<?php echo $val->id; ?>++;
	
		var close_heard = 7000; 
		setTimeout(function(){
			jQuery('#mwp-heard-<?php echo $val->id; ?>').animate({opacity: 0, right: '-10%'}, 400); 
			if (stop_heard_effect<?php echo $val->id; ?> < 4){
			run_heard_open_<?php echo $val->id; ?> (); 
			}
			}, close_heard); 
		
}