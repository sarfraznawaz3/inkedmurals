<?php

/*
  Plugin Name: WooCommerce - Gravity Forms Product Add-Ons
  Plugin URI: http://woothemes.com/products/gravity-forms-add-ons/
  Description: Allows you to use Gravity Forms on individual WooCommerce products. Requires the Gravity Forms plugin to work. Requires WooCommerce 2.5 or higher.
  Version: 3.1.11
  Author: Lucas Stark
  Author URI: http://www.elementstark.com/
  Developer: Lucas Stark
  Developer URI: http://www.elementstark.com/
  Requires at least: 3.1
  Tested up to: 4.7.4

  Copyright: © 2009-2017 Lucas Stark.
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'a6ac0ab1a1536e3a357ccf24c0650ed0', '18633' );

if ( is_woocommerce_active() ) {

	load_plugin_textdomain( 'wc_gf_addons', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	add_action( 'init', 'wc_gravityforms_product_addons_load_textdomain', 0 );

	function wc_gravityforms_product_addons_load_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wc_gf_addons' );
		load_textdomain( 'wc_gf_addons', WP_LANG_DIR . '/woocommerce/woocommerce-gravityforms-product-addons-' . $locale . '.mo' );
		load_plugin_textdomain( 'wc_gf_addons', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
	}

	include 'compatibility.php';

	add_action('plugins_loaded', 'wc_gravityforms_product_addons_plugins_loaded');

	function wc_gravityforms_product_addons_plugins_loaded() {
		if ( WC_GFPA_Compatibility::is_wc_version_gte_2_7() ) {
			require_once 'gravityforms-product-addons-main.php';
		} else {
			require_once 'back_compat_less_27/gravityforms-product-addons-main.php';
		}
	}

	function wc_gfpa_get_plugin_url() {
		return plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) );
	}

}
