const defaults = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

module.exports = {
	...defaults,
	plugins: defaults.plugins.map( ( plugin ) =>
		plugin instanceof MiniCssExtractPlugin
			? new MiniCssExtractPlugin( {
					filename: ( { chunk } ) =>
						chunk.name === 'scripts' ? 'styles.css' : '[name].css',
				} )
			: plugin
	),
	entry: {
		scripts: path.resolve( __dirname, 'src/scripts.js' ),
		editor: path.resolve( __dirname, 'src/js/editor.js' ),
	},
};
