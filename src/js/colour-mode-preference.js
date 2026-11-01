try {
	const preference = localStorage.getItem( 'obel-colour-mode' );
	if ( preference === 'light' || preference === 'dark' ) {
		document.documentElement.dataset.colourMode = preference;
	}
} catch {
	// Storage may be unavailable; CSS retains the system preference.
}
