/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody, TextControl,SelectControl,RadioControl, Disabled } from '@wordpress/components';  // Import Disabled component
import { getSetting } from '@woocommerce/settings';

/**
 * Internal dependencies
 */
import './style.scss';
import {colorOptions, sizeOptions} from "./options";

const { defaultTsectionText } = getSetting('t-shirt_data', '');

export const Edit = ({ attributes, setAttributes }) => {
	const { text, tshirtTitle } = attributes;
	const blockProps = useBlockProps();
	return (
		<div {...blockProps} style={{ display: 'block' }}>
			<InspectorControls>
				<PanelBody title={__('Block options', 'shipping-workshop')}>
					Options for the block go here.
				</PanelBody>
			</InspectorControls>
			<div>
				<PanelBody title={__('Block options', 'shipping-workshop')}>
					{/* Option to edit the section title */}
					<RichText
						value={
							text ||
							defaultTsectionText ||
							__('T-shirt Option Section Title', 'shipping-workshop')
						}
						onChange={(value) => setAttributes({ text: value })}
					/>
					<Disabled>
						<TextControl
							placeholder={__('T-shirt Title', 'extra-workshop')}
						/>
						<TextControl
							placeholder={__('T-shirt Desc', 'extra-workshop')}
						/>
						<RadioControl
							label={__('T-shirt Size', 'shipping-workshop')}
							options={sizeOptions}
							onChange={(value) => setTshirtSize(value)}
						/>
						<SelectControl
							placeholder={__('T-shirt Color', 'shipping-workshop')}
							options={colorOptions}
							onChange={(value) => setTshirtColor(value)}
						/>
					</Disabled>

				</PanelBody>

			</div>
		</div>
	);
};

export const Save = ({ attributes }) => {
	const { text } = attributes;
	return (
		<div {...useBlockProps.save()}>
			<RichText.Content value={text} />
		</div>
	);
};
