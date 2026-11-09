try {
	const preference = localStorage.getItem( 'obel-colour-mode' );
	if ( preference === 'light' || preference === 'dark' ) {
		document.documentElement.dataset.colourMode = preference;
	}
} catch {
	// Storage may be unavailable; CSS retains the system preference.
}

const system = window.matchMedia( '(prefers-color-scheme: dark)' );
const adaptiveImages = 'img[data-obel-adaptive-dark]';

/**
 * Set adaptive featured images to the active colour mode before they paint.
 *
 * @param {Node} target Added node or document to synchronise.
 * @return {void}
 */
const syncAdaptiveImages = ( target ) => {
	const mode =
		document.documentElement.dataset.colourMode ||
		( system.matches ? 'dark' : 'light' );
	const source = mode === 'dark' ? 'obelAdaptiveDark' : 'obelAdaptiveLight';
	const images = [];

	if ( target.matches?.( adaptiveImages ) ) {
		images.push( target );
	}
	target.querySelectorAll?.( adaptiveImages ).forEach( ( image ) => {
		images.push( image );
	} );

	images.forEach( ( image ) => {
		const imageSource = image.dataset[ source ];

		if ( imageSource && image.getAttribute( 'src' ) !== imageSource ) {
			image.src = imageSource;
		}
	} );
};

const observer = new window.MutationObserver( ( records ) => {
	records.forEach( ( { addedNodes } ) =>
		addedNodes.forEach( syncAdaptiveImages )
	);
} );

observer.observe( document.documentElement, {
	childList: true,
	subtree: true,
} );
syncAdaptiveImages( document );
