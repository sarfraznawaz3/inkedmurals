<?php global $redux_builder_amp; global $wp;  ?>
<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
  <link rel="dns-prefetch" href="https://cdn.ampproject.org">
	<?php
	if ( is_archive() ) {
		$description 	= get_the_archive_description();
		$sanitizer = new AMPFORWP_Content( $description, array(), 
			apply_filters( 'ampforwp_content_sanitizers',
				array( 
					'AMP_Style_Sanitizer' 		=> array(),
					'AMP_Blacklist_Sanitizer' 	=> array(),
					'AMP_Img_Sanitizer' 		=> array(),
					'AMP_Video_Sanitizer' 		=> array(),
					'AMP_Audio_Sanitizer' 		=> array(),
					'AMP_Iframe_Sanitizer' 		=> array(
						'add_placeholder' 		=> true,
					)
				) ) );
	}
	 ?>
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<?php
	$amp_component_scripts = $sanitizer->amp_scripts;
	if ( $sanitizer && $amp_component_scripts) {	
		foreach ($amp_component_scripts as $ampforwp_service => $ampforwp_js_file) { ?>
			<script custom-element="<?php echo $ampforwp_service; ?>"  src="<?php echo $ampforwp_js_file; ?>" async></script> <?php
		}
	}?>
	<style amp-custom>
	<?php $this->load_parts( array( 'style' ) ); ?>
	<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>
<body class="amp_home_body design_2_wrapper <?php
             if ( is_post_type_archive() ) {
                 $post_type = get_queried_object(); echo'type-'; echo $post_type->rewrite['slug']; }
             ?><?php
             // Show ID on Pages, Post, Post Type's Post
             if ( is_singular() ) { ?>singular-<?php $page_id = get_queried_object_id(); echo $page_id;
    ?><?php } ?> <?php
        // Show ID on category, tag, Author Page, Etc.
        if ( is_archive() ) { ?>archive-<?php $page_id = get_queried_object_id(); echo $page_id;
    ?><?php } ?>">
<?php do_action('ampforwp_body_beginning', $this); ?>
<?php $this->load_parts( array( 'header-bar' ) ); ?>

<?php do_action( 'ampforwp_after_header', $this ); ?>

<main>
	<?php do_action('ampforwp_post_before_loop') ?>

 	<?php if ( is_archive() ) { ?>
 		<div class="amp-wp-content amp-archive-heading">
 			<?php
 			the_archive_title( '<h3 class="page-title">', '</h3>' );

			$arch_desc 		= $sanitizer->get_amp_content();
			if( $arch_desc ) { 
				if($wp->query_vars['paged'] <= '1') {?>
					<div class="amp-wp-content taxonomy-description">
						<?php echo $arch_desc ; ?>
				  </div> <?php
				}
			} ?>
 		</div>
 		<?php
 	} ?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
		$ampforwp_amp_post_url =  trailingslashit( get_permalink() ) . AMPFORWP_AMP_QUERY_VAR ;

		$ampforwp_amp_post_url  = trailingslashit( $ampforwp_amp_post_url );

			if( in_array( "ampforwp-custom-type-amp-endpoint" , $redux_builder_amp ) ) {
				if ( $redux_builder_amp['ampforwp-custom-type-amp-endpoint']) {
				 $ampforwp_amp_post_url = trailingslashit( get_permalink() ) . '?amp';
			 }
			} ?>

		<div class="amp-wp-content amp-loop-list">
			<?php if ( has_post_thumbnail() ) { ?>
				<?php
				$thumb_id = get_post_thumbnail_id();
				$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail', true);
				$thumb_url = $thumb_url_array[0];
				?>
				<div class="home-post_image">
					<a href="<?php echo esc_url( $ampforwp_amp_post_url ); ?>">
						<amp-img
							src=<?php echo $thumb_url ?>
							<?php ampforwp_thumbnail_alt(); ?>
							<?php if( $redux_builder_amp['ampforwp-homepage-posts-image-modify-size'] ) { ?>
								width=<?php global $redux_builder_amp; echo $redux_builder_amp['ampforwp-homepage-posts-design-1-2-width'] ?>
								height=<?php global $redux_builder_amp; echo $redux_builder_amp['ampforwp-homepage-posts-design-1-2-height'] ?>
							<?php } else { ?>
								width=100
								height=75
							<?php } ?>
						></amp-img>
					</a>
				</div>
			<?php } ?>

			<div class="amp-wp-post-content">

				<h2 class="amp-wp-title"> <a href="<?php echo esc_url( $ampforwp_amp_post_url ); ?>"> <?php the_title(); ?></a></h2>

				<?php
					if(has_excerpt()){
						$content = get_the_excerpt();
					}else{
						$content = get_the_content();
					}
				?>
		        <p><?php echo wp_trim_words( strip_shortcodes( $content ) , '15' ); ?></p>

		    </div>
            <div class="cb"></div>
		</div>

	<?php endwhile;  ?>

		<div class="amp-wp-content pagination-holder">

			<div id="pagination">
				<div class="next"><?php next_posts_link( ampforwp_translation($redux_builder_amp['amp-translator-next-text'] . ' &raquo;' , 'Next' ) , 0 ) ?></div>
				<div class="prev"><?php previous_posts_link( '&laquo; '. ampforwp_translation($redux_builder_amp['amp-translator-previous-text'], 'Previous') ); ?></div>

				<div class="clearfix"></div>
			</div>
		</div>

	<?php endif; ?>
	<?php do_action('ampforwp_post_after_loop') ?>
</main>
<?php do_action( 'amp_post_template_above_footer', $this ); ?>
<?php $this->load_parts( array( 'footer' ) ); ?>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>

</html>