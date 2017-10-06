/* global wc_bundles_admin_params */
/* global woocommerce_admin_meta_boxes */

jQuery( function($) {

	var $bundled_products_panel       = $( '#bundled_product_data' ),
		$bundled_product_input        = $( '#bundled_product', $bundled_products_panel );

	$.fn.wc_bundles_select2 = function() {
		$( document.body ).trigger( 'wc-enhanced-select-init' );
	};

	// Bundle type move stock msg up.
	$( '.bundle_stock_msg' ).appendTo( '._manage_stock_field .description' );

	// Hide the default "Sold Individually" field.
	$( '#_sold_individually' ).closest( '.form-field' ).addClass( 'hide_if_bundle' );

	// Hide the "Grouping" field.
	$( '#linked_product_data .grouping.show_if_simple, #linked_product_data .form-field.show_if_grouped' ).addClass( 'hide_if_bundle' );

	// Simple type options are valid for bundles.
	$( '.show_if_simple:not(.hide_if_bundle)' ).addClass( 'show_if_bundle' );

	// Bundle type specific options.
	$( 'body' ).on( 'woocommerce-product-type-change', function( event, select_val, select ) {

		if ( select_val === 'bundle' ) {

			$( '.show_if_external' ).hide();
			$( '.show_if_bundle' ).show();

			$( 'input#_manage_stock' ).change();

			$( '#_nyp' ).change();

			if ( wc_bundles_admin_params.is_wc_version_gte_2_7 === 'no' ) {
				$( '#_regular_price' ).val( $( '#_wc_pb_base_regular_price' ).val() ).change();
				$( '#_sale_price' ).val( $( '#_wc_pb_base_sale_price' ).val() ).change();
			}
		}

	} );

	$( 'select#product-type' ).change();

	// Downloadable support.
	$( 'input#_downloadable' ).change( function() {
		$( 'select#product-type' ).change();
	});

	init_bundled_products_panel();

	// Subsubsub navigation.
	$( '#wc-bundle-metaboxes-wrapper-inner' ).on( 'click', '.subsubsub a', function() {

		$( this ).closest( '.subsubsub' ).find( 'a' ).removeClass( 'current' );
		$( this ).addClass( 'current' );

		$( this ).closest( '.wc-bundled-item' ).find( '.options_group' ).addClass( 'options_group_hidden' );

		var tab = $( this ).data( 'tab' );

		$( this ).closest( '.wc-bundled-item' ).find( '.options_group_' + tab ).removeClass( 'options_group_hidden' );

		return false;

	} );

	function index_bundled_product_rows() {
		$( '.wc-bundled-items .wc-bundled-item' ).each( function( index, el ) {
			$( '.item_menu_order', el ).val( index );
		} );
	}

	function toggle_item_visibility( $item ) {

		var visibility_classes = [ 'product', 'cart', 'order' ],
			visible_exists     = false;

		$.each( visibility_classes, function( index, visibility_class ) {
			if ( $item.find( 'input.visibility_' + visibility_class ).is( ':checked' ) ) {
				$item.find( 'input.price_visibility_' + visibility_class ).css( 'opacity', 1 );
				visible_exists = true;
			} else {
				$item.find( 'input.price_visibility_' + visibility_class ).css( 'opacity', 0.5 );
			}
		} );
	}

	function init_bundled_products_panel() {

		$( '.wc-bundled-items' )

		// Priced individually.
		.on( 'change', '.priced_individually input', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( 'div.item-data' ).find( '.discount' ).show();
				$( this ).closest( 'div.item-data' ).find( '.price_visibility' ).show();
			} else {
				$( this ).closest( 'div.item-data' ).find( '.discount' ).hide();
				$( this ).closest( 'div.item-data' ).find( '.price_visibility' ).hide();
			}
		} )

		// Variation filtering options.
		.on( 'change', '.override_variations input', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( 'div.item-data' ).find( 'div.allowed_variations' ).show();
			} else {
				$( this ).closest( 'div.item-data' ).find( 'div.allowed_variations' ).hide();
			}
		} )

		// Selection defaults options.
		.on( 'change', '.override_default_variation_attributes input', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( 'div.item-data' ).find( 'div.default_variation_attributes' ).show();
			} else {
				$( this ).closest( 'div.item-data' ).find( 'div.default_variation_attributes' ).hide();
			}
		} )

		// Custom title options.
		.on( 'change', '.override_title input', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( 'div.item-data' ).find( 'div.custom_title' ).show();
			} else {
				$( this ).closest( 'div.item-data' ).find( 'div.custom_title' ).hide();
			}
		} )

		// Custom description options.
		.on( 'change', '.override_description input', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( 'div.item-data' ).find( 'div.custom_description' ).show();
			} else {
				$( this ).closest( 'div.item-data' ).find( 'div.custom_description' ).hide();
			}
		} )

		// Visibility.
		.on( 'change', 'input.visibility_product', function() {

			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( 'div.item-data' ).find( '.override_title, .override_description, .hide_thumbnail' ).show();
				$( this ).closest( 'div.item-data' ).find( '.override_title input' ).change();
				$( this ).closest( 'div.item-data' ).find( '.override_description input' ).change();
			} else {
				$( this ).closest( 'div.item-data' ).find( '.override_title, .custom_title, .override_description, .custom_description, .hide_thumbnail' ).hide();
			}

			toggle_item_visibility( $( this ).closest( 'div.item-data' ) );
		} )

		.on( 'change', 'input.visibility_cart, input.visibility_order', function() {
			toggle_item_visibility( $( this ).closest( 'div.item-data' ) );
		} );

		$( '.wc-bundled-items .priced_individually input' ).change();
		$( '.wc-bundled-items .override_variations input' ).change();
		$( '.wc-bundled-items .override_default_variation_attributes input' ).change();
		$( '.wc-bundled-items .override_title input' ).change();
		$( '.wc-bundled-items .override_description input' ).change();
		$( '.wc-bundled-items input.visibility_product' ).change(); // Also calls 'toggle_item_visibility'.

		// Initial order.
		var bundled_items = $( '.wc-bundled-items' ).find( '.wc-bundled-item' ).get();

		bundled_items.sort( function( a, b ) {
		   var compA = parseInt( $(a).attr( 'rel' ) );
		   var compB = parseInt( $(b).attr( 'rel' ) );
		   return ( compA < compB ) ? -1 : ( compA > compB ) ? 1 : 0;
		} );

		$( bundled_items ).each( function( idx, itm ) {
			$( '.wc-bundled-items' ).append( itm );
		} );

		// Item ordering.
		$( '.wc-bundled-items' ).sortable( {
			items:'.wc-bundled-item',
			cursor:'move',
			axis:'y',
			handle: 'h3',
			scrollSensitivity:40,
			forcePlaceholderSize: true,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wc-metabox-sortable-placeholder',
			start:function( event, ui ){
				ui.item.css( 'background-color','#f6f6f6' );
			},
			stop:function( event, ui ){
				ui.item.removeAttr( 'style' );
				index_bundled_product_rows();
			}
		} );

		index_bundled_product_rows();

		// Remove.
		$( '.wc-bundle-metaboxes-wrapper', $bundled_products_panel ).on( 'click', 'a.remove_row', function( e ) {

			var $parent = $( this ).closest( '.wc-bundled-item' );

			$parent.find('*').off();
			$parent.remove();
			index_bundled_product_rows();

			e.preventDefault();
		} );

		// Expand & Close.
		$( '.expand_all', $bundled_products_panel ).click( function() {
			$( this ).closest( '.wc-metaboxes-wrapper' ).find( '.wc-metabox > .item-data' ).show();
			return false;
		} );

		$( '.close_all', $bundled_products_panel ).click( function() {
			$( this ).closest( '.wc-metaboxes-wrapper' ).find( '.wc-metabox > .item-data').hide();
			return false;
		} );
	}

	// Add Product.
	var bundle_metabox_count = $( '.wc-bundled-items .wc-bundled-item', $bundled_products_panel ).length;
	var block_params         = {
			message: 	null,
			overlayCSS: {
				background: '#fff',
				opacity: 	0.6
			}
		};

	$bundled_products_panel.on( 'click', 'button.add_bundled_product', function() {

		var bundled_product_id = $bundled_product_input.val();

		if ( ! bundled_product_id > 0 ) {
			$bundled_product_input.select2( 'open' );
			return false;
		} else {
			if ( wc_bundles_admin_params.is_wc_version_gte_2_7 === 'yes' ) {
				$bundled_product_input.val( '' ).change();
			} else {
				$bundled_product_input.select2( 'val', '' );
			}
		}

		$bundled_products_panel.block( block_params );

		bundle_metabox_count++;

		var data = {
			action: 	'woocommerce_add_bundled_product',
			post_id: 	woocommerce_admin_meta_boxes.post_id,
			id: 		bundle_metabox_count,
			product_id: bundled_product_id,
			security: 	wc_bundles_admin_params.add_bundled_product_nonce
		};

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function ( response ) {

			if ( response.markup !== '' ) {

				$( '.wc-bundled-items', $bundled_products_panel ).append( response.markup );
				index_bundled_product_rows();

				var $added = $( '.wc-bundled-items .wc-bundled-item', $bundled_products_panel ).last();

				$added.find( '.override_variations input' ).change();
				$added.find( '.override_default_variation_attributes input' ).change();
				$added.find( '.override_title input' ).change();
				$added.find( '.override_description input' ).change();

				toggle_item_visibility( $added );

				$added.find( '.woocommerce-help-tip' ).tipTip( {
					'attribute' : 'data-tip',
					'fadeIn' : 50,
					'fadeOut' : 50,
					'delay' : 200
				} );

				$added.wc_bundles_select2();

				$bundled_products_panel.trigger( 'wc-bundles-added-bundled-product' );

			} else if ( response.message !== '' ) {
				window.alert( response.message );
			}

			$bundled_products_panel.unblock();

		} );

		return false;

	} );

} );
