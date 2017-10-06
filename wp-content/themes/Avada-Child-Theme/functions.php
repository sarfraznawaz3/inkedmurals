<?php



function theme_enqueue_styles() {

    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );

}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



function avada_lang_setup() {

	$lang = get_stylesheet_directory() . '/languages';

	load_child_theme_textdomain( 'Avada', $lang );

}

add_action( 'after_setup_theme', 'avada_lang_setup' );





function add_custom_script(){

?>

<script>

    jQuery('.gform_variation_wrapper').each(function () {

   jQuery(this).insertBefore(jQuery(this).prev('#price_calculator'));

});

  jQuery('#price_calculator').insertAfter(jQuery('.single_variation').next());

</script>

<?php 
add_filter('gform_form_post_get_meta', 'gform_form_post_get_meta', 10, 1);
 
function gform_form_post_get_meta($value) {
 
    if (isset($value['fields']) && is_array($value['fields'])) {
        foreach ($value['fields'] as $id => $data) {
            if (isset($data->label) && isset($data->id)) {
                $value['fields'][$id]->label = apply_filters( 'wpml_translate_single_string', $data->label, "gravity_form-{$data->id}", "field-{$data->id}-label" );
            }
        }
    }
 
    return $value;
}
?>



<?php

}

 
add_action('wp_footer', 'add_custom_script');
 
