<?php

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Class for integrating with WooCommerce Blocks
 */
class Shipping_Workshop_Blocks_Integration implements IntegrationInterface {

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'shipping-workshop';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize() {
		require_once __DIR__ . '/shipping-workshop-extend-store-endpoint.php';

		$this->register_shipping_workshop_block_editor_scripts();
		$this->register_shipping_workshop_block_editor_styles();

		$this->register_main_integration();
		$this->extend_store_api();


	}

	/**
	 * Extends the cart schema to include the shipping-workshop value.
	 */
	private function extend_store_api() {
		Shipping_Workshop_Extend_Store_Endpoint::init();
	}


	/**
	 * Registers the main JS file required to add filters and Slot/Fills.
	 */
	private function register_main_integration() {
		$script_path = '/build/index.js';
		$style_path  = '/build/style-index.css';

		$script_url = plugins_url( $script_path, __FILE__ );
		$style_url  = plugins_url( $style_path, __FILE__ );

		$script_asset_path = dirname( __FILE__ ) . '/build/index.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $script_path ),
			];

		wp_enqueue_style(
			'shipping-workshop-blocks-integration',
			$style_url,
			[],
			$this->get_file_version( $style_path )
		);

		wp_register_script(
			'shipping-workshop-blocks-integration',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
		wp_set_script_translations(
			'shipping-workshop-blocks-integration',
			'shipping-workshop',
			dirname( __FILE__ ) . '/languages'
		);
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return [ 'shipping-workshop-blocks-integration', 'shipping-workshop-block-frontend' ];
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return [ 'shipping-workshop-blocks-integration', 'shipping-workshop-block-editor' ];
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {
		$data = [
			'shipping-workshop-active' => true,
		];

		return $data;

	}

	public function register_shipping_workshop_block_editor_styles() {
		$block_style_path = '/build/style-shipping-workshop-block.css';

		$block_style_url = plugins_url( $block_style_path, __FILE__ );
		wp_enqueue_style(
			'shipping-workshop-block',
			$block_style_url,
			[],
			$this->get_file_version( $block_style_path )
		);
		$extra_style_path = '/build/style-extra-workshop-block.css';

		$extra_style_url = plugins_url( $extra_style_path, __FILE__ );
		wp_enqueue_style(
			'extra-workshop-block',
			$extra_style_url,
			[],
			$this->get_file_version( $extra_style_path )
		);
	}

	public function register_shipping_workshop_block_editor_scripts() {
		$block_path       = '/build/shipping-workshop-block.js';
		$block_url        = plugins_url( $block_path, __FILE__ );
		$block_asset_path = dirname( __FILE__ ) . '/build/shipping-workshop-block.asset.php';
		$block_asset      = file_exists( $block_asset_path )
			? require $block_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $block_asset_path ),
			];

		wp_register_script(
			'shipping-workshop-block-editor',
			$block_url,
			$block_asset['dependencies'],
			$block_asset['version'],
			true
		);

		wp_set_script_translations(
			'extra-workshop-block-editor',
			'shipping-workshop',
			dirname( __FILE__ ) . '/languages'
		);

		$extra_block_path       = '/build/extra-workshop-block.js';
		$extra_block_url        = plugins_url( $extra_block_path, __FILE__ );
		$extra_block_asset_path = dirname( __FILE__ ) . '/build/extra-workshop-block.asset.php';
		$extra_block_asset      = file_exists( $extra_block_asset_path )
			? require $extra_block_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $extra_block_asset_path ),
			];

		wp_register_script(
			'shipping-workshop-block-editor',
			$extra_block_url,
			$extra_block_asset['dependencies'],
			$extra_block_asset['version'],
			true
		);

		wp_set_script_translations(
			'extra-workshop-block-editor',
			'shipping-workshop',
			dirname( __FILE__ ) . '/languages'
		);
		$block_frontend      = plugins_url( '/build/shipping-workshop-block-frontend.js', __FILE__ );
		$frontend_asset_path = dirname( __FILE__ ) . '/build/shipping-workshop-block-frontend.asset.php';
		$frontend_asset      = file_exists( $frontend_asset_path )
			? require $frontend_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $frontend_asset_path ),
			];

		wp_enqueue_script(
			'shipping-workshop-block-frontend',
			$block_frontend,
			$frontend_asset['dependencies'],
			$frontend_asset['version'],
			true
		);
		wp_set_script_translations(
			'shipping-workshop-block-frontend',
			'shipping-workshop',
			dirname( __FILE__ ) . '/languages'
		);

		$extra_block_frontend      = plugins_url( '/build/extra-workshop-block-frontend.js', __FILE__ );
		$extra_frontend_asset_path = dirname( __FILE__ ) . '/build/extra-workshop-block-frontend.asset.php';
		$extra_frontend_asset      = file_exists( $extra_frontend_asset_path )
			? require $extra_frontend_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $extra_frontend_asset_path ),
			];

		wp_enqueue_script(
			'extra-workshop-block-frontend',
			$extra_block_frontend,
			$extra_frontend_asset['dependencies'],
			$extra_frontend_asset['version'],
			true
		);
		wp_set_script_translations(
			'extra-workshop-block-frontend',
			'shipping-workshop',
			dirname( __FILE__ ) . '/languages'
		);


	}


	/**
	 * Get the file modified time as a cache buster if we're in dev mode.
	 *
	 * @param string $file Local path to the file.
	 *
	 * @return string The cache buster value to use for the given file.
	 */
	protected function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
			return filemtime( $file );
		}

		return SHIPPING_WORKSHOP_VERSION;
	}
}
