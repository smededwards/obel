// Apply the saved preference before rendering; CSS handles the system default.
try {
	const preference = localStorage.getItem( 'obel-colour-mode' );
	if ( preference === 'light' || preference === 'dark' ) {
		document.documentElement.dataset.colourMode = preference;
	}
} catch {
	// Storage may be unavailable; retain the system preference.
}
