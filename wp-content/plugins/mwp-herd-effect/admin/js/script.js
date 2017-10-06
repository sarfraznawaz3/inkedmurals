jQuery(document).ready(function($) {
	//* Vertical table
	$('.tab-nav li:first').addClass('select'); 
	$('.tab-panels>div').hide().filter(':first').show();    
	$('.tab-nav a').click(function(){
		$('.tab-panels>div').hide().filter(this.hash).show(); 
		$('.tab-nav li').removeClass('select');
		$(this).parent().addClass('select');
		return (false); 
	})
	var menu = $('#menu_icon').val();
	$("#font_icon [value='"+menu+"']").attr("selected", "selected");
	$('#font_icon').fontIconPicker({
		theme: 'fip-darkgrey',
		emptyIcon: false,
		allCategoryText: 'Show all'
	});
	itemcustom();	
	wpscreen();
});
function itemcustom(){   
	if (jQuery('#herd_custom').prop('checked')){
		jQuery('#herd_custom_block').css('display','');
		jQuery('#iconstandart').css('display','none');	  	   
	}
	else{
		jQuery('#herd_custom_block').css('display','none');
		jQuery('#iconstandart').css('display','');
	}    
}
//* Show screen
function wpscreen(){
	
	if (jQuery('[name="mobil_show"]').is(':checked')){
		jQuery('#screen').css('display', '');
	}
	else {
		jQuery('#screen').css('display', 'none');
	}
}