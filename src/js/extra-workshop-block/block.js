/**
 * External dependencies
 */
import { useEffect, useState, useCallback } from '@wordpress/element';
import { TextControl,SelectControl, TextareaControl,RadioControl } from '@wordpress/components'; // Ensure you import TextControl correctly
import { __ } from '@wordpress/i18n';
import { debounce } from 'lodash';
import {sizeOptions,colorOptions} from "../extra-workshop-block/options";

/**
 * Internal dependencies
 */

export const Block = ({ checkoutExtensionData }) => {
	const { setExtensionData } = checkoutExtensionData;
	const debouncedSetExtensionData = useCallback(
		debounce((namespace, key, value) => {
			setExtensionData(namespace, key, value);
		}, 1000),
		[setExtensionData]
	);

	const [tshirtTitle, setTshirtTitle] = useState('');
	const [tshirtDesc, setTshirtDesc] = useState('');
	const [tshirtSize, setTshirtSize] = useState('');
	const [tshirtColor, setTshirtColor] = useState('');

	/* Handle changing the T-shirt Title value */
	useEffect(() => {
		setExtensionData('shipping-workshop', 'tshirtTitle', tshirtTitle);
		debouncedSetExtensionData('shipping-workshop', 'tshirtTitle', tshirtTitle);
	}, [setExtensionData, tshirtTitle, debouncedSetExtensionData]);

	/* Handle changing the T-shirt Title value */
	useEffect(() => {
		setExtensionData('shipping-workshop', 'tshirtDesc', tshirtDesc);
		debouncedSetExtensionData('shipping-workshop', 'tshirtDesc', tshirtDesc);
	}, [setExtensionData, tshirtDesc, debouncedSetExtensionData]);

	/* Handle changing the T-shirt Title value */
	useEffect(() => {
		setExtensionData('shipping-workshop', 'tshirtSize', tshirtSize);
		debouncedSetExtensionData('shipping-workshop', 'tshirtSize', tshirtSize);
	}, [setExtensionData, tshirtSize, debouncedSetExtensionData]);

	/* Handle changing the T-shirt Title value */
	useEffect(() => {
		setExtensionData('shipping-workshop', 'tshirtColor', tshirtColor);
		debouncedSetExtensionData('shipping-workshop', 'tshirtColor', tshirtColor);
	}, [setExtensionData, tshirtColor, debouncedSetExtensionData]);


	return (
		<div className="wc-block-components-text-input">

			{/* T-shirt Title field for the frontend */}
			<TextControl
				placeholder={__('T-shirt Title', 'shipping-workshop')}
				value={tshirtTitle}
				onChange={(value) => setTshirtTitle(value)}
			/>
			<TextareaControl
				className={
					'shipping-workshop-other-textarea'
				}
				onChange={setTshirtDesc}
				value={tshirtDesc}
				required={true}
				placeholder={__(
					'Enter T-shirt description to be printed on the T-shirt',
					'shipping-workshop'
				)}
			/>
			<RadioControl
				label={__('T-shirt Size', 'shipping-workshop')}
				selected={tshirtSize}
				options={sizeOptions}
				onChange={(value) => setTshirtSize(value)}
			/>
			<SelectControl
				label={__('T-shirt Color', 'shipping-workshop')}
				value={tshirtColor}
				options={colorOptions}
				onChange={(value) => setTshirtColor(value)}
			/>

		</div>
	);
};
