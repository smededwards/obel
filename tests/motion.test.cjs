const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const test = require( 'node:test' );
const vm = require( 'node:vm' );
const postcss = require( 'postcss' );

const source = fs.readFileSync( path.join( __dirname, '../src/motion.js' ), 'utf8' );

function harness( { motion = true, observerAvailable = true } = {} ) {
	const listeners = {};
	const observers = [];
	const cards = [ 100, 1000, 1500 ].map( ( top ) => {
		const classes = new Set();
		return {
			classes,
			focused: false,
			classList: {
				add: ( value ) => classes.add( value ),
				remove: ( value ) => classes.delete( value ),
			},
			getBoundingClientRect: () => ( { top, width: 320, height: 200 } ),
			matches() { return this.focused; },
			closest() { return classes.has( 'card-reveal' ) ? this : null; },
		};
	} );
	const preference = {
		matches: motion,
		addEventListener: ( event, callback ) => { listeners.preference = callback; },
	};
	class Observer {
		constructor( callback ) {
			this.callback = callback;
			this.targets = new Set();
			observers.push( this );
		}
		observe( card ) { this.targets.add( card ); }
		unobserve( card ) { this.targets.delete( card ); }
		disconnect() { this.targets.clear(); }
		enter( card ) { this.callback( [ { target: card, isIntersecting: true } ] ); }
	}
	vm.runInNewContext( source, {
		document: {
			querySelectorAll: () => cards,
			addEventListener: ( event, callback ) => { listeners[ event ] = callback; },
		},
		window: {
			innerHeight: 800,
			matchMedia: () => preference,
			IntersectionObserver: observerAvailable ? Observer : undefined,
			addEventListener: ( event, callback ) => { listeners[ event ] = callback; },
		},
	} );
	return { cards, observers, listeners, preference };
}

const animated = ( card ) => card.classes.has( 'card-reveal--enter' );

test( 'visible cards remain static; offscreen cards reveal once', () => {
	const { cards, observers, listeners } = harness();
	assert.equal( observers[ 0 ].targets.has( cards[ 0 ] ), false );
	assert.equal( cards.some( animated ), false );
	observers[ 0 ].enter( cards[ 1 ] );
	assert.equal( animated( cards[ 1 ] ), true );
	listeners.animationend( { animationName: 'obel-card-enter', target: cards[ 1 ] } );
	assert.equal( animated( cards[ 1 ] ), false );
	observers[ 0 ].enter( cards[ 1 ] );
	assert.equal( animated( cards[ 1 ] ), false );
} );

test( 'reduced motion starts static and cancels an in-progress reveal', () => {
	const { cards, observers, listeners, preference } = harness( { motion: false } );
	assert.equal( observers.length, 0 );
	assert.equal( cards.some( animated ), false );
	preference.matches = true;
	listeners.preference();
	observers[ 0 ].enter( cards[ 1 ] );
	assert.equal( animated( cards[ 1 ] ), true );
	preference.matches = false;
	listeners.preference();
	assert.equal( cards.some( animated ), false );
	assert.equal( observers[ 0 ].targets.size, 0 );
	preference.matches = true;
	listeners.preference();
	assert.equal( observers[ 1 ].targets.has( cards[ 1 ] ), false );
} );

test( 'keyboard focus reveals immediately, including queued observer callbacks', () => {
	const { cards, observers, listeners } = harness();
	cards[ 1 ].focused = true;
	listeners.focusin( { target: cards[ 1 ] } );
	observers[ 0 ].enter( cards[ 1 ] );
	assert.equal( animated( cards[ 1 ] ), false );
	observers[ 0 ].enter( cards[ 2 ] );
	assert.equal( animated( cards[ 2 ] ), true );
	cards[ 2 ].focused = true;
	listeners.focusin( { target: cards[ 2 ] } );
	assert.equal( animated( cards[ 2 ] ), false );
} );

test( 'unsupported observers leave content untouched', () => {
	const { cards, observers } = harness( { observerAvailable: false } );
	assert.equal( observers.length, 0 );
	assert.equal( cards.every( ( card ) => card.classes.size === 0 ), true );
} );

test( 'back-forward restoration does not replay revealed cards', () => {
	const { cards, observers, listeners } = harness();
	observers[ 0 ].enter( cards[ 1 ] );
	listeners.pageshow( { persisted: true } );
	assert.equal( animated( cards[ 1 ] ), false );
	assert.equal( observers[ 1 ].targets.has( cards[ 1 ] ), false );
} );

test( 'compiled animations and transitions require no-preference; opacity zero is confined to keyframes', () => {
	const css = postcss.parse( fs.readFileSync( path.join( __dirname, '../build/frontend.css' ), 'utf8' ) );
	let effects = 0;
	css.walkDecls( ( declaration ) => {
		if ( /^(animation|transition)(-|$)/.test( declaration.prop ) || ( declaration.prop === 'opacity' && declaration.value === '0' ) ) {
			let parent = declaration.parent;
			let gated = false;
			let keyframe = false;
			while ( parent ) {
				if ( parent.type === 'atrule' && parent.name === 'media' && /prefers-reduced-motion\s*:\s*no-preference/.test( parent.params ) ) { gated = true; }
				if ( parent.type === 'atrule' && parent.name.endsWith( 'keyframes' ) ) { keyframe = true; }
				parent = parent.parent;
			}
			assert.equal( gated, true, `Ungated motion: ${ declaration.toString() }` );
			if ( declaration.prop === 'opacity' && declaration.value === '0' ) { assert.equal( keyframe, true, 'Content must never wait in a hidden state.' ); }
			++effects;
		}
	} );
	assert.ok( effects > 0 );
} );
