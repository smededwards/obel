const toggle = document.querySelector( '.colour-toggle button' );
const system = window.matchMedia( '(prefers-color-scheme: dark)' );

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
if ( toggle ) {
	toggle.hidden = false;
	toggle.addEventListener( 'click', () => {
		const mode = isDark() ? 'light' : 'dark';
		document.documentElement.dataset.colourMode = mode;
		try {
			localStorage.setItem( 'obel-colour-mode', mode );
		} catch {
			// The toggle still works when storage is unavailable.
		}
		update();
	} );
	update();
	system.addEventListener( 'change', update );
}
