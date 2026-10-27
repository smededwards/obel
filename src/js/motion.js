/**
 * Enhance offscreen cards without hiding content while waiting for JavaScript.
 */
const initialiseCardMotion = () => {
	if (
		typeof window.IntersectionObserver !== 'function' ||
		typeof window.matchMedia !== 'function'
	) {
		return;
	}

	const cards = Array.from(
		document.querySelectorAll(
			'.work-grid > li, .collection-grid > li, .archive-list > li'
		)
	);
	if ( ! cards.length ) {
		return;
	}

	const preference = window.matchMedia(
		'(prefers-reduced-motion: no-preference)'
	);
	const seen = new WeakSet();
	let observer;

	const finish = ( card ) => {
		seen.add( card );
		observer?.unobserve( card );
		card.classList.remove( 'card-reveal--enter' );
	};

	const prepare = () => {
		observer?.disconnect();
		cards.forEach( ( card ) =>
			card.classList.remove( 'card-reveal--enter' )
		);
		if ( ! preference.matches ) {
			return;
		}

		observer = new window.IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					if ( ! entry.isIntersecting ) {
						return;
					}
					const card = entry.target;
					observer.unobserve( card );
					if ( seen.has( card ) || ! preference.matches ) {
						return;
					}
					seen.add( card );
					if ( ! card.matches( ':focus-within' ) ) {
						card.classList.add( 'card-reveal--enter' );
					}
				} );
			},
			{ rootMargin: '0px', threshold: 0 }
		);

		cards.forEach( ( card ) => {
			card.classList.add( 'card-reveal' );
			const bounds = card.getBoundingClientRect();
			if (
				seen.has( card ) ||
				! bounds.width ||
				! bounds.height ||
				bounds.top <= window.innerHeight
			) {
				seen.add( card );
				return;
			}
			observer.observe( card );
		} );
	};

	document.addEventListener( 'focusin', ( event ) => {
		const card = event.target.closest?.( '.card-reveal' );
		if ( card ) {
			finish( card );
		}
	} );
	document.addEventListener( 'animationend', ( event ) => {
		if ( event.animationName === 'obel-card-enter' ) {
			event.target.classList.remove( 'card-reveal--enter' );
		}
	} );
	preference.addEventListener( 'change', prepare );
	window.addEventListener( 'pageshow', ( event ) => {
		if ( event.persisted ) {
			prepare();
		}
	} );
	prepare();
};

initialiseCardMotion();
