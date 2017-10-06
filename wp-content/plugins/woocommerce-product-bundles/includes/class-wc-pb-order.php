<?php
/**
 * WC_PB_Order class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    4.5.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Bundle order-related functions and filters.
 *
 * @class    WC_PB_Order
 * @version  5.2.4
 */
class WC_PB_Order {

	/**
	 * Flag to prevent 'woocommerce_order_get_items' filters from modifying original order line items when calling 'WC_Order::get_items'.
	 *
	 * @var boolean
	 */
	public static $override_order_items_filters = false;

	/**
	 * @var WC_PB_Order - the single instance of the class.
	 *
	 * @since 5.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main WC_PB_Order instance.
	 *
	 * Ensures only one instance of WC_PB_Order is loaded or can be loaded.
	 *
	 * @static
	 * @return WC_PB_Order - Main instance
	 * @since  5.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 5.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-product-bundles' ), '4.11.4' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 5.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-product-bundles' ), '4.11.4' );
	}

	/**
	 * Setup order class.
	 */
	protected function __construct() {

		// Filter price output shown in cart, review-order & order-details templates.
		add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'order_item_subtotal' ), 10, 3 );

		// Virtual bundle containers should not affect order status unless one of their children does.
		add_filter( 'woocommerce_order_item_needs_processing', array( $this, 'container_item_needs_processing' ), 10, 3 );

		// Modify order items to include bundle meta.
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_order_item_meta' ), 10, 3 );

		// Hide bundle configuration metadata in order line items.
		add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hidden_order_item_meta' ) );

		// Filter order item count in the front-end.
		add_filter( 'woocommerce_get_item_count', array( $this, 'order_item_count' ), 10, 3 );

		// Filter admin dashboard item count and classes.
		if ( is_admin() ) {
			add_filter( 'woocommerce_admin_order_item_count', array( $this, 'order_item_count_string' ), 10, 2 );
			add_filter( 'woocommerce_admin_html_order_item_class', array( $this, 'html_order_item_class' ), 10, 3 );
			add_filter( 'woocommerce_admin_order_item_class', array( $this, 'html_order_item_class' ), 10, 3 );
		}

		// Modify product while completing payment - @see 'get_processing_product_from_item()' and 'container_item_needs_processing()'.
		add_action( 'woocommerce_pre_payment_complete', array( $this, 'apply_get_product_from_item_filter' ) );
		add_action( 'woocommerce_payment_complete', array( $this, 'remove_get_product_from_item_filter' ) );
	}

	/*
	|--------------------------------------------------------------------------
	| API functions.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Validates a bundle configuration and adds all associated line items to an order. Relies on specifying a bundle configuration array with all necessary data.
	 * The configuration array is passed as a 'configuration' key of the $args method argument. Example:
	 *
	 *    $args = array(
	 *        'configuration' => array(
	 *            134 => array(                             // ID of bundled item.
	 *                'quantity'          => 2,             // Qty of bundled product, will fall back to min.
	 *                'discount'          => 50.0,          // Bundled product discount, defaults to the defined value.
	 *                'title'             => 'Test',        // Bundled product title, include only if overriding.
	 *                'optional_selected' => 'yes',         // If the bundled item is optional, indicate if chosen or not.
	 *                'attributes'        => array(         // Array of selected variation attribute names, sanitized.
	 *                    'attribute_color' => 'black',
	 *                    'attribute_size'  => 'medium'
	 *                 ),
	 *                'variation_id'      => 43,            // ID of chosen variation, if applicable.
	 *                'args'              => array()        // Custom bundled item args to pass into 'WC_Order::add_product()'.
	 *            )
	 *        )
	 *    );
	 *
	 * Returns the container order item ID if sucessful, or false otherwise.
	 *
	 * Note: Container/child order item totals are calculated without taxes, based on their pricing setup.
	 * - Container item totals can be overridden by passing a 'totals' array in $args, as with 'WC_Order::add_product()'.
	 * - Bundled item totals can be overridden in the 'configuration' array, as shown in the example above.
	 *
	 *
	 * @param  WC_Product_Bundle  $bundle
	 * @param  WC_Order           $order
	 * @param  integer            $quantity
	 * @param  array              $args
	 * @return integer|WP_Error
	 */
	public function add_bundle_to_order( $bundle, $order, $quantity = 1, $args = array() ) {

		$added_to_order = false;

		$args = wp_parse_args( $args, array(
			'configuration' => array(),
			'silent'        => true
		) );

		if ( $bundle && 'bundle' === $bundle->get_type() ) {

			$configuration = $args[ 'configuration' ];

			if ( WC_PB()->cart->validate_bundle_configuration( $bundle, $quantity, $configuration, 'add-to-order' ) ) {

				// Add container item.
				$container_order_item_id = $order->add_product( $bundle, $quantity, $args );
				$added_to_order          = $container_order_item_id;

				// Unique hash to use in place of the cart item ID.
				$container_item_hash = md5( $container_order_item_id );

				// Add bundled items.
				$bundled_items = $bundle->get_bundled_items();

				// Hashes of children.
				$bundled_order_item_hashes = array();

				$bundled_weight = 0.0;

				if ( ! empty( $bundled_items ) ) {
					foreach ( $bundled_items as $bundled_item_id => $bundled_item ) {

						$bundled_item_configuration  = isset( $configuration[ $bundled_item_id ] ) ? $configuration[ $bundled_item_id ] : array();
						$bundled_item_quantity       = isset( $bundled_item_configuration[ 'quantity' ] ) ? absint( $bundled_item_configuration[ 'quantity' ] ) : $bundled_item->get_quantity();
						$bundled_product             = isset( $bundled_item_configuration[ 'variation_id' ] ) && in_array( $bundled_item->product->get_type(), array( 'variable', 'variable-subscription' ) ) ? wc_get_product( $bundled_item_configuration[ 'variation_id' ] ) : $bundled_item->product;
						$bundled_item_variation_data = isset( $bundled_item_configuration[ 'attributes' ] ) && in_array( $bundled_item->product->get_type(), array( 'variable', 'variable-subscription' ) ) ? $bundled_item_configuration[ 'attributes' ] : array();
						$bundled_item_discount       = isset( $bundled_item_configuration[ 'discount' ] ) ? wc_format_decimal( $bundled_item_configuration[ 'discount' ] ) : $bundled_item->get_discount();
						$bundled_item_args           = isset( $bundled_item_configuration[ 'args' ] ) ? $bundled_item_configuration[ 'args' ] : array();

						if ( $bundled_item->is_optional() ) {

							$optional_selected = isset( $bundled_item_configuration[ 'optional_selected' ] ) && 'yes' === $bundled_item_configuration[ 'optional_selected' ] ? 'yes' : 'no';

							if ( 'no' === $optional_selected ) {
								$bundled_item_quantity = 0;
							}
						}

						if ( 0 === $bundled_item_quantity ) {
							continue;
						}

						if ( $bundled_item->is_priced_individually() ) {
							if ( $bundled_item_discount ) {
								$bundled_item_args[ 'subtotal' ]     = isset( $bundled_item_args[ 'subtotal' ] ) ? $bundled_item_args[ 'subtotal' ] : wc_get_price_excluding_tax( $bundled_product, array( 'qty' => $bundled_item_quantity * $quantity ) ) * ( 1 - (float) $bundled_item_discount / 100 );
								$bundled_item_args[ 'total' ]        = isset( $bundled_item_args[ 'total' ] ) ? $bundled_item_args[ 'total' ] : wc_get_price_excluding_tax( $bundled_product, array( 'qty' => $bundled_item_quantity * $quantity ) ) * ( 1 - (float) $bundled_item_discount / 100 );
								$bundled_item_args[ 'subtotal_tax' ] = isset( $bundled_item_args[ 'subtotal_tax' ] ) ? $bundled_item_args[ 'subtotal_tax' ] : 0;
								$bundled_item_args[ 'total_tax' ]    = isset( $bundled_item_args[ 'total_tax' ] ) ? $bundled_item_args[ 'total_tax' ] : 0;
							}
						} else {
							$bundled_item_args[ 'subtotal' ]     = isset( $bundled_item_args[ 'subtotal' ] ) ? $bundled_item_args[ 'subtotal' ] : 0;
							$bundled_item_args[ 'total' ]        = isset( $bundled_item_args[ 'total' ] ) ? $bundled_item_args[ 'total' ] : 0;
							$bundled_item_args[ 'subtotal_tax' ] = isset( $bundled_item_args[ 'subtotal_tax' ] ) ? $bundled_item_args[ 'subtotal_tax' ] : 0;
							$bundled_item_args[ 'total_tax' ]    = isset( $bundled_item_args[ 'total_tax' ] ) ? $bundled_item_args[ 'total_tax' ] : 0;
						}

						// Args to pass into 'add_product()'.
						$bundled_item_args[ 'variation' ] = $bundled_item_variation_data;

						// Add bundled item.
						$bundled_order_item_id = $order->add_product( $bundled_product, $bundled_item_quantity * $quantity, $bundled_item_args );

						if ( ! $bundled_order_item_id ) {
							continue;
						}

						// Locate the item.
						$order_items        = $order->get_items( 'line_item' );
						$bundled_order_item = $order_items[ $bundled_order_item_id ];

						/*
						 * Add bundled order item meta.
						 */

						$bundled_order_item->add_meta_data( '_bundled_by', $container_item_hash, true );
						$bundled_order_item->add_meta_data( '_stamp', $configuration, true );
						$bundled_order_item->add_meta_data( '_bundled_item_id', $bundled_item_id, true );

						if ( false === $bundled_item->is_visible( 'order' ) ) {
							$bundled_order_item->add_meta_data( '_bundled_item_hidden', 'yes', true );
						}

						if ( false === $bundled_item->is_price_visible( 'order' ) ) {
							$bundled_order_item->add_meta_data( '_bundled_item_price_hidden', 'yes', true );
						}

						if ( $bundled_item->has_title_override() ) {
							$bundled_order_item->add_meta_data( '_bundled_item_title', isset( $bundled_item_configuration[ 'title' ] ) ? $bundled_item_configuration[ 'title' ] : $bundled_item->get_raw_title(), true );
						}

						// Pricing setup.
						$bundled_order_item->add_meta_data( '_bundled_item_priced_individually', $bundled_item->is_priced_individually() ? 'yes' : 'no', true );

						// Unique hash to use in place of the cart item ID.
						$bundled_item_hash           = md5( $bundled_order_item_id );
						$bundled_order_item_hashes[] = $bundled_item_hash;

						$bundled_order_item->add_meta_data( '_bundle_cart_key', $bundled_item_hash, true );

						// Shipping setup.
						$shipped_individually = false;

						if ( $bundled_product->needs_shipping() && $bundled_item->is_shipped_individually( $bundled_product ) ) {
							$shipped_individually = true;
						} elseif ( $bundled_product->needs_shipping() ) {
							/** Hook documented in 'WC_PB_Cart::set_bundled_cart_item()'. */
							if ( apply_filters( 'woocommerce_bundled_item_has_bundled_weight', false, $bundled_product, $bundled_item_id, $bundle ) ) {
								$bundled_weight += (double) $bundled_product->get_weight( 'edit' ) * $bundled_item_quantity;
							}
						}

						$bundled_order_item->add_meta_data( '_bundled_item_needs_shipping', $shipped_individually ? 'yes' : 'no', true );

						// Save the item.
						$bundled_order_item->save();

						/**
						 * 'woocommerce_bundled_add_to_order' action.
						 *
						 * @param  int                $bundled_order_item_id
						 * @param  WC_Order           $order
						 * @param  WC_Product         $bundled_product
						 * @param  int                $bundled_item_quantity
						 * @param  WC_Bundled_Item    $bundled_item
						 * @param  WC_Product_Bundle  $bundle
						 * @param  int                $quantity
						 * @param  array              $bundled_item_args
						 * @param  array              $args
						 */
						do_action( 'woocommerce_bundled_add_to_order', $bundled_order_item_id, $order, $bundled_product, $bundled_item_quantity, $bundled_item, $bundle, $quantity, $bundled_item_args, $args );
					}
				}

				// Locate the item.
				$order_items          = $order->get_items( 'line_item' );
				$container_order_item = $order_items[ $container_order_item_id ];

				/*
				 * Add container order item meta.
				 */

				$container_order_item->add_meta_data( '_stamp', $configuration, true );
				$container_order_item->add_meta_data( '_bundled_items', $bundled_order_item_hashes, true );
				$container_order_item->add_meta_data( '_bundle_cart_key', $container_item_hash, true );

				if ( $bundle->needs_shipping() ) {
					$container_order_item->add_meta_data( '_bundle_weight', (double) $bundle->get_weight( 'edit' ) + $bundled_weight, true );
				}

				// Save the item.
				$container_order_item->save();

			} else {

				$error_data = array( 'notices' => wc_get_notices( 'error' ) );
				$message    = __( 'The submitted bundle configuration could not be added to this order.', 'woocommerce-product-bundles' );

				if ( $args[ 'silent' ] ) {
					wc_clear_notices();
				}

				$added_to_order = new WP_Error( 'woocommerce_bundle_configuration_invalid', $message, $error_data );
			}

		} else {
			$message        = __( 'A bundle with this ID does not exist.', 'woocommerce-product-bundles' );
			$added_to_order = new WP_Error( 'woocommerce_bundle_invalid', $message );
		}

		return $added_to_order;
	}

	/**
	 * Modifies bundle parent/child order items depending on their shipping setup. Reconstructs an accurate representation of a bundle for shipping purposes.
	 * Used in combination with 'get_product_from_item', right below.
	 *
	 * Adds the totals of "packaged" items to the container totals and creates a container "Contents" meta field to provide a description of the included items.
	 *
	 * @param  array     $items
	 * @param  WC_Order  $order
	 * @return array
	 */
	public function get_order_items( $items, $order ) {

		// Nobody likes infinite loops.
		if ( self::$override_order_items_filters ) {
			return $items;
		}

		// Right?
		self::$override_order_items_filters = true;

		$return_items = array();

		foreach ( $items as $item_id => $item ) {

			if ( wc_pb_is_bundle_container_order_item( $item ) ) {

				/*
				 * Add the totals of "packaged" items to the container totals and create a container "Contents" meta field to provide a description of the included products.
				 */
				$product = wc_get_product( $item->get_product_id() );

				if ( $product && $product->needs_shipping() && $child_items = wc_pb_get_bundled_order_items( $item, $items ) ) {

					if ( ! empty( $child_items ) ) {

						// Aggregate contents.
						$contents = array();

						// Aggregate prices.
						$bundle_totals = array(
							'subtotal'     => $item->get_subtotal(),
							'total'        => $item->get_total(),
							'subtotal_tax' => $item->get_subtotal_tax(),
							'total_tax'    => $item->get_total_tax(),
							'taxes'        => $item->get_taxes()
						);

						foreach ( $child_items as $child_item_id => $child_item ) {

							// If the child is "packaged" in its parent...
							if ( 'no' === $child_item->get_meta( '_bundled_item_needs_shipping', true ) ) {

								$child_variation_id = $child_item->get_variation_id();
								$child_product_id   = $child_item->get_product_id();
								$child_id           = $child_variation_id ? $child_variation_id : $child_product_id;
								$child              = wc_get_product( $child_id );

								if ( ! $child || ! $child->needs_shipping() ) {
									continue;
								}

								/*
								 * Add item into a new container "Contents" meta.
								 */

								$sku = $child->get_sku();

								if ( ! $sku ) {
									$sku = '#' . $child_id;
								}

								$meta = rtrim( strip_tags( wc_display_item_meta( $child_item, array(
									'before'    => '',
									'separator' => ', ',
									'after'     => '',
									'echo'      => false,
									'autop'     => false,
								) ) ) );

								$bundled_item_title = WC_PB_Helpers::format_product_title( $child_item->get_name(), '', $meta, true );

								$contents[] = array(
									'title'       => $bundled_item_title,
									'description' => sprintf( __( 'Quantity: %1$s, SKU: %2$s', 'woocommerce-product-bundles' ), $child_item->get_quantity(), $sku )
								);

								/*
								 * Add item totals to the container totals.
								 */

								$bundle_totals[ 'subtotal' ]     += $child_item->get_subtotal();
								$bundle_totals[ 'total' ]        += $child_item->get_total();
								$bundle_totals[ 'subtotal_tax' ] += $child_item->get_subtotal_tax();
								$bundle_totals[ 'total_tax' ]    += $child_item->get_total_tax();

								$child_item_tax_data = $child_item->get_taxes();

								$bundle_totals[ 'taxes' ][ 'total' ]    = array_merge( $bundle_totals[ 'taxes' ][ 'total' ], $child_item_tax_data[ 'total' ] );
								$bundle_totals[ 'taxes' ][ 'subtotal' ] = array_merge( $bundle_totals[ 'taxes' ][ 'subtotal' ], $child_item_tax_data[ 'subtotal' ] );
							}
						}

						// Back up meta to resolve https://github.com/woocommerce/woocommerce/pull/14851.
						$item_meta_data = unserialize( serialize( $item->get_meta_data() ) );

						// Create a clone to ensure item totals will not be modified permanently.
						$cloned_item = clone $item;

						// Delete meta without 'id' prop.
						$cloned_item_meta_data = $cloned_item->get_meta_data();

						foreach ( $cloned_item_meta_data as $cloned_item_meta ) {
							$cloned_item->delete_meta_data( $cloned_item_meta->key );
						}

						// Copy back meta with 'id' prop intact.
						$cloned_item->set_meta_data( $item_meta_data );

						// Replace original with clone.
						$item = $cloned_item;

						// Find highest 'id'.
						$max_id = 1;
						foreach ( $item->get_meta_data() as $item_meta ) {
							if ( isset( $item_meta->id ) ) {
								if ( $item_meta->id >= $max_id ) {
									$max_id = $item_meta->id;
								}
							}
						}

						$item->set_props( $bundle_totals );

						// Create a meta field for each bundled item.
						if ( ! empty( $contents ) ) {
							foreach ( $contents as $contained ) {
								$item->add_meta_data( $contained[ 'title' ], $contained[ 'description' ] );
								$added_meta = $item->get_meta( $contained[ 'title' ], true );
								// Ensure the meta object has an 'id' prop so it can be picked up by 'get_formatted_meta_data'.
								foreach ( $item->get_meta_data() as $item_meta ) {
									if ( $item_meta->key === $contained[ 'title' ] && ! isset( $item_meta->id ) ) {
										$item_meta->id = $max_id + 1;
										$max_id++;
									}
								}
							}
						}
					}
				}

			} elseif ( wc_pb_is_bundled_order_item( $item, $items ) ) {

				$variation_id = $item->get_variation_id();
				$product_id   = $item->get_product_id();
				$id           = $variation_id ? $variation_id : $product_id;
				$product      = wc_get_product( $id );

				if ( $product && $product->needs_shipping() && 'no' === $item->get_meta( '_bundled_item_needs_shipping', true ) ) {

					$item_totals = array(
						'subtotal'     => 0,
						'total'        => 0,
						'subtotal_tax' => 0,
						'total_tax'    => 0,
						'taxes'        => array( 'total' => array(), 'subtotal' => array() )
					);

					// Create a clone to ensure item totals will not be modified permanently.
					$item = clone $item;

					$item->set_props( $item_totals );
				}
			}

			$return_items[ $item_id ] = $item;
		}

		// End of my awesome infinite looping prevention mechanism.
		self::$override_order_items_filters = false;

		return $return_items;
	}

	/**
	 * Modifies parent/child order products in order to reconstruct an accurate representation of a bundle for shipping purposes:
	 *
	 * - If it's a container, then its weight is modified to include the weight of "packaged" children.
	 * - If a child is "packaged" inside its parent, then it is marked as virtual.
	 *
	 * Used in combination with 'get_order_items', right above.
	 *
	 * @param  WC_Product  $product
	 * @param  array       $item
	 * @param  WC_Order    $order
	 * @return WC_Product
	 */
	public function get_product_from_item( $product, $item, $order ) {

		// If it's a container item...
		if ( wc_pb_is_bundle_container_order_item( $item ) ) {

			if ( $product->needs_shipping() ) {

				// If it needs shipping, modify its weight to include the weight of all "packaged" items.
				if ( $bundle_weight = $item->get_meta( '_bundle_weight', true ) ) {
					$product->set_weight( $bundle_weight );
				}

				// Override SKU with kit/bundle SKU if needed.
				$child_items         = wc_pb_get_bundled_order_items( $item, $order );
				$packaged_products   = array();
				$packaged_quantities = array();

				// Find items shipped in the container:
				foreach ( $child_items as $child_item ) {

					if ( 'no' === $child_item->get_meta( '_bundled_item_needs_shipping', true ) ) {

						$child_variation_id = $child_item->get_variation_id();
						$child_product_id   = $child_item->get_product_id();
						$child_id           = $child_variation_id ? $child_variation_id : $child_product_id;

						$child_product    = wc_get_product( $child_id );

						if ( ! $child_product || ! $child_product->needs_shipping() ) {
							continue;
						}

						$packaged_products[]              = $child_product;
						$packaged_quantities[ $child_id ] = $child_item->get_quantity();
					}
				}

				$sku = $product->get_sku( 'edit' );

				/**
				 * Allows you to construct a dynamic SKU for the product bundle depending on its contents.
				 *
				 * @param  string             $sku
				 * @param  WC_Product_Bundle  $bundle
				 * @param  WC_Order_Item      $item
				 * @param  WC_Order           $order
				 * @param  array              $packaged_products_list
				 * @param  array              $packaged_product_quantities
				 */
				$new_sku = apply_filters( 'woocommerce_bundle_sku_from_order_item', $sku, $product, $item, $order, $packaged_products, $packaged_quantities );

				if ( $sku !== $new_sku ) {
					$product->set_sku( $new_sku );
				}
			}

		// If it's a child item...
		} elseif ( wc_pb_is_bundled_order_item( $item, $order ) ) {

			if ( $product->needs_shipping() ) {

				// If it's "packaged" in its container, set it to virtual.
				if ( 'no' === $item->get_meta( '_bundled_item_needs_shipping', true ) ) {
					$product->set_virtual( 'yes' );
					$product->set_weight( '' );
				}
			}
		}

		return $product;
	}

	/**
	 * Alternative shipping representation of a bundle that reconstructs an accurate value/volume/weight representation of a bundle for shipping purposes.
	 * Use this when each item needs to appear as a separate line item. Legacy method of exporting to ShipStation.
	 *
	 * Virtual containers/children are assigned a zero weight and tiny dimensions in order to maintain the value of the associated item in shipments:
	 *
	 * - If a bundled item is not shipped individually (virtual), its value must be included to ensure an accurate calculation of shipping costs (value/insurance).
	 * - If a bundle is not shipped as a physical item (virtual), it may have a non-zero value that also needs to be included to ensure an accurate calculation of shipping costs (value/insurance).
	 *
	 * In both cases, the workaround is to assign a tiny weight and miniscule dimensions to the non-shipped order items, in order to:
	 *
	 * - ensure that they are included in the exported data, by having 'needs_shipping' return 'true', but also
	 * - minimize the impact of their inclusion on shipping costs.
	 *
	 * @param  WC_Product  $product
	 * @param  array       $item
	 * @param  WC_Order    $order
	 * @return WC_Product
	 */
	public function get_legacy_shipstation_product_from_item( $product, $item, $order ) {

		// If it's a container item...
		if ( wc_pb_is_bundle_container_order_item( $item ) ) {

			if ( $product->needs_shipping() ) {

				if ( $bundle_weight = $item->get_meta( '_bundle_weight', true ) ) {
					$product->set_weight( $bundle_weight, $bundle_weight );
				}

			} else {

				// Process container.
				if ( $child_items = wc_pb_get_bundled_order_items( $item, $order ) ) {

					$non_virtual_child_exists = false;

					// Virtual container converted to non-virtual with zero weight and tiny dimensions if it has non-virtual bundled children.
					foreach ( $child_items as $child_item_id => $child_item ) {
						if ( 'yes' === $child_item->get_meta( '_bundled_item_needs_shipping', true ) ) {
							$non_virtual_child_exists = true;
							break;
						}
					}

					if ( $non_virtual_child_exists ) {
						$product->set_virtual( 'no' );
					}
				}

				if ( $product->get_weight() > 0 ) {
					$product->set_weight( '' );
				}
				if ( $product->length > 0 ) {
					$product->set_length( 0.001 );
				}
				if ( $product->height > 0 ) {
					$product->set_height( 0.001 );
				}
				if ( $product->width > 0 ) {
					$product->set_width( 0.001 );
				}
			}

		// If it's a child item...
		} elseif ( wc_pb_is_bundled_order_item( $item, $order ) ) {

			if ( $product->needs_shipping() ) {

				// If it's "packaged" in its container, set it to virtual.
				if ( 'no' === $item->get_meta( '_bundled_item_needs_shipping', true ) ) {

					if ( $product->get_weight() > 0 ) {
						$product->set_weight( '' );
					}
					if ( $product->length > 0 ) {
						$product->set_length( 0.001 );
					}
					if ( $product->height > 0 ) {
						$product->set_height( 0.001 );
					}
					if ( $product->width > 0 ) {
						$product->set_width( 0.001 );
					}
				}
			}
		}

		return $product;
	}

	/*
	|--------------------------------------------------------------------------
	| Filter hooks.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Modify the subtotal of order items depending on their pricing setup.
	 *
	 * @param  string         $subtotal
	 * @param  WC_Order_Item  $item
	 * @param  WC_Order       $order
	 * @return string
	 */
	public function order_item_subtotal( $subtotal, $item, $order ) {

		// If it's a bundled item...
		if ( $parent_item = wc_pb_get_bundled_order_item_container( $item, $order ) ) {

			$bundled_item_priced_individually = $item->get_meta( '_bundled_item_priced_individually', true );
			$bundled_item_price_hidden        = $item->get_meta( '_bundled_item_price_hidden', true );

			// Back-compat.
			if ( ! in_array( $bundled_item_priced_individually, array( 'yes', 'no' ) ) ) {
				$bundled_item_priced_individually = isset( $parent_item[ 'per_product_pricing' ] ) ? $parent_item[ 'per_product_pricing' ] : get_post_meta( $parent_item[ 'product_id' ], '_wc_pb_v4_per_product_pricing', true );
			}

			if ( 'no' === $bundled_item_priced_individually || 'yes' === $bundled_item_price_hidden || WC_PB()->compatibility->is_composited_order_item( $parent_item, $order ) ) {
				$subtotal = '';
			} else {

				/**
				 * Controls whether to include bundled order item subtotals in the container order item subtotal.
				 *
				 * @param  boolean   $add
				 * @param  array     $container_order_item
				 * @param  WC_Order  $order
				 */
				if ( apply_filters( 'woocommerce_add_bundled_order_item_subtotals', true, $parent_item, $order ) ) {
					$subtotal = sprintf( _x( '%1$s: %2$s', 'bundled product subtotal', 'woocommerce-product-bundles' ), __( 'Subtotal', 'woocommerce-product-bundles' ), $subtotal );
				}

				$subtotal = '<span class="bundled-product-subtotal">' . $subtotal . '</span>';
			}
		}

		// If it's a bundle (parent item)...
		if ( wc_pb_is_bundle_container_order_item( $item ) ) {

			// Create a clone to ensure item totals will not be modified permanently.
			$item = clone $item;

			/** Documented right above. Look up. See? */
			$add_subtotals_into_container = apply_filters( 'woocommerce_add_bundled_order_item_subtotals', true, $item, $order );

			if ( ! isset( $item->child_subtotals_added ) && $add_subtotals_into_container ) {

				$children = wc_pb_get_bundled_order_items( $item, $order );

				if ( ! empty( $children ) ) {

					foreach ( $children as $child ) {
						$item->set_subtotal( $item->get_subtotal( 'edit' ) + $child->get_subtotal( 'edit' ) );
						$item->set_subtotal_tax( $item->get_subtotal_tax( 'edit' ) + $child->get_subtotal_tax( 'edit' ) );
					}

					$item->child_subtotals_added = 'yes';

					$subtotal = $order->get_formatted_line_subtotal( $item );
				}
			}
		}

		return $subtotal;
	}

	/**
	 * Filters the reported number of order items.
	 * Do not count bundled items.
	 *
	 * @param  int       $count
	 * @param  string    $type
	 * @param  WC_Order  $order
	 * @return int
	 */
	public function order_item_count( $count, $type, $order ) {

		$subtract = 0;

		if ( function_exists( 'is_account_page' ) && is_account_page() ) {
			foreach ( $order->get_items() as $item ) {
				if ( wc_pb_get_bundled_order_item_container( $item, $order ) ) {
					$subtract += $item->get_quantity();
				}
			}
		}

		$new_count = $count - $subtract;

		return $new_count;
	}

	/**
	 * Filters the string of order item count.
	 * Include bundled items as a suffix.
	 *
	 * @see    order_item_count
	 *
	 * @param  int       $count
	 * @param  WC_Order  $order
	 * @return int
	 */
	public function order_item_count_string( $count, $order ) {

		$add = 0;

		foreach ( $order->get_items() as $item ) {
			if ( wc_pb_get_bundled_order_item_container( $item, $order ) ) {
				$add += $item->get_quantity();
			}
		}

		if ( $add > 0 ) {
			$count = sprintf( __( '%1$s, %2$s bundled', 'woocommerce-product-bundles' ), $count, $add );
		}

		return $count;
	}

	/**
	 * Filters the order item admin class.
	 *
	 * @param  string    $class
	 * @param  array     $item
	 * @param  WC_Order  $order
	 * @return string
	 */
	public function html_order_item_class( $class, $item, $order = false ) {

		if ( wc_pb_maybe_is_bundled_order_item( $item ) ) {
			if ( false === $order || wc_pb_get_bundled_order_item_container( $item, $order ) ) {
				$class .= ' bundled_item';
			}
		}

		return $class;

	}

	/**
	 * Bundle Containers need no processing - let it be decided by bundled items only.
	 *
	 * @param  boolean     $is_needed
	 * @param  WC_Product  $product
	 * @param  int         $order_id
	 * @return boolean
	 */
	public function container_item_needs_processing( $is_needed, $product, $order_id ) {

		if ( $product->is_type( 'bundle' ) && isset( $product->bundle_needs_processing ) && 'no' === $product->bundle_needs_processing ) {
			$is_needed = false;
		}

		return $is_needed;
	}

	/**
	 * Hides bundle metadata.
	 *
	 * @param  array  $hidden
	 * @return array
	 */
	public function hidden_order_item_meta( $hidden ) {

		$current_meta = array( '_bundled_by', '_bundled_items', '_bundle_cart_key', '_bundled_item_id', '_bundled_item_hidden', '_bundled_item_price_hidden', '_bundled_item_title', '_bundled_item_needs_shipping', '_bundle_weight', '_bundled_item_priced_individually', '_bundled_items_need_processing' );
		$legacy_meta  = array( '_per_product_pricing', '_per_product_shipping', '_bundled_shipping', '_bundled_weight' );

		return array_merge( $hidden, $current_meta, $legacy_meta );
	}

	/**
	 * Add bundle info meta to order items.
	 *
	 * @param  WC_Order_Item  $order_item
	 * @param  string         $cart_item_key
	 * @param  array          $cart_item_values
	 * @return void
	 */
	public function add_order_item_meta( $order_item, $cart_item_key, $cart_item_values ) {

		if ( wc_pb_is_bundled_cart_item( $cart_item_values ) ) {

			$order_item->add_meta_data( '_bundled_by', $cart_item_values[ 'bundled_by' ], true );
			$order_item->add_meta_data( '_bundled_item_id', $cart_item_values[ 'bundled_item_id' ], true );

			$bundled_item_id = $cart_item_values[ 'bundled_item_id' ];
			$visible         = true;

			if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item_values ) ) {

				$bundle          = $bundle_container_item[ 'data' ];
				$bundled_item_id = $cart_item_values[ 'bundled_item_id' ];

				if ( $bundled_item = $bundle->get_bundled_item( $bundled_item_id ) ) {

					if ( false === $bundled_item->is_visible( 'order' ) ) {
						$order_item->add_meta_data( '_bundled_item_hidden', 'yes', true );
					}

					if ( false === $bundled_item->is_price_visible( 'order' ) ) {
						$order_item->add_meta_data( '_bundled_item_price_hidden', 'yes', true );
					}

					$order_item->add_meta_data( '_bundled_item_priced_individually', $bundled_item->is_priced_individually() ? 'yes' : 'no', true );
				}
			}

			if ( isset( $cart_item_values[ 'stamp' ][ $bundled_item_id ][ 'title' ] ) ) {
				$title = $cart_item_values[ 'stamp' ][ $bundled_item_id ][ 'title' ];
				$order_item->add_meta_data( '_bundled_item_title', $title, true );
			}
		}

		if ( wc_pb_is_bundle_container_cart_item( $cart_item_values ) ) {
			if ( isset( $cart_item_values[ 'bundled_items' ] ) ) {
				$order_item->add_meta_data( '_bundled_items', $cart_item_values[ 'bundled_items' ], true );
			}
		}

		if ( isset( $cart_item_values[ 'stamp' ] ) ) {

			$order_item->add_meta_data( '_stamp', $cart_item_values[ 'stamp' ], true );
			$order_item->add_meta_data( '_bundle_cart_key', $cart_item_key, true );

			/*
			 * Store shipping data - useful when exporting order content.
			 */

			$needs_shipping = $cart_item_values[ 'data' ]->needs_shipping() ? 'yes' : 'no';

			// If it's a physical child item, add a meta fild to indicate whether it is shipped individually.
			if ( wc_pb_is_bundled_cart_item( $cart_item_values ) ) {

				$order_item->add_meta_data( '_bundled_item_needs_shipping', $needs_shipping, true );

			} elseif ( wc_pb_is_bundle_container_cart_item( $cart_item_values ) ) {

				// If it's a physical container item, grab its aggregate weight from the package data.
				if ( 'yes' === $needs_shipping ) {

					$packaged_item_values = false;

					foreach ( WC()->cart->get_shipping_packages() as $package ) {
						if ( isset( $package[ 'contents' ][ $cart_item_key ] ) ) {
							$packaged_item_values = $package[ 'contents' ][ $cart_item_key ];
							break;
						}
					}

					if ( ! empty( $packaged_item_values ) ) {
						$bundled_weight = $packaged_item_values[ 'data' ]->get_weight( 'edit' );
						$order_item->add_meta_data( '_bundle_weight', $bundled_weight, true );
					}

				// If it's a virtual container item, look at its children to see if any of them needs processing.
				} elseif ( false === $this->bundled_items_need_processing( $cart_item_values ) ) {
					$order_item->add_meta_data( '_bundled_items_need_processing', 'no', true );
				}
			}
		}
	}

	/**
	 * Given a virtual bundle container cart item, find if any of its children need processing.
	 *
	 * @since  5.0.0
	 *
	 * @param  array  $item_values
	 * @return mixed
	 */
	private function bundled_items_need_processing( $item_values ) {

		$child_keys        = wc_pb_get_bundled_cart_items( $item_values, WC()->cart->cart_contents, true, true );
		$processing_needed = false;

		if ( ! empty( $child_keys ) && is_array( $child_keys ) ) {
			foreach ( $child_keys as $child_key ) {
				$child_product = WC()->cart->cart_contents[ $child_key ][ 'data' ];
				if ( false === $child_product->is_downloadable() || false === $child_product->is_virtual() ) {
					$processing_needed = true;
					break;
				}
			}
		}

		return $processing_needed;
	}

	/**
	 * Activates the 'get_product_from_item' filter below.
	 *
	 * @param  string  $order_id
	 * @return void
	 */
	public function apply_get_product_from_item_filter( $order_id ) {
		add_filter( 'woocommerce_get_product_from_item', array( $this, 'get_processing_product_from_item' ), 10, 3 );
	}

	/**
	 * Deactivates the 'get_product_from_item' filter below.
	 *
	 * @param  string  $order_id
	 * @return void
	 */
	public function remove_get_product_from_item_filter( $order_id ) {
		remove_filter( 'woocommerce_get_product_from_item', array( $this, 'get_processing_product_from_item' ), 10, 3 );
	}

	/**
	 * Filters 'get_product_from_item' to add data used for 'woocommerce_order_item_needs_processing'.
	 *
	 * @param  WC_Product  $product
	 * @param  array       $item
	 * @param  WC_Order    $order
	 * @return WC_Product
	 */
	public function get_processing_product_from_item( $product, $item, $order ) {

		if ( ! empty( $product ) && $product->is_virtual() ) {

			// Process container.
			if ( $child_items = wc_pb_get_bundled_order_items( $item, $order ) ) {

				// If no child requires processing and the container is virtual, it should not require processing - @see 'container_item_needs_processing()'.
				if ( $product->is_virtual() && sizeof( $child_items ) > 0 ) {
					if ( 'no' === $item->get_meta( '_bundled_items_need_processing', true ) ) {
						$product->bundle_needs_processing = 'no';
					}
				}
			}
		}

		return $product;
	}


	/*
	|--------------------------------------------------------------------------
	| Deprecated methods.
	|--------------------------------------------------------------------------
	*/

	public function get_bundled_order_item_container( $item, $order ) {
		_deprecated_function( __METHOD__ . '()', '4.13.0', __CLASS__ . '::get_bundle_parent()' );
		return wc_pb_get_bundled_order_item_container( $item, $order );
	}
	public function woo_bundles_add_order_item_meta( $order_item_id, $cart_item_values, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '4.13.0', __CLASS__ . '::add_order_item_meta()' );
		$this->add_order_item_meta( $order_item_id, $cart_item_values, $cart_item_key );
	}
	public static function get_bundle_parent( $item, $order, $return_type = 'item' ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', 'wc_pb_get_bundled_order_item_container()' );
		return wc_pb_get_bundled_order_item_container( $item, $order, $return_type === 'id' );
	}
	public static function get_bundle_children( $item, $order, $return_type = 'item' ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', 'wc_pb_get_bundled_order_items()' );
		return wc_pb_get_bundled_order_items( $item, $order, $return_type === 'id' );
	}
}
