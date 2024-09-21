<?php
/**
 * Plugin Name:     Shipping Workshop
 * Version:         1.0
 * Author:          Thomas Roberts and Niels Lange
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     shipping-workshop
 *
 * @package         create-block
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define SHIPPING_WORKSHOP_VERSION.
$plugin_data = get_file_data( __FILE__, [ 'version' => 'version' ] );
$plugin_version = isset( $plugin_data['version'] ) ? $plugin_data['version'] : '1.0.0';
define( 'SHIPPING_WORKSHOP_VERSION', $plugin_version );

/**
 * Include the dependencies needed to instantiate the block.
 */
function shipping_workshop_register_blocks() {
	require_once __DIR__ . '/shipping-workshop-extend-store-endpoint.php';
	require_once __DIR__ . '/shipping-workshop-extend-woo-core.php';
	require_once __DIR__ . '/shipping-workshop-blocks-integration.php';

	// Initialize endpoints and core functionality
	Shipping_Workshop_Extend_Store_Endpoint::init();
	$extend_core = new Shipping_Workshop_Extend_Woo_Core();
	$extend_core->init();

	// Register the blocks integration
	add_action( 'woocommerce_blocks_checkout_block_registration', function( $integration_registry ) {
		$integration_registry->register( new Shipping_Workshop_Blocks_Integration() );
	});
}
add_action( 'woocommerce_blocks_loaded', 'shipping_workshop_register_blocks' );

/**
 * Registers the slug as a block category with WordPress.
 */
function register_shipping_workshop_block_category( $categories ) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'shipping-workshop',
				'title' => __( 'Shipping Workshop Blocks', 'shipping-workshop' ),
			],
		]
	);
}
add_action( 'block_categories_all', 'register_shipping_workshop_block_category', 10, 2 );

