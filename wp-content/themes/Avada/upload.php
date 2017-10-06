<?php
/**
 * Template Name: upload
 * This template file is used for contact pages.
 *
 * @package Avada
 * @subpackage Templates
 */

$filname= "stock-photo-colourful-paper-shopping-bags-isolated-on-white-110370773.jpg";
$post_id = 282;
$upload_file = wp_upload_bits($filename, null, @file_get_contents($file));
if(!$upload_file['error']) {
  //if succesfull insert the new file into the media library (create a new attachment post type)
  $wp_filetype = wp_check_filetype($filename, null );
  $attachment = array(
    'post_mime_type' => $wp_filetype['type'],
    'post_parent' => $post_id,
    'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
    'post_content' => '',
    'post_status' => 'inherit'
  );
  //wp_insert_attachment( $attachment, $filename, $parent_post_id );
  $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );
  if (!is_wp_error($attachment_id)) {
     //if attachment post was successfully created, insert it as a thumbnail to the post $post_id
     require_once(ABSPATH . "wp-admin" . '/includes/image.php');
     //wp_generate_attachment_metadata( $attachment_id, $file ); for images
     $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
     wp_update_attachment_metadata( $attachment_id,  $attachment_data );
     set_post_thumbnail( $post_id, $attachment_id );
   }
}
?>