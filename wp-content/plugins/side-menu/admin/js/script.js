function proverka(input) { 
    input.value = input.value.replace(/[^\d]/g, '');
};
jQuery(document).ready(function($) {
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
	changetype();	
});
function changetype(){
	var type = jQuery('[name="menu_type"]').val();
	if (type == 'link'){
		jQuery('#menu_type_link').css('display', 'block');
		jQuery('#menu_type_block').css('display', 'none');
		jQuery('#block_text').css('display', 'none');		
	}
	else{
		jQuery('#menu_type_link').css('display', 'none');
		jQuery('#menu_type_block').css('display', '');
		jQuery('#block_text').css('display', '');		
	}	
}