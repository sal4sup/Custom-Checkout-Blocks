/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';

// Import block definitions
import './shipping-workshop-block';
import './extra-workshop-block';  // Ensure the extra workshop block is imported

const render = () => {};

registerPlugin('shipping-workshop', {
	render,
	scope: 'woocommerce-checkout',
});
