<?php
/**
 * Single product title.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.1.0
 */

?>
<!-- <h2 itemprop="name" class="product_title entry-title"><?php //the_title(); ?></h2> -->
<h2 itemprop="name" class="product_title entry-title show-dimen">Dimensions of your mural in feet</h2>
<div id="demo">
<div class="hight_cls">
<label>Width (ft)</label>	
	<input type="number"  id="length_needed">
</div>
<div class="width_cls">
<label>Height (ft)</label>
	<input type="number" id="width_needed">
</div>
</div>
</div>

<script>

jQuery(document).ready(function(){
    jQuery('#width_needed').keyup(calculate);
    jQuery('#length_needed').keyup(calculate);

});
function calculate(e)
{
    jQuery('.addon-custom-price').val(jQuery('#width_needed').val() * jQuery('#length_needed').val());
}

</script>

