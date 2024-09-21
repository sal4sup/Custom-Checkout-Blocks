<?php

use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartSchema;
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema;

/**
 * Shipping Workshop Extend WC Core.
 */
class Shipping_Workshop_Extend_Woo_Core {

	/**
	 * Plugin Identifier, unique to each plugin.
	 *
	 * @var string
	 */
	private $name = 'shipping-workshop';

	/**
	 * Bootstraps the class and hooks required data.
	 */
	public function init() {
		add_action( 'woocommerce_store_api_checkout_update_order_from_request', [
			$this,
			'save_shipping_instructions'
		], 10, 2 );
		add_action( 'woocommerce_admin_order_data_after_shipping_address', [
			$this,
			'show_shipping_instructions_in_order'
		] );
		add_action( 'woocommerce_order_details_after_customer_details', [
			$this,
			'show_shipping_instructions_in_order_confirmation'
		] );
		add_action( 'woocommerce_email_after_order_table', [
			$this,
			'show_shipping_instructions_in_order_email'
		], 10, 4 );

	}


	/**
	 * Saves the shipping instructions to the order's metadata.
	 *
	 * @return void
	 */
	public function save_shipping_instructions( \WC_Order $order, \WP_REST_Request $request ) {
		/**
		 * 📝 Write a hook, using the `woocommerce_store_api_checkout_update_order_from_request` action
		 * that will update the order metadata with the shipping-workshop alternate shipping instruction.
		 *
		 * The documentation for this hook is at: https://github.com/woocommerce/woocommerce-blocks/blob/b73fbcacb68cabfafd7c3e7557cf962483451dc1/docs/third-party-developers/extensibility/hooks/actions.md#woocommerce_store_api_checkout_update_order_from_request
		 */

		$shipping_workshop_request_data = $request['extensions'][ $this->name ];
		/**
		 * 📝From the `$shipping_workshop_request_data` array, get the `alternateShippingInstruction` and
		 * `otherShippingValue` entries. Store them in their own variables, $alternate_shipping_instruction and .
		 */
		$alternate_shipping_instruction = $shipping_workshop_request_data['alternateShippingInstruction'];
		$other_shipping_value           = $shipping_workshop_request_data['otherShippingValue'];
		$tshirt_title                   = $shipping_workshop_request_data['tshirtTitle'];
		$tshirt_desc                    = $shipping_workshop_request_data['tshirtDesc'];
		$tshirt_size                    = $shipping_workshop_request_data['tshirtSize'];
		$tshirt_color                   = $shipping_workshop_request_data['tshirtColor'];


		/**
		 * 📝Using `$order->update_meta_data` update the order metadata.
		 */
		$order->update_meta_data( 'shipping_workshop_alternate_shipping_instruction', $alternate_shipping_instruction );

		if ( 'other' === $alternate_shipping_instruction ) {
			$order->update_meta_data( 'shipping_workshop_alternate_shipping_instruction_other_text', $other_shipping_value );
		}

		$order->update_meta_data( 'extra_workshop_tshirt_title', $tshirt_title );
		$order->update_meta_data( 'extra_workshop_tshirt_desc', $tshirt_desc );
		$order->update_meta_data( 'extra_workshop_tshirt_size', $tshirt_size );
		$order->update_meta_data( 'extra_workshop_tshirt_color', $tshirt_color );

		/**
		 * 💡Don't forget to save the order using `$order->save()`.
		 */
		$order->save();

	}

	/**
	 * Adds the address in the order page in WordPress admin.
	 */
	public function show_shipping_instructions_in_order( \WC_Order $order ) {
		/**
		 * 📝 Get the `shipping_workshop_alternate_shipping_instruction` from the order metadata using `$order->get_meta`.
		 */
		$alternate_shipping_instruction = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );

		$alternate_shipping_instruction_other_text = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );
		$tshirt_title                              = $order->get_meta( 'extra_workshop_tshirt_title' );
		$tshirt_desc                               = $order->get_meta( 'extra_workshop_tshirt_desc' );
		$tshirt_size                               = $order->get_meta( 'extra_workshop_tshirt_size' );
		$tshirt_color                              = $order->get_meta( 'extra_workshop_tshirt_color' );


		/**
		 * Output the shipping instructions in the order admin by echoing them here.
		 */
		echo '<div>';
		echo '<h3>' . __( 'Shipping Instructions', 'shipping-workshop' ) . '</h3>';
		printf( '<p>%s</p>', $alternate_shipping_instruction );
		if ( $alternate_shipping_instruction === 'other' ) {
			printf( '<p>%s</p>', $alternate_shipping_instruction_other_text );
		}
		echo '</div>';


		echo '<div>';
		echo '<h3>' . __( 'T-shirt Options', 'shipping-workshop' ) . '</h3>';
		printf( '<p><strong>T-shirt Title:</strong> %s</p>', $tshirt_title );
		printf( '<p><strong>T-shirt Description:</strong> %s</p>', $tshirt_desc );
		printf( '<p><strong>T-shirt Size:</strong> %s</p>', $tshirt_size );
		printf( '<p><strong>T-shirt Color:</strong> %s</p>', $tshirt_color );

		echo '</div>';


		echo '</div>';


	}


	/**
	 * Adds the address on the order confirmation page.
	 */
	public function show_shipping_instructions_in_order_confirmation( \WC_Order $order ) {

		$shipping_workshop_alternate_shipping_instruction = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction' );
		$shipping_workshop_alternate_shipping_instruction_other_text = $order->get_meta( 'shipping_workshop_alternate_shipping_instruction_other_text' );
		$tshirt_title = $order->get_meta['extra_workshop_tshirt_title'];

		if ( $shipping_workshop_alternate_shipping_instruction !== '' ) {
			echo '<h2>' . __( 'Shipping Instructions', 'shipping-workshop' ) . '</h2>';
			echo '<p>' . $shipping_workshop_alternate_shipping_instruction . '</p>';

			if ( $shipping_workshop_alternate_shipping_instruction_other_text !== '' ) {
				echo '<p>' . $shipping_workshop_alternate_shipping_instruction_other_text . '</p>';
			}
		}
		if ( $tshirt_title !== '' ) {
			echo '<h2>' . __( 'T-shirt Title', 'shipping-workshop' ) . '</h2>';
			echo '<p>' . $tshirt_title . '</p>';
		}

	}

	/**
	 * Adds the address on the order confirmation email.
	 */
	public function show_shipping_instructions_in_order_email( $order, $sent_to_admin, $plain_text, $email ) {
		$tshirt_title = $order->get_meta( 'extra_workshop_tshirt_title' );
		if ( $tshirt_title !== '' ) {
			echo '<h2>' . esc_html__( 'T-shirt Title', 'shipping-workshop' ) . '</h2>';
			echo '<p>' . esc_html( $tshirt_title ) . '</p>';
		}
	}

}
