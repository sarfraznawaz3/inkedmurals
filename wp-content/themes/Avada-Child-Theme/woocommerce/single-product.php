<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
<div class="row mar0A">
                <div class="col-lg-4 beforeStep pad0A ">
                    <a href="http://php.twilightsoftwares.com:9998/muraldesign/bigstock/"><p class="stepsImages mar0A finished"><span>1</span> Select my image </p></a> 
                  </div>
                   <div class="col-lg-4 beforeStep pad0A ">
                    <a href="http://php.twilightsoftwares.com:9998/muraldesign/bigstock/"><p class="stepsImages mar0A"><span>2</span> Customize my mural</p></a> 
                  </div>
                   <div class="col-lg-4 beforeStep pad0A">
                    <a href="http://php.twilightsoftwares.com:9998/muraldesign/bigstock/"><p class="stepsImages mar0A"><span>3</span> Order my mural</p></a> 
                  </div>
                 
        </div>
<div class="col-lg-12">
<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12"></div>
<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 pad20T cardImg ">
	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
    
<?php // echo do_shortcode('[gravityform id=1 title="false" description="false"]'); ?>
    
	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
</div>
<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>
    </div>
    </div>
<div class="clearfix"></div>
<div class="col-lg-12">
	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
                
               
	?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		var pathname = window.location.pathname;
		if(pathname == "/muraldesign/product/mural/"){
			jQuery(".stepsImages").each(function() {
				jQuery(this).removeClass("finished");
			});
			jQuery(".stepsImages").eq(0).addClass("finished");
			jQuery(".stepsImages").eq(1).addClass("finished");
			jQuery(".stepsImages").eq(2).parent().attr("href", "javascript:void(0)");
		}

		jQuery(".stepsImages").each(function() {
			if(!jQuery(this).hasClass("finished")){
				jQuery(this).attr("style", "cursor: not-allowed;");
			}
		});
	});
</script>


<?php get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
