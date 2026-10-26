import './frontend.scss';
import './motion';

const toggle = document.querySelector( '[data-colour-toggle]' );
const system = window.matchMedia( '(prefers-color-scheme: dark)' );
const isDark = () =>
	( document.documentElement.dataset.colourMode ||
		( system.matches ? 'dark' : 'light' ) ) === 'dark';
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
