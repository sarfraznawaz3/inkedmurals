<?php

/**

 * The footer template.

 *

 * @package Avada

 * @subpackage Templates

 */



// Do not allow directly accessing this file.

if ( ! defined( 'ABSPATH' ) ) {

	exit( 'Direct script access denied.' );

}

?>

					<?php do_action( 'avada_after_main_content' ); ?>



				</div>  <!-- fusion-row -->

			</div>  <!-- #main -->

			<?php do_action( 'avada_after_main_container' ); ?>



			<?php global $social_icons; ?>



			<?php if ( false !== strpos( Avada()->settings->get( 'footer_special_effects' ), 'footer_sticky' ) ) : ?>

				</div>

			<?php endif; ?>



			<?php

			/**

			 * Get the correct page ID.

			 */

			$c_page_id = Avada()->fusion_library->get_page_id();

			?>



			<?php

			/**

			 * Only include the footer.

			 */

			?>

			<?php if ( ! is_page_template( 'blank.php' ) ) : ?>

				<?php $footer_parallax_class = ( 'footer_parallax_effect' == Avada()->settings->get( 'footer_special_effects' ) ) ? ' fusion-footer-parallax' : ''; ?>



				<div class="fusion-footer<?php echo esc_attr( $footer_parallax_class ); ?>">



					<?php

					/**

					 * Check if the footer widget area should be displayed.

					 */

					$display_footer = get_post_meta( $c_page_id, 'pyre_display_footer', true );

					?>

					<?php if ( ( Avada()->settings->get( 'footer_widgets' ) && 'no' !== $display_footer ) || ( ! Avada()->settings->get( 'footer_widgets' ) && 'yes' === $display_footer ) ) : ?>

						<?php $footer_widget_area_center_class = ( Avada()->settings->get( 'footer_widgets_center_content' ) ) ? ' fusion-footer-widget-area-center' : ''; ?>



						<footer role="contentinfo" class="fusion-footer-widget-area fusion-widget-area<?php echo esc_attr( $footer_widget_area_center_class ); ?>">

							<div class="fusion-row">

								<div class="fusion-columns fusion-columns-<?php echo esc_attr( Avada()->settings->get( 'footer_widgets_columns' ) ); ?> fusion-widget-area">

									<?php

									/**

									 * Check the column width based on the amount of columns chosen in Theme Options.

									 */

									$footer_widget_columns = Avada()->settings->get( 'footer_widgets_columns' );

									$footer_widget_columns = ( ! $footer_widget_columns ) ? 1 : $footer_widget_columns;

									$column_width = ( '5' == Avada()->settings->get( 'footer_widgets_columns' ) ) ? 2 : 12 / $footer_widget_columns;

									?>



									<?php

									/**

									 * Render as many widget columns as have been chosen in Theme Options.

									 */

									?>

									<?php for ( $i = 1; $i < 7; $i++ ) : ?>

										<?php if ( $i <= Avada()->settings->get( 'footer_widgets_columns' ) ) : ?>

											<div class="fusion-column<?php echo ( Avada()->settings->get( 'footer_widgets_columns' ) == $i ) ? ' fusion-column-last' : ''; ?> col-lg-<?php echo esc_attr( $column_width ); ?> col-md-<?php echo esc_attr( $column_width ); ?> col-sm-<?php echo esc_attr( $column_width ); ?>">

												<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'avada-footer-widget-' . $i ) ) : ?>

													<?php

													/**

													 * All is good, dynamic_sidebar() already called the rendering.

													 */

													?>

												<?php endif; ?>

											</div>

										<?php endif; ?>

									<?php endfor; ?>



									<div class="fusion-clearfix"></div>

								</div> <!-- fusion-columns -->

							</div> <!-- fusion-row -->

						</footer> <!-- fusion-footer-widget-area -->

					<?php endif; // End footer wigets check. ?>



					<?php

					/**

					 * Check if the footer copyright area should be displayed.

					 */

					$display_copyright = get_post_meta( $c_page_id, 'pyre_display_copyright', true );

					?>

					<?php if ( ( Avada()->settings->get( 'footer_copyright' ) && 'no' !== $display_copyright ) || ( ! Avada()->settings->get( 'footer_copyright' ) && 'yes' === $display_copyright ) ) : ?>

						<?php $footer_copyright_center_class = ( Avada()->settings->get( 'footer_copyright_center_content' ) ) ? ' fusion-footer-copyright-center' : ''; ?>



						<footer id="footer" class="fusion-footer-copyright-area<?php echo esc_attr( $footer_copyright_center_class ); ?>">

							<div class="fusion-row">

								<div class="fusion-copyright-content">



									<?php

									/**

									 * Footer Content (Copyright area) avada_footer_copyright_content hook.

									 *

									 * @hooked avada_render_footer_copyright_notice - 10 (outputs the HTML for the Theme Options footer copyright text)

									 * @hooked avada_render_footer_social_icons - 15 (outputs the HTML for the footer social icons)..

									 */

									do_action( 'avada_footer_copyright_content' );

									?>



								</div> <!-- fusion-fusion-copyright-content -->

							</div> <!-- fusion-row -->

						</footer> <!-- #footer -->

					<?php endif; // End footer copyright area check. ?>

					<?php

					// Displays WPML language switcher inside footer if parallax effect is used.

					if ( defined( 'ICL_SITEPRESS_VERSION' ) && 'footer_parallax_effect' === Avada()->settings->get( 'footer_special_effects' ) ) {

						global $wpml_language_switcher;

						$slot = $wpml_language_switcher->get_slot( 'statics', 'footer' );

						if ( $slot->is_enabled() ) {

							echo wp_kses_post( $wpml_language_switcher->render( $slot ) );

						}

					}

					?>

				</div> <!-- fusion-footer -->

			<?php endif; // End is not blank page check. ?>

		</div> <!-- wrapper -->



		<?php

		/**

		 * Check if boxed side header layout is used; if so close the #boxed-wrapper container.

		 */

		$page_bg_layout = ( $c_page_id ) ? get_post_meta( $c_page_id, 'pyre_page_bg_layout', true ) : 'default';

		?>

		<?php if ( ( ( 'Boxed' === Avada()->settings->get( 'layout' ) && 'default' === $page_bg_layout ) || 'boxed' === $page_bg_layout ) && 'Top' !== Avada()->settings->get( 'header_position' ) ) : ?>

			</div> <!-- #boxed-wrapper -->

		<?php endif; ?>

		<?php if ( ( ( 'Boxed' === Avada()->settings->get( 'layout' ) && 'default' === $page_bg_layout ) || 'boxed' === $page_bg_layout ) && 'framed' === Avada()->settings->get( 'scroll_offset' ) && 0 !== intval( Avada()->settings->get( 'margin_offset', 'top' ) ) ) : ?>

			<div class="fusion-top-frame"></div>

			<div class="fusion-bottom-frame"></div>

			<?php if ( 'None' !== Avada()->settings->get( 'boxed_modal_shadow' ) ) : ?>

				<div class="fusion-boxed-shadow"></div>

			<?php endif; ?>

		<?php endif; ?>

		<a class="fusion-one-page-text-link fusion-page-load-link"></a>



		<?php wp_footer(); ?>



		<?php

		/**

		 * Echo the scripts added to the "before </body>" field in Theme Options.

		 * The 'space_body' setting is not sanitized.

		 * In order to be able to take advantage of this,

		 * a user would have to gain access to the database

		 * in which case this is the least on your worries.

		 */

		// @codingStandardsIgnoreLine

		echo Avada()->settings->get( 'space_body' );

		?>

	</body>
<script>        
//jQuery('.closify-image').on('change', function () {
//    alert("testing");
//    jQuery('#uploadFile').val(this.value);});  


//jQuery('.single_add_to_cart_button').click(function(){
//  alert("testing");
//  alert(jQuery('.closify-image').attr('src'));
//  var image_url = jQuery('.closify-image').attr('src');
//   jQuery('.addon-custom').val(image_url);
//   alert("assign");
//   jQuery('submit').trigger('click');
//  document.cookie = "imgurl="+image_url;
//});

	
 </script>  

 <script type="text/javascript">
// alert(1);
 // calculate header and footer height
 // jQuery('.addon-checkbox').click(function(){

 // var htmlstring = jQuery("#product-addons-total").html();
 // console.log(htmlstring);
 
 // jQuery("#price_calculator").children().append(htmlstring);

 // });
              
                var foothgt = $(".fusion-footer").height();
                var hdrhgt = $(".fusion-header-wrapper").height();
                 // alert(hdrhgt);
                var screenhgt = $(window).height() - foothgt - hdrhgt - 2;
                $("#main").css({"min-height": screenhgt});

                var modal = document.getElementById('myModal');
            var img = document.getElementById('myImg');
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            var image_id ;
            $(".thumbnails").on("click", "a", function (e) {
                $("#myModal").attr("style", "display:block");
                
                var img_id = $(this).attr("href");
                var removehash = img_id.substring(1, img_id.length);
                               
                
//                image_id = (".thumbnail").attr("href");
//                alert(image_id);
                //   alert($(".thumbnail").find("img").attr("src"));
                //   alert($(this).children("img").attr('src'));
                var imgsrc = $(this).children("img").attr('src');
                $("#img01").attr("src", imgsrc);
                //    modalImg.src = this.src;
                //  s
                //$("#caption").children("a").attr('href',imgsrc);
                $("#caption").children("a").attr('rel', imgsrc);
                $("#caption").children("a").attr('id', removehash);
                //text()
            });
            var span = document.getElementsByClassName("close")[0];
         // change to your account id at bigstock.com/partners
         var account_id = '883610';
         var light_account_id = '293876';
         var auth_key = 'dcd27497c1c647d3b2def053b48de87a2cb36155';
         var selected_category, search_term, page, max_page, jsonp_happening, limit, order, select_value,exclude,photos,illustrations,orientation,safesearch,vector;
         var textBox = "";
         //"api.bigstockphoto.com/2/" + light_account_id + "/lightbox/?auth_key=[auth_key]"
         
         $( document ).ready(function() {
            // $('ul.appends').append($('.moveLast'))

			
        });

              function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
} 
    
  jQuery(document).ready(function(){
    jQuery(".stepsImages").each(function() {
      if(!jQuery(this).hasClass("finished")){
        jQuery(this).attr("style", "cursor: not-allowed;");
      }
    });
  });
  function imgpopup()
{
$("#myModal").attr("style","display:none");
}



function testing()
   {
       jQuery('#caption').on("click", "a", function () {
           //alert(jQuery(this).attr('rel'));
           var big_url = jQuery(this).attr('rel');
           var img_url = jQuery(this).attr('rel');
           var imgid = jQuery(this).attr('id');
           //alert(imgid);
           //alert(big_url);
           // alert("select the mural image");
           localStorage.setItem("imgurl", big_url);
           if (img_url)
           {

               jQuery.ajax({
                   type: 'POST',
                   data: {'imgid': imgid, 'imgurl': img_url},
                   url: "http://staging.inkedmurals.com/product-details/",
                   success: function (value) {
                       // window.location.href = "http://php.twilightsoftwares.com:9998/murals/product/"+data.id;

                       window.location.href = "http://staging.inkedmurals.com/product/"+imgid;
                   }
               });
           }


       });
   }
         
$(function () {

// $(".appends li:last-child").append($('.moveLast'));
var qstr = getParameterByName('q'); 
if(qstr)
{
// alert("Testing");
// alert(qstr);
 $("#search_text, #search_text2").val(qstr);

 //$(".searhOverall").trigger('click');
populate_search();
  //$("#search_btn").trigger('click'); 
}
         

// $(".appends li:last-child").append($('.moveLast'));



$('.openClos, .popularDrop, .item-template ').hide();
$('#beforeSearch').show();
$('#afterSearch, #pagination').hide();    
$("#category").hide();
  //$(".thumbnails").on("click", "a", function (e) {
   // $("#loading").attr('style','display:block');
// setTimeout(function(){location.href="http://php.twilightsoftwares.com:9998/murals/product/mural/"} ,   //5000);   
  //});
  //           $("#category").click(function(){
  //         $("#loader").attr('style','display:block');
  //       setTimeout(function(){} , 5000);   
  // });

  $(".search_results_number").hide();
             // open search modal on page load
             $("#search-form").modal({
               backdrop: 'static'
           });

             // populate the categories
             $.getJSON("http://api.bigstockphoto.com/2/" + account_id + "/categories/?callback=?", function (data) {
               if (data && data.data) {
                   var ival = 1;
                   $.each(data.data, function (i, v) {
                       if (v.name == "Sexual") {
                           return;
                       }
                       $("#categories ul").append("<li class='pad0A content'><a href='#' class='box overlay'><img src='http://staging.inkedmurals.com/wp-content/uploads/images/" + v.name  + ".jpg'><span class='image-caption'>" + v.name  + "</span><span class='desc overlay-toggle'></span></a></li>");

                       $('#category').append($('<option>', {
                           value: v.name,
                           text: v.name
                       }));

                       ival++;
                   });

               }
           });

             // when the user clicks on a category
             $("#categories").on("click", "a", function (e) {
               selected_category = $(this).text();
                  $("#search_text2").val($(this).text());
		localStorage.setItem("q_string", selected_category);
                 $(".form-search").trigger("submit", {
                   category: true
               });

             });

             // show a loading message when the search button is clicked
             $("html").on("submit", ".form-search", function (e, val) {
               page = 1;
               textBox = $("#limit").val();
               limit = $("#limit").val();

               var results = $("#results");
               results.html("")
               results.append("<div id=\"loading\"><div class=\"dot\"></div><div class=\"dot\"></div><div class=\"dot\"></div><div class=\"dot\"></div></div>");
              
               var val = val || {};

                 //check if the user selected a category or did a keyword search
                 if (val.category) {
                   search_term = "";
               } else {
                   selected_category = "";
                   search_term = $(".search-query").val();
                   if(search_term == ""){
                    $(".search-query").val($("#search_text2").val());
			localStorage.setItem("q_string",$(".search-query").val());
                }
                search_term = $(".search-query").val();
                console.log(search_term);
            }
            image_value = $("#images_shape").val();
                    image_value1 = $("#images_shape1").val();
                 //start the search
                 $("html").trigger("bigstock_search", {
                   q: search_term,
                   category: selected_category
               });
                 $("#categories").hide();
                 $("#results-holder").show('medium');
                 $("#category-link").show();
                 $("#pagination").show();
                 $(".search_results_number, .item-template, .popularDrop").show();
                 $("#category, #afterSearch").show();
                 $("#search_text, #beforeSearch").hide();
                 $('.appends').hide();
                 $("#search_text").val($("#search_text2").val());
                 e.preventDefault();
             })

         });

         // populate the search results
         $("html").on("bigstock_search", function (e, val) {
            // alert (val.q);
            	var order_value,image_value,safese;
            if (!jsonp_happening) {
               jsonp_happening = true;

               
                
                var val = val || {};
                    val.page = val.page || 1;
                    val.limit = 50;
//                    alert(image_value);

                    order_value = $("#popular").val();
                    safese = $("#safesearch").val();
                    if (safese == "y")
                    {
                        val.safesearch = 'y';
                    }

                    if (safese == "n")
                    {
                        val.safesearch = 'n';
                    }

                    // alert("ordervalue");
                    //alert(order_value);
                    var orient_value;
                    orient_value = $("#orientation").val();
                    // alert(orient_value);
                    if (orient_value == "h")
                    {
                        // alert("horizontal");
                        val.orientation = "h";
                    }
                    if (orient_value == "v")
                    {
                        // alert("vertical");
                        val.orientation = "v";
                    }
                    if (order_value == "new")
                    {
                        val.order = "new";
                    }

                    if (order_value == "relevant")
                    {
                        val.order = "relevant";
                    }

                    if (order_value == "popular")
                    {
                        val.order = "popular";
                    }


                    val.exclude = $("#exclude").val();


//            alert(image_value);

                    //                    alert($("#exclude").val());
                    //                    val.order = $(".popular_value").val();
                    //                    val.order = "New";




//                 if (image_value == "photos")
//                    {
//                        alert("val.photos");
//                        val.photos = $("#images_shape").val();
//                         alert("params:photos");
//                        params.photos = 'photos';
//                    }




                    var results = $("#results");

                    //setup the paramaters for the JSONP request
                    var params = {};
                    if (val.category != "")
                    {
                        params.category = val.category;
                    }
                    params.q = val.q;
                    /*my code*/
                    params.page = val.page;
                    params.limit = val.limit;
                    params.exclude = val.exclude;

                    if (val.order != "") {
                        params.order = val.order;
                    }

  if (val.orientation!= "") {
    params.orientation = val.orientation;
    }
//if(image_value=="photos")
//{
//    alert("");
//}




if ((image_value!= "photos") || (image_value1!= "photos"))
{
                    if (image_value == "vector")
                    {
                        // alert("val1.vector");
                        val.vector = $("#images_shape").val();
//                        alert("params:vector");
                        params.vector = 'y';
//                         val.vector ="y";
                    }

                    if (image_value == "illustrations")
                    {
                     // alert("val1.illustrations");
                        val.illustrations = $("#images_shape").val();
//                        val.illustrations = "y";

//                        alert("params:illustrations");
                        params.illustrations = 'y';

                    }



                    if ($('#categories').css('display') == 'none')
                    {
                     if (image_value1 == "vector")
                        {
                           // alert("val2.vector");
                            val.vector = $("#images_shape1").val();
//                            alert("params:vector");
                            params.vector = 'y';
//                         val.vector ="y";
                        }

                        if (image_value1 == "illustrations")
                        {
                           // alert("val2.illustrations");
                            val.illustrations = $("#images_shape1").val();
//                        val.illustrations = "y";

//                            alert("params:illustrations");
                            params.illustrations = 'y';

                        }
                    }
//                    }
                
                    params.safesearch = val.safesearch;

//                    if (val.vector == "vector")
//                    {
//                        alert("params:vector");
//                        params.vector = 'vector';
//                    }
//                    if (val.illustrations == "illustrations")
//                    {
//                        alert("params:illustrations");
//                        params.illustrations = 'illustrations';
//                    }
//                    if (val.photos == "photos")
//                    {
//                        alert("params:photos");
//                        params.photos = 'photos';
                    }

if ($('#categories').css('display') != 'none')
{
if ((image_value== "photos"))
{
    // alert("bestphto1");
//    params.vector = null;
   delete params.vector;
//    params.illustrations = null;
    delete params.illustrations;
    console.log(params);
}
}

if ($('#categories').css('display') == 'none')
{
if ((image_value1=="photos"))
{
    // alert("bestphto2");
//  ss params.category = $("#search_text2").val();
  delete params.vector;
//  delete params.q;
//    params.illustrations = null;
    delete params.illustrations;
    console.log(params);
}
}
console.log("final params");
console.log(params);
                    $.getJSON("http://api.bigstockphoto.com/2/" + account_id + "/search/?callback=?&response_detail=all", params, function (data) {       



                       results.find("#loading").remove();


                       results.find("#oops").remove();

                       if (data && data.data && data.data.images) {
                           /*my code*/
                         //max_page = data.data.paging.total_pages;
         //                        alert(data.data.paging.total_pages);
         max_page = 1;
         /*my code*/
         var template = $(".item-template");
         $.each(data.data.images, function (i, v) {
           template.find("img").attr("src", v.preview.url);
           template.find("a").attr("href", "#" + v.id);
         //                            template.find("a").attr("href",v.id);
         results.append(template.html())
         
         var total_results = data.data.paging.total_pages;
         
         $(".search_results_number").val(total_results);
         
     });
     } else {
       results.append("<li id=\"oops\"><div class=\"alert alert-error\">OOOPS! We found no results. Please try another search.</div></li>");
   }

   jsonp_happening = false;
});
                }
            })
         
         // when a user clicks on a thumbnail
         //        $("#results").on("click", "a", function(e) {
         //            $.getJSON("http://api.bigstockphoto.com/2/" + account_id + "/image/" + $(this).attr("href").substring(1) + "/?callback=?", function(data) {
         //                if (data && data.data && data.data.image) {
         //                    var detail_template = $(".detail-template");
         //                    detail_template.find("img").attr("src", data.data.image.preview.url);
         //                    detail_template.find("h3").html(data.data.image.title);
         //                    $(".detail-template").modal({
         //                        backdrop: false
         //                    });
         //                    e.preventDefault();
         //                }
         //            });
         //        });
         
         
         
         //  $("#results").on("click", "a", function(e) {
         //      //alert("testing");
         //      var img_url = $('#img_disp').attr('src');
         //       if(img_url)
         //       {
         //           alert(img_url);
         //            jQuery.ajax({
         //              type:'POST',            
         //              data:{'imgid':1,'imgurl':img_url},
         //              url:"http://php.twilightsoftwares.com:9998/murals/product-details/",
         //              success: function(value) {
         //              // window.location.href = "http://php.twilightsoftwares.com:9998/murals/product/"+data.id;
         //              
         //              window.location.href = "http://php.twilightsoftwares.com:9998/murals/product/mural/";          
         //              
         //            }
         //            
         //          });   
         //      }  
         //   });
         // when a user clicks on the "select this image" button
         $(".detail-template").on("click", ".btn-primary", function (e) {
           alert('Here you will need server-side code to purchase and download the un-watermarked image. See the documentation at http://bigstock.com/partners/')
       });
         
         // when a user clicks "browse by category"
         $("#category-link").click(function (e) {
           $("#results-holder, #category-link, #pagination").hide();
           $("#categories").show('medium');
           e.preventDefault();
       });
         
         // Categories change on the search result page
         
         $('#category').on('change', function () {
           var id = $(this).attr("id");
           select_value = $(this).val(); 
		 localStorage.setItem("q_string", select_value);
           search_term = "";
           $("#search_text, #search_text2").val(select_value);
           populate_search();
       });
         
         
         
         // Order Revelant or new 
         
  // Order Revelant or new 
         
//          $('#popular').on('change', function () { 
//              var q_value = localStorage.getItem("q_string");
//              alert(q_value);
       
//        select_value = $(this).val();
//         query_string = q_value + "," + select_value;
//         $("#search_text, #search_text2").val(query_string);
        
        
// var str1 = query_string;
// var str2 = "new";
// var str3 = "relevant";  

// if(str1.indexOf(str2) != -1){
// alert(str2 + " found");
// select_value = $(this).val();
// var query_string1 = q_value.remove(str2);
// alert(query_string1);
// }

// if(str1.indexOf(str3) != -1){
// alert(str3 + " found");
// select_value = $(this).val();
// var query_string1 = q_value.remove(str3);
// alert(query_string1);
// }
// localStorage.getItem("q_string","");
// });
                
         
         
         
     //     $('#orientation').on('change', function () {
     //     //                alert("testing");
     //     select_value = $(this).val();   
     //     //                alert(selected_category);
     //     var query_string = selected_category + ",";
     //     $("#search_text, #search_text2").val(query_string + select_value);
     //     //                alert($("#search_text").val());
     //     populate_search();
         
         
     // });
         
         
         $('#exclude').on('change', function () {
           var exc = $(this).val();
         //        alert(exc);
     });
         
         
   
          

       </script>   
       <script>
           $("#next").click(function () {
                jQuery('#results').html('');
                var bla = $('#page_val_disp').html();
                // infinite scroll
                myFunction_add(bla);

            });

            $("#previous").click(function () {
                jQuery('#results').html('');
                var bla = $('#page_val_disp').html();
                // infinite scroll
                myFunction_sub(bla);

            });


            function myFunction_add(x) {
                // page++;
                var page_val = parseInt(x) + 1;
                $('#page_val_disp').html(page_val);
                 $('#page_val_disp1').html(page_val);

                $("html").trigger("bigstock_search", {
                    q: search_term,
                    category: selected_category,
                    page: page_val
                })


            }

            function myFunction_sub(x) {

                // page++;
                var page_val = parseInt(x) - 1;
                if (page_val <= 0)
                {
                    page_val = 1;
                    $('#page_val_disp').html(page_val);
                     $('#page_val_disp1').html(page_val);
                }
                else
                {
                    $('#page_val_disp').html(page_val);
                     $('#page_val_disp1').html(page_val);
                }
                $("html").trigger("bigstock_search", {
                    q: search_term,
                    category: selected_category,
                    page: page_val
                })
            }
        $("#next1").click(function () {
                jQuery('#results').html('');
                var bla = $('#page_val_disp1').html();
                // infinite scroll
                myFunction1_add(bla);

            });

            $("#previous1").click(function () {
                jQuery('#results').html('');
                var bla = $('#page_val_disp1').html();
                // infinite scroll
                myFunction1_sub(bla);

            });


            function myFunction1_add(x) {
                // page++;
                var page_val = parseInt(x) + 1;
                $('#page_val_disp1').html(page_val);
                $('#page_val_disp').html(page_val);

                $("html").trigger("bigstock_search", {
                    q: search_term,
                    category: selected_category,
                    page: page_val
                })


            }

            function myFunction1_sub(x) {

                // page++;
                var page_val = parseInt(x) - 1;
                if (page_val <= 0)
                {
                    page_val = 1;
                    $('#page_val_disp1').html(page_val);
                    $('#page_val_disp').html(page_val);
                }
                else
                {
                    $('#page_val_disp1').html(page_val);
                     $('#page_val_disp').html(page_val);
                }
                $("html").trigger("bigstock_search", {
                    q: search_term,
                    category: selected_category,
                    page: page_val
                })
            }   
            
       function populate_search()
       {
           page = 1;
           var results = $("#results");
           results.html("")
           results.append("<li id=\"loading\"><span class=\"label\">Loading...</span></li>");
           var val = val || {};
           if (val.category) {
               search_term = "";
           } else {
               search_term = $(".search-query").val();
           }
           $("html").trigger("bigstock_search", {
               q: search_term,
               category: selected_category
           });
           $("#categories").hide();
           $("#results-holder").show('medium');
           $("#category-link").show();
           $("#pagination").show();
           $(".search_results_number").show();
           $("#category").show();
           $("#search_text").hide();
	$('.bigstock-img').hide();
  $('#beforeSearch').hide();

           e.preventDefault();
       }
       $(".riteFiltr").click(function(){
           $(".openClos").slideToggle();
       });

 // function testing()
 //            {
 //                // localStorage.setItem("bar", foo); 
 //                $('#caption').on("click", "a", function () {
 //                    alert($(this).attr('rel'));
 //                    //var big_url = $(this).attr('href');
 //                    var big_url = $(this).attr('rel');
 //                    alert(big_url);
 //                    localStorage.setItem("imgurl", big_url);
 //                    window.location.href = "http://php.twilightsoftwares.com:9998/muraldesign/product/mural/";
 //                });
 //            }

   </script>     
        
        
</html>

