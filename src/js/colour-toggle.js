const toggle = document.querySelector( '.colour-toggle button' );
const system = window.matchMedia( '(prefers-color-scheme: dark)' );
const prefersReducedMotion = window.matchMedia(
	'(prefers-reduced-motion: reduce)'
);
const root = document.documentElement;

/**
 * Resolve the active mode from the saved choice or system preference.
 *
 * @return {boolean} Whether dark mode is active.
 */
const isDark = () =>
	( document.documentElement.dataset.colourMode ||
		( system.matches ? 'dark' : 'light' ) ) === 'dark';

/**
 * Keep the toggle's pressed state aligned with the active mode.
 *
 * @return {void}
 */
const update = () => {
	if ( toggle ) {
		toggle.setAttribute( 'aria-pressed', String( isDark() ) );
	}
};

/**
 * Apply and persist the selected colour mode.
 *
 * @param {string} mode Colour mode to apply.
 * @return {void}
 */
const applyMode = ( mode ) => {
	root.dataset.colourMode = mode;
	try {
		localStorage.setItem( 'obel-colour-mode', mode );
	} catch {
		// The toggle still works when storage is unavailable.
	}
	update();
};

if ( toggle ) {
	toggle.hidden = false;
	toggle.addEventListener( 'click', () => {
		const mode = isDark() ? 'light' : 'dark';
		if (
			! prefersReducedMotion.matches &&
			typeof document.startViewTransition === 'function'
		) {
			document.startViewTransition( () => applyMode( mode ) );
			return;
		}
		applyMode( mode );
	} );
	update();
	system.addEventListener( 'change', update );
}
