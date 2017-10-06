
<?php
/**
 * WooCommere View Order template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.1
 */

// The $order_id is inherited from the Avada_Woocommerce::avada_woocommerce_view_order() method.
$order = wc_get_order( $order_id );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
?>
<div class="avada-order-details woocommerce-content-box full-width">
	<h2><?php esc_attr_e( 'Order details', 'woocommerce' ); ?></h2>
	<table class="shop_table order_details">
		<thead>
		<tr>
			<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
		</tr>
		</thead>
		<tfoot>
			<?php if ( $totals = $order->get_order_item_totals() ) : ?>
				<?php foreach ( $totals as $total ) : ?>
					<tr>
						<td class="filler-td">&nbsp;</td>
						<th scope="row">
							<?php // @codingStandardsIgnoreLine ?>
							<?php echo $total['label']; ?>
						</th>
						<td class="product-total">
							<?php // @codingStandardsIgnoreLine ?>
							<?php echo $total['value']; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tfoot>
		<tbody>
			<?php if ( count( $order->get_items() ) > 0 ) :
				foreach ( $order->get_items() as $item_id => $item ) :
					// Checks for Woo < 2.7.
					if ( version_compare( self::get_wc_version(), '2.7', '>=' ) ) {
						$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );
					} else {
						$product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
					}

					if ( method_exists( $product, 'get_purchase_note' ) ) {
						$purchase_note = ( $product ) ? $product->get_purchase_note() : '';
					} else {
						$purchase_note = get_post_meta( $product->id, '_purchase_note', true );
					}

					$is_visible        = $product && $product->is_visible();
					$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">
						<td class="woocommerce-table__product-name product-name">
							<span class="product-thumbnail">
								<?php $thumbnail = $product->get_image(); ?>
								<?php if ( ! $is_visible ) : ?>
									<?php // @codingStandardsIgnoreLine ?>
									<?php echo $thumbnail; ?>
								<?php else : ?>
									<?php // @codingStandardsIgnoreLine ?>
									<?php echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $thumbnail ) : wp_kses_post( $thumbnail ), $item, $is_visible ); ?>
								<?php endif; ?>

							</span>

							<div class="product-info">
								<?php
								// Check for Woo < 2.7.
								if ( version_compare( self::get_wc_version(), '2.7', '>=' ) ) {
									$product_name = $item->get_name();
								} else {
									$product_name = $item['name'];
								}

								if ( $product && ! $is_visible ) {
									echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_name, $item,  $is_visible ) );
								} else {
									 echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', $product_permalink, $product_name ), $item, $is_visible ) );
								}

								// Meta data.
								do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );
								if ( version_compare( self::get_wc_version(), '2.7', '>=' ) ) {
									wc_display_item_meta( $item );
									wc_display_item_downloads( $item );
								} else {
									$order->display_item_meta( $item );
									$order->display_item_downloads( $item );
								}
								do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
								?>
							</div>
						</td>
						<td class="product-quantity">
							<?php
							// Check for Woo < 2.7.
							if ( version_compare( self::get_wc_version(), '2.7', '>=' ) ) {
								$product_quantity = $item->get_quantity();
							} else {
								$product_quantity = $item['qty'];
							}
							echo wp_kses_post( apply_filters( 'woocommerce_order_item_quantity_html', $product_quantity, $item ) );
							?>
						</td>
						<td class="woocommerce-table__product-total product-total">
							<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
						</td>
					</tr>

					<?php if ( $show_purchase_note && $purchase_note ) : ?>
						<tr class="woocommerce-table__product-purchase-note product-purchase-note">
							<td colspan="3"><?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php do_action( 'woocommerce_order_items_table', $order ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</div>

<div class="avada-customer-details woocommerce-content-box full-width">
	<header>
		<h2><?php esc_attr_e( 'Customer details', 'woocommerce' ); ?></h2>
	</header>
	<dl class="customer_details">
		<?php $billing_email = ( method_exists( $order, 'get_billing_email' ) ) ? $order->get_billing_email() : $order->billing_email; ?>
		<?php if ( $billing_email ) : ?>
			<dt><?php esc_attr_e( 'Email:', 'woocommerce' ); ?></dt> <dd><?php echo esc_attr( $billing_email ); ?> </dd><br />
		<?php endif; ?>

		<?php $billing_phone = ( method_exists( $order, 'get_billing_phone' ) ) ? $order->get_billing_phone() : $order->billing_phone; ?>
		<?php if ( $billing_phone ) : ?>
			<dt><?php esc_attr_e( 'Telephone:', 'woocommerce' ); ?></dt> <dd><?php echo esc_html( $billing_phone ); ?></dd>
		<?php endif; ?>

		<?php
		// Additional customer details hook.
		do_action( 'woocommerce_order_details_after_customer_details', $order );
		?>
	</dl>

	<?php if ( 'no' === get_option( 'woocommerce_ship_to_billing_address_only' ) && 'no' !== get_option( 'woocommerce_calc_shipping' ) ) : ?>

		<div class="col2-set addresses">
			<div class="col-1">

	<?php endif; ?>

	<header class="title">
		<h3><?php esc_attr_e( 'Billing address', 'woocommerce' ); ?></h3>
	</header>

	<address>
		<p>
			<?php if ( ! $order->get_formatted_billing_address() ) : ?>
				<?php esc_attr_e( 'N/A', 'woocommerce' ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?>
			<?php endif; ?>
		</p>
	</address>

	<?php if ( 'no' === get_option( 'woocommerce_ship_to_billing_address_only' ) && 'no' !== get_option( 'woocommerce_calc_shipping' ) ) : ?>

		</div>
		<div class="col-2">
			<header class="title">
				<h3><?php esc_attr_e( 'Shipping address', 'woocommerce' ); ?></h3>
			</header>
			<address>
				<p>
					<?php if ( ! $order->get_formatted_shipping_address() ) : ?>
						<?php esc_attr_e( 'N/A', 'woocommerce' ); ?>
					<?php else : ?>
						<?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?>
					<?php endif; ?>
				</p>
			</address>
		</div>

	</div>
	<?php endif; ?>

	<div class="clear"></div>

</div>
