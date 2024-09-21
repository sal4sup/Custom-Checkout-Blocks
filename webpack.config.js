const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const WooCommerceDependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// Remove SASS rule from the default config so we can define our own.
const defaultRules = defaultConfig.module.rules.filter((rule) => {
	return String(rule.test) !== String(/\.(sc|sa)ss$/);
});

module.exports = {
	...defaultConfig,
	entry: {
		index: path.resolve(process.cwd(), 'src', 'js', 'index.js'),
		// Existing blocks
		'shipping-workshop-block': path.resolve(
			process.cwd(),
			'src',
			'js',
			'shipping-workshop-block',
			'index.js'
		),
		'shipping-workshop-block-frontend': path.resolve(
			process.cwd(),
			'src',
			'js',
			'shipping-workshop-block',
			'frontend.js'
		),
		// New entry for the extra-workshop-block
		'extra-workshop-block': path.resolve(
			process.cwd(),
			'src',
			'js',
			'extra-workshop-block',
			'index.js'  // Ensure this file exists for extra-workshop-block
		),
		'extra-workshop-block-frontend': path.resolve(
			process.cwd(),
			'src',
			'js',
			'extra-workshop-block',
			'frontend.js' // Assuming you have a frontend.js for extra-workshop-block as well
		),
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultRules,
			{
				test: /\.(sc|sa)ss$/,
				exclude: /node_modules/,
				use: [
					MiniCssExtractPlugin.loader,
					{ loader: 'css-loader', options: { importLoaders: 1 } },
					{
						loader: 'sass-loader',
						options: {
							sassOptions: {
								includePaths: ['src/css'],
							},
						},
					},
				],
			},
		],
	},
	plugins: [
		// Remove the DependencyExtractionWebpackPlugin from WordPress scripts
		...defaultConfig.plugins.filter(
			(plugin) =>
				plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
		),
		// Add WooCommerce Dependency Extraction Plugin
		new WooCommerceDependencyExtractionWebpackPlugin(),
		// MiniCssExtractPlugin for handling the CSS files
		new MiniCssExtractPlugin({
			filename: `[name].css`,
		}),
	],
};
