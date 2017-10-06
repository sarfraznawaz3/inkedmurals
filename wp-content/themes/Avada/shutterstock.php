<?php

/**
 * Template Name: shutterstock
 * This template file is used for contact pages.
 *
 * @package Avada
 * @subpackage Templates
 */
$productId = $_POST['imgid'];
$productimg = $_POST['imgurl'];
  // 'post_title' => $productId,
$post = array(
   'comment_status' => 'closed',
   'post_author' => 1,
   'post_date' => date('Y-m-d H:i:s'),
   'post_status' => 'publish',
   'post_title' => $productId,
   'post_content' => $productimg,
   'post_type' => 'product' // custom type
);

//wp_insert_post($post); 
$post_id = wp_insert_post($post);

  $term = get_term_by('name', 'custom mural', 'product_cat');

  wp_set_object_terms($post_id, $term->term_id, 'product_cat');

// echo "<pre>";
// print_r($post);
// exit


wp_set_object_terms($post_id, 'simple', 'product_type' );
// Setting the product price
update_post_meta($post_id, '_price', 150 );
update_post_meta($post_id, '_regular_price', 150 );
update_post_meta($post_id, 'fifu_image_url', $productimg);

//update_post_meta( $post_id, '_gform-form-title', 'shutterimage');
//update_post_meta( $post_id, '_gform-form-id', 1); 
//update_post_meta( $post_id, '_gravity_form_data', 'a:15:{s:2:"id";s:1:"1";s:13:"display_title";b:0;s:19:"display_description";b:0;s:25:"disable_woocommerce_price";s:2:"no";s:12:"price_before";s:0:"";s:11:"price_after";s:0:"";s:20:"disable_calculations";s:2:"no";s:22:"disable_label_subtotal";s:2:"no";s:21:"disable_label_options";s:2:"no";s:19:"disable_label_total";s:2:"no";s:14:"disable_anchor";s:2:"no";s:14:"label_subtotal";s:8:"Subtotal";s:13:"label_options";s:7:"Options";s:11:"label_total";s:5:"Total";s:8:"use_ajax";s:2:"no";}'); 
//update_post_meta( $post_id,'_product_addons', 'a:0:{}');
//wp_set_object_terms ($post_id, 'shutterimage', '_gform-form-title');
//wp_set_object_terms ($post_id, 1, '_gform-form-id');


//$post_id = 362;
// $post_id = 756;
// $image_url = $productimg;

// $upload_dir = wp_upload_dir();
// $image_data = file_get_contents($image_url);
// $filename = basename($image_url);
// if(wp_mkdir_p($upload_dir['path']))
//     $file = $upload_dir['path'] . '/' . $filename;
// else
//     $file = $upload_dir['basedir'] . '/' . $filename;
// file_put_contents($file, $image_data);

// $wp_filetype = wp_check_filetype($filename, null );
// $attachment = array(
//     'post_mime_type' => $wp_filetype['type'],
//     'post_title' => sanitize_file_name($filename),
//     'post_content' => '',
//     'post_status' => 'inherit'
// );
// $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
// require_once(ABSPATH . 'wp-admin/includes/image.php');
// $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
// wp_update_attachment_metadata( $attach_id, $attach_data );

// set_post_thumbnail( $post_id, $attach_id );

?>

