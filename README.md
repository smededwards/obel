# Obel

A block portfolio theme using native HTML block templates, editable template parts and core-block page content.

Develop alongside `portfolio`, `obel`, `aeon` and `rune`. Use the site Makefile to run all tools inside containers. This repository has its own Composer lock and checks.

Run `npm ci`, `npm run build`, `npm run start`, `npm run lint:js` and `npm run lint:scss` inside the Node container. Generated `build/` assets are assembled in site CI, not committed.

Run `composer install` and `composer lint` in the PHP tools container. CI also syntax-checks PHP.

For release, publish this repository separately and create a semantic version tag after checks pass. The site must require a real tag and update its production lock. Do not deploy a local path dependency.

## Media sizes

Obel keeps three generated core sizes: a 150×150 cropped Thumbnail for media previews, Medium within 640×640, and Large within 1120×1120. Medium and Large preserve the source aspect ratio; WordPress does not upscale smaller uploads. The full-size image remains available to responsive images, including higher-density displays. WordPress’s normal handling of oversized originals is preserved.

`class-images.php` excludes the overlapping core `medium_large`, `1536x1536` and `2048x2048` sizes from generation while preserving any additional sizes registered by plugins. On theme activation it sets the native Settings → Media dimensions and the default editor insertion size to Large. Administrators can subsequently adjust the Media settings; nothing rewrites them on each request. Existing media needs regeneration after changing sizes.

## Contribution conventions

Use Conventional Commit subjects and PR titles, for example `feat(theme): add navigation` or `fix(blocks): correct editor styles`. The repository's first commit is the explicit exception: `Initial Commit`. PR CI validates titles and every commit in the PR. See `AGENTS.md` for repository guidance.

## Contact form styling

The native Rune Contact form uses `src/scss/components/_contact-form.scss` for BEM field, button, focus and validation styles. These styles are shared by the frontend and editor, adapt to the theme colours and retain accessible focus states. Submission handling belongs to Aeon; surrounding page content remains native blocks.

## Work and Projects pages

Use standard Pages, with individual entries as child pages of Work or Projects. Their archive cards are native Query Loop blocks scoped to the parent page, ordered by Page Attributes → Order. Edit the blocks in the page editor; use Page List when only a navigation list is needed.

Homepage cards use native Query Loop blocks querying Pages, filtered to the appropriate parent, ordered by Page Attributes → Order and limited to three entries. Featured Image, Post Title and Post Excerpt blocks inside the Post Template control the cards. All of these blocks, their settings and their layout are editable in the dashboard. No custom shortcode, renderer, page-collection template or custom post type is required.

## Homepage editing

Edit **Pages → Home** in the dashboard. Its introduction, headings, text, links and section groups are saved in WordPress and can be edited or reordered there. The theme renders that saved content through the native Post Content block; there is no hard-coded homepage content file or fallback copy.

Work and Projects cards remain live views of their child pages. Edit each child’s title, Featured Image and Excerpt to change its card, and Page Attributes → Order to change its position. Their native Query Loop blocks can be configured, moved or removed with the surrounding section groups.

## SCSS formatting

Use expanded blocks with tab indentation, one declaration per line and one selector per line in grouped selectors. Separate sibling rules with a blank line, with no empty line directly inside braces. This applies equally to single-declaration rules, media queries and nested BEM selectors. Keep the existing property order and styling choices.

WordPress's SCSS stylistic preset, the local Stylelint overrides and `.editorconfig` enforce this format. Run `npm run lint:scss` to check it or `npm run lint:scss:fix` to apply corrections. CI runs the same check.

## Motion

The homepage Work/Projects cards and the Work, Projects and Posts archives use a one-time 28px rise and fade lasting 450ms. `src/js/motion.js` observes offscreen cards as they enter view. Cards already visible at page load remain static. Content has no hidden waiting state, so it remains readable without JavaScript or IntersectionObserver support.

Component motion styles use the shared `motion.enabled` mixin in `src/scss/utilities/_motion.scss`, which gates them behind `prefers-reduced-motion: no-preference`. Shared reveal keyframes and lifecycle classes remain in that utility. A changed preference cancels active reveals and disconnects observers. Keyboard focus immediately cancels a card reveal, and focus outlines remain instant. Link text transitions to a contrast-safe gold on hover over 160ms; toggle icon colours and submit-button borders also transition over 160ms; card images scale to 1.02 over 180ms on hover-capable fine pointers. Text/background swaps remain instant to maintain contrast.

Run `npm run build` followed by `npm run test:motion` for the motion regression checks. They cover one-time reveals, reduced-motion changes, keyboard focus, unsupported observers, back/forward restoration and reduced-motion gating in the compiled CSS. JavaScript and SCSS lint checks pass; existing JavaScript spacing has been aligned with the WordPress formatter.

## Archive and detail layouts

Work and Projects use image-led archive cards. Posts uses editorial rows with an image, title, date and excerpt. These layouts use core Query Loop, Post Template, Featured Image, Post Title, Post Date, Post Excerpt and Pagination blocks, with BEM classes for theme styling. Work and Projects listing content remains in its page editor. The Posts index query and shared detail layouts are now supplied by block templates.

The Posts index uses `templates/home.html`, with an inherited Query Loop so WordPress controls chronology and pagination. Its previous page content is retained but is no longer the source of the index layout. Posts use `templates/single.html`. Work and Projects child pages use the selectable Work entry and Project entry templates, with shared editorial title/image styling and a narrow content column. Featured images come from the Media Library and article bodies remain native editor blocks. Both colour modes and responsive layouts are supported.


## Site editing and navigation

Use **Appearance → Editor → Templates** for page frames, archive layouts and the Posts index; edit Header and Footer through the editor's template parts. Page and post bodies remain in their normal editors. Frontend and editor canvases share the same SCSS presentation. The colour toggle is a core Button block with a real button element; its frontend behaviour and icon are supplied by the theme.

Use **Appearance → Editor → Navigation → Primary navigation** to add, remove or reorder menu links. Move the Navigation block within the Header template part to change its placement, and select another saved menu through its native controls. The old PHP navigation fallback and primary classic-menu location are removed. No custom navigation renderer or custom block is introduced.

The local migration created a native `wp_navigation` entity and stored its ID in `obel_primary_navigation`; the hidden `obel/site-navigation` pattern supplies that reference without hard-coding a database ID in the theme. Existing navigation entities and page bodies are preserved. For a fresh installation, choose or create a menu in the Header's Navigation block; it can be saved through the Site Editor without the migration option. Existing local Work/Projects child pages were assigned their respective custom templates; choose the relevant template for new child pages using the page editor's Template setting.

Editor saves of templates and template parts live in the database and override theme files. Export intentional shared design changes back into the theme when they should be version-controlled; migrate database customisations alongside content when launching. Core WordPress supplies document markup, head/footer hooks and the skip link. HTML templates include a single main landmark with the `main` anchor.


## Asset and SCSS structure

`src/scripts.js` imports the shared `src/styles.scss` entry and frontend behaviour. Webpack emits `build/scripts.js` and `build/styles.css`; WordPress loads the script using its generated dependency/version metadata and versions the stylesheet by its own modification time, so CSS-only rebuilds also invalidate the browser cache. `src/js/editor.js` imports the shared style entry and then `src/scss/editor.scss` for editor-specific canvas defaults. Supporting JavaScript lives in `src/js/`, including colour-toggle and motion behaviour; only `scripts.js` and `styles.scss` sit directly in `src/`.

SCSS lives under `src/scss/`, organised into `foundations/` (fonts, colour tokens, element defaults), `layouts/` (shared page/grid/block layouts), `components/` (one partial per BEM block) and `utilities/` (shared reduced-motion-aware enhancements). Component partials own their elements, modifiers, hover/focus states and responsive rules. Use shallow `&__element`, `&--modifier` and `&:hover` nesting while preserving WordPress's native block classes. The Navigation component explicitly owns the gold link hover, native overlay layout and 323px compact-menu breakpoint; the Header component owns the logo/toggle rows and navigation placement.

Linked core Image and Featured Image blocks draw the standard keyboard focus ring on the outer image block, avoiding clipping from image-zoom overflow. `src/scss/utilities/_focus.scss` shares the 3px theme-accent outline and 5px offset between standard controls and `src/scss/components/_linked-image.scss`. The ring appears only while the image link matches `:focus-visible`.

Rune's editor colour toggle can override the canvas preview using `--rune-canvas-*` variables. Obel's editor tokens consume those variables with existing light-colour fallbacks; public frontend colours and stored block styles are unaffected.
