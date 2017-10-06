<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {

    exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
    'woocommerce-product-gallery',
    'woocommerce-product-gallery--' . $placeholder,
    'woocommerce-product-gallery--columns-' . absint( $columns ),
    'images',
) );
?>
<!-- <script type="text/javascript" src="http://php.twilightsoftwares.com:9998/murals/wp-content/themes/Avada/assets/js/jquery-1.3.2.js"></script> -->
<link href="http://staging.inkedmurals.com/wp-content/themes/Avada/assets/css/main.css" type="text/css" rel="stylesheet" />
 <link rel="stylesheet" type="text/css" href="http://staging.inkedmurals.com/wp-content/themes/Avada/assets/css/jquery.Jcrop.min.css" />
<script type="text/javascript" src="http://staging.inkedmurals.com/wp-content/themes/Avada/assets/js/jquery.Jcrop.min.js"></script> 

 


<script type="text/javascript">


function Log(data)
{
	console.log(data);
}


var kp = false;
jQuery(document).ready(function(){


jQuery(".home").attr("href", "javascript:void(0)");
jQuery(".home").children('img').attr('id', 'cropimage');

	  
	var jcrop_api;

	jQuery('#cropimage').Jcrop({
	    onChange: updatePreview,
	    // onSelect: showCoords,
	    bgFade: true,
	    bgOpacity: .2,
	    setSelect: [ 0, 0, 0, 0 ],
	    //aspectRatio: 1
	},function(){
	    jcrop_api = this;
	});
	
	// Create variables (in this scope) to hold the API and image size
	var imgVal = jQuery(".wp-post-image").attr("src"); 
	var weightVal = heightVal = '';

	var  boundx, boundy;
	var  xx,yy,xx1,yy1; 
	
	function updatePreview(c) { // croping image preview
		if( kp == false)
	 	{
			Log("on change...!");
			Log(c);
		    if (parseInt(c.w) > 0) {
		        var rx = 220 / c.w, ry = 220 / c.h; Log("rx " + rx );  Log("ry " + ry );
		    }
			jQuery('#x').val(c.x);
		 	jQuery('#y').val(c.y);
		 	
	    	jQuery('#length_needed').val(c.w);
	    	jQuery('#width_needed').val(c.h);	
        	xx = c.x;
			yy = c.y;
            xx1 = c.x2;
            yy1 = c.y2;
	 	}
		kp = false;
		jQuery(".jcrop-holder").find(".jcrop-dragbar").attr("style", "display: none !important;");
        jQuery(".jcrop-holder").find(".jcrop-handle").attr("style", "display: none !important;");
		
	}

	function showCoords(c) {  
	}

	jQuery("#length_needed").keydown(function(){


		kp =true;
		jQuery(".overLay").hide();

        setTimeout(function(){ 
        	    heightVal = jQuery("#length_needed").val();
        	    weightVal = jQuery("#width_needed").val();  
        		Log("heightVal=>"+heightVal+",weightVal=>"+weightVal);
        	    var dim = jcrop_api.getBounds(); Log(dim);
        		jcrop_api.setSelect([xx1,yy1,heightVal,weightVal]);

         }, 5);
	});


	jQuery("#width_needed").keydown(function(){
		
        setTimeout(function(){ 
        		kp =true;
        		jQuery(".overLay").hide();
        	    heightVal = jQuery("#length_needed").val();
        	    weightVal = jQuery("#width_needed").val();   
        	    Log("WW=>" + this.value);
        	    var dim = jcrop_api.getBounds(); Log(dim);
        	    Log("heightVal=>"+heightVal+",weightVal=>"+weightVal);
        	    jcrop_api.setSelect([xx1,yy1,heightVal,weightVal]);
        }, 5);
	});
/*4 combitantion opterations*/
jQuery('input[value="greyscale"]').click(function(){
	            jQuery(".jcrop-holder").children().children().children().addClass("grey");
	             if(!jQuery(this).is(":checked") 
	                ){
	                jQuery(".jcrop-holder").children().children().children().removeClass("grey");
	             }

	            jQuery('input[value="sepia"]').prop('checked', false);
	            jQuery(".jcrop-holder").children().children().children().removeClass("sep");
	            // if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%); transform: rotateY(180deg);");

	            // }else if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":not(:checked)")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%);");

	            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "transform: rotateY(180deg);");
	            // } else if(jQuery(this).is(":checked")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%);");
	            // } else if(jQuery(this).is(":not(:checked)")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "");
	            // }

	             
	            // else if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%); transform: rotateX(180deg);");
	            // } else if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":not(:checked)")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%);");
	            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "transform: rotateX(180deg);");
	            // } else if(jQuery(this).is(":checked")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%);");
	            // } else if(jQuery(this).is(":not(:checked)")){
	            //     var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "");
	            // }

	            // var xx = jQuery('#x').val();
	            // var yy = jQuery('#y').val();
	            //jcrop_api.setSelect([0,0,heightVal,weightVal]);
	        });



	       /**/
	         jQuery('input[value="sepia"]').click(function(){
	            // jQuery(".helloworld").remove();
	            jQuery(".jcrop-holder").children().children().children().addClass("sep");
	            if(!jQuery(this).is(":checked") 
	                ){
	                jQuery(".jcrop-holder").children().children().children().removeClass("sep");
	             }

	            jQuery('input[value="greyscale"]').prop('checked', false);
	            jQuery(".jcrop-holder").children().children().children().removeClass("grey");
	    //         var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "");
	    //         if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%); transform: rotateY(180deg);");
	    //         }else if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":not(:checked)")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%);");

	    //         } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "transform: rotateY(180deg);");
	    //         } else if(jQuery(this).is(":checked")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%);");
	    //         } else if(jQuery(this).is(":not(:checked)")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "");
	    //         }


	    //         else if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%); transform: rotateY(180deg);");
	    //         }

	 			// else if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%); transform: rotateX(180deg);");
	    //         } else if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":not(:checked)")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%);");
	    //         } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "transform: rotateX(180deg);");
	    //         } else if(jQuery(this).is(":checked")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%);");
	    //         } else if(jQuery(this).is(":not(:checked)")){
	    //             var imgObj = document.getElementsByClassName('helloworld')[0].setAttribute("style", "");
	    //         } 
	 

	   // jQuery(".jcrop-tracker.helloworld");
	            // heightVal = jQuery("#length_needed").val();
	            // weightVal = jQuery("#width_needed").val();

	            // jQuery('#length_needed').val(heightVal);
	            // jQuery('#width_needed').val(weightVal);

	            // var xx = jQuery('#x').val();
	            // var yy = jQuery('#y').val();
	            // jcrop_api.setSelect([0,0,heightVal,weightVal]);
	        });
         /**/
    
        jQuery('input[value="mirror-vertical"]').click(function(){
        	 if(jQuery('input[value="mirror-horizontal"]').is(":checked")){
                    jQuery(".jcrop-holder").children().children().children().addClass("mirror-hor");
                } 
                if(!jQuery('input[value="mirror-horizontal"]').is(":checked")){
                    jQuery(".jcrop-holder").children().children().children().removeClass("mirror-hor");
                }
                if(jQuery(this).is(":checked") 
                    ){
                    jQuery(".jcrop-holder").children().children().children().addClass("mirror-ver");
                }
                if(!jQuery(this).is(":checked") 
                    ){
                    jQuery(".jcrop-holder").children().children().children().removeClass("mirror-ver");
                }

	      		if(jQuery(this).is(":checked") && jQuery('input[value="mirror-horizontal"]').is(":checked")){
	  				jQuery(".jcrop-holder").children().children().children().removeClass("mirror-ver");
	  				jQuery(".jcrop-holder").children().children().children().removeClass("mirror-hor");
	  				jQuery(".jcrop-holder").children().children().children().addClass("mirror-hor-ver");
	      		} else {
	      			jQuery(".jcrop-holder").children().children().children().removeClass("mirror-hor-ver");
	      		}


            // if(jQuery(this).is(":checked") && jQuery('input[value="sepia"]').is(":checked") && jQuery('input[value="greyscale"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%); transform: rotateX(180deg);");
            // } else if(jQuery(this).is(":checked") && jQuery('input[value="sepia"]').is(":not(:checked)") && jQuery('input[value="greyscale"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "transform: rotateX(180deg)");
            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="sepia"]').is(":checked") && jQuery('input[value="greyscale"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%);");
            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="greyscale"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "");
            // }

            // else if(jQuery(this).is(":checked") && jQuery('input[value="greyscale"]').is(":checked") && jQuery('input[value="sepia"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%); transform: rotateX(180deg);");
            // } else if(jQuery(this).is(":checked") && jQuery('input[value="greyscale"]').is(":not(:checked)") && jQuery('input[value="sepia"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "transform: rotateX(180deg)");
            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="greyscale"]').is(":checked") && jQuery('input[value="sepia"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%);");
            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="sepia"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "");
            // }
        });
/**/
   
        jQuery('input[value="mirror-horizontal"]').click(function(){
             if(jQuery('input[value="mirror-vertical"]').is(":checked")){
                    jQuery(".jcrop-holder").children().children().children().addClass("mirror-ver");
                    // jQuery(".jcrop-tracker").addClass("mirror-ver");
                } 
                if(!jQuery('input[value="mirror-vertical"]').is(":checked")){
                    jQuery(".jcrop-holder").children().children().children().removeClass("mirror-ver");
                    // jQuery(".jcrop-tracker").removeClass("mirror-ver");
                }
             if(jQuery(this).is(":checked") 
                    ){
                    jQuery(".jcrop-holder").children().children().children().addClass("mirror-hor");
                	// jQuery(".jcrop-tracker").addClass("mirror-hor");
                }
                if(!jQuery(this).is(":checked") 
                    ){
                    jQuery(".jcrop-holder").children().children().children().removeClass("mirror-hor");
                	// jQuery(".jcrop-tracker").removeClass("mirror-hor");
                }

                if(jQuery(this).is(":checked") && jQuery('input[value="mirror-vertical"]').is(":checked")){
	  				jQuery(".jcrop-holder").children().children().children().removeClass("mirror-ver");
	  				jQuery(".jcrop-holder").children().children().children().removeClass("mirror-hor");
	  				jQuery(".jcrop-holder").children().children().children().addClass("mirror-hor-ver");
	  				// jQuery(".jcrop-tracker").removeClass("mirror-ver");
	  				// jQuery(".jcrop-tracker").removeClass("mirror-hor");
	  				// jQuery(".jcrop-tracker").addClass("mirror-hor-ver");
	      		} else {
	      			jQuery(".jcrop-holder").children().children().children().removeClass("mirror-hor-ver");
	      			// jQuery(".jcrop-tracker").removeClass("mirror-hor-ver");
	      		}

            // if(jQuery(this).is(":checked") && jQuery('input[value="sepia"]').is(":checked") && jQuery('input[value="greyscale"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%); transform: rotateY(180deg);");
            // } else if(jQuery(this).is(":checked") && jQuery('input[value="sepia"]').is(":not(:checked)") && jQuery('input[value="greyscale"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "transform: rotateY(180deg)");
            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="sepia"]').is(":checked") && jQuery('input[value="greyscale"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "-webkit-filter: sepia(100%);filter: sepia(100%);");
            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="greyscale"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "");
            // }

            // else if(jQuery(this).is(":checked") && jQuery('input[value="greyscale"]').is(":checked") && jQuery('input[value="sepia"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%); transform: rotateY(180deg);");
            // } else if(jQuery(this).is(":checked") && jQuery('input[value="greyscale"]').is(":not(:checked)") && jQuery('input[value="sepia"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "transform: rotateY(180deg)");
            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="greyscale"]').is(":checked") && jQuery('input[value="sepia"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "-webkit-filter: grayscale(100%); filter: grayscale(100%);");
            // } else if(jQuery(this).is(":not(:checked)") && jQuery('input[value="sepia"]').is(":not(:checked)")){
            //     var imgObj = document.getElementsByClassName('wp-post-image')[0].setAttribute("style", "");
            // }
        });

	        
	    
	        

});

</script>
<style type="text/css">
    

input#x,
input#y,
input#x2,
input#y2{display: none;}


.grey{
-webkit-filter: grayscale(100%); filter: grayscale(100%);
}
.sep{
-webkit-filter: sepia(100%);filter: sepia(100%);
}
.mirror-ver{
transform: rotateX(180deg);
}
.mirror-hor{
transform: rotateY(180deg);
}
.mirror-hor-ver{
transform: rotateX(180deg) rotateY(180deg);	
}

</style>


 
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <figure class="woocommerce-product-gallery__wrapper" style="width: 100% !important">
        <?php
        $attributes = array(
            'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
            'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
            'data-src'                => $full_size_image[0],
            'data-large_image'        => $full_size_image[0],
            'data-large_image_width'  => $full_size_image[1],
            'data-large_image_height' => $full_size_image[2],
            'id' => 'YOUR-UNIQUE-ID',
        );
        // if ( has_post_thumbnail() ) {
             $html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a class="home" href="' . esc_url( $full_size_image[0] ) . '">';

        // echo '<pre>'; print_r($attributes);echo '</pre>';
             $html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
             $html .= '</a></div>';

        // } else {
        //     $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
        //     $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image helloworld" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
        //     $html .= '</div>';
        // }

        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

        do_action( 'woocommerce_product_thumbnails' );
        ?>
        <div class="overLay" style="cursor: not-allowed;">
        <div class="overSec">
        	
        </div>
        <div class="dimension">You must set the dimensions.</div></div>

    </figure> 

</div>
 <div>
     <p class="onceprinter">Please note that Bigstock watermark will not appear once printed.</p>
 </div>

 <?php
 $id = get_the_ID();
 //echo "testadfadfasf";
// echo $id;
if ($id == '282')
{
?>
 <script>
jQuery(document).ready(function(){
	jQuery(".jcrop-holder").remove();
	jQuery(".summary-container").hide();
});
</script>
<?php }?>
 <script>
jQuery(document).ready(function(){
jQuery("#length_needed").on('input', function() {
   
    var lengthParseInt = parseInt(jQuery("#length_needed").val());
    jQuery("#length_needed").val(lengthParseInt);
    
    }); 
jQuery("#width_needed").on('input', function() {
   
    var widthParseInt = parseInt(jQuery("#width_needed").val());
    jQuery("#width_needed").val(widthParseInt);
    
    }); 
});
</script>
 