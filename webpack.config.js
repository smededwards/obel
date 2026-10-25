const defaults = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaults,
	entry: {
		frontend: path.resolve( __dirname, 'src/frontend.js' ),
		editor: path.resolve( __dirname, 'src/editor.js' ),
	},
};
