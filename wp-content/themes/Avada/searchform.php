<?php
/**
 * The search-form template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
  exit( 'Direct script access denied.' );
}
?>
<form role="search" class="searchform" method="get" action="<?php echo esc_url_raw( home_url( '/' ) ); ?>bigstock/">
  <div class="search-table">
    <div class="search-field">
      <input id="search_text" type="text" value="" name="q" class="s" placeholder="<?php esc_html_e( 'Search ...', 'Avada' ); ?>" required aria-required="true" aria-label="<?php esc_html_e( 'Search ...', 'Avada' ); ?>"/>
    </div>
    <div class="search-button">
      <input id="searchResult" type="submit" class="searchsubmit" value="&#xf002;" alt="<?php esc_attr_e( 'Search', 'Avada' ); ?>" />
    </div>
  </div>
</form> 
<!--  <form class="form-search beforeSearch" action="#" >
               <input id="search_text" value="" type="text" class="serchQuerys2 search-query span4" placeholder="Find the perfect image...">
<button id="submit" type="submit" class="searhOverall btn btn-primary">Search</button>
           </form> -->

  <script>
     jQuery(function () {
         //alert("testing");
      jQuery('#searchResult').on('click', function () {
       //  alert("testing"); 
       var qstring = jQuery("#search_text").val();
window.location.href = "<?php echo esc_url_raw( home_url( '/' ) ); ?>bigstock/?q=" + qstring;
//        window.location.href="www.google.com";
return false;
      });
      
  });  
    </script>