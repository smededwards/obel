# Obel

A hybrid portfolio theme using PHP templates and editable core-block page content.

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

The native Rune Contact form uses `src/_contact.scss` for BEM field, button, focus and validation styles. These styles are shared by the frontend and editor, adapt to the theme colours and retain accessible focus states. Submission handling belongs to Aeon; surrounding page content remains native blocks.

## Work and Projects pages

Use standard Pages, with individual entries as child pages of Work or Projects. Their archive cards are native Query Loop blocks scoped to the parent page, ordered by Page Attributes → Order. Edit the blocks in the page editor; use Page List when only a navigation list is needed.

Homepage cards use native Query Loop blocks querying Pages, filtered to the appropriate parent, ordered by Page Attributes → Order and limited to three entries. Featured Image, Post Title and Post Excerpt blocks inside the Post Template control the cards. All of these blocks, their settings and their layout are editable in the dashboard. No custom shortcode, renderer, page-collection template or custom post type is required.

## Homepage editing

Edit **Pages → Home** in the dashboard. Its introduction, headings, text, links and section groups are saved in WordPress and can be edited or reordered there. The theme renders that saved content through `the_content()`; there is no hard-coded homepage content file or fallback copy.

Work and Projects cards remain live views of their child pages. Edit each child’s title, Featured Image and Excerpt to change its card, and Page Attributes → Order to change its position. Their native Query Loop blocks can be configured, moved or removed with the surrounding section groups.

## SCSS formatting

Use expanded blocks with tab indentation, one declaration per line and one selector per line in grouped selectors. Separate sibling rules with a blank line, with no empty line directly inside braces. This applies equally to single-declaration rules, media queries and nested BEM selectors. Keep the existing property order and styling choices.

WordPress's SCSS stylistic preset, the local Stylelint overrides and `.editorconfig` enforce this format. Run `npm run lint:scss` to check it or `npm run lint:scss:fix` to apply corrections. CI runs the same check.

## Motion

The homepage Work/Projects cards and the Work, Projects and Posts archives use a one-time 28px rise and fade lasting 450ms. `src/motion.js` observes offscreen cards as they enter view. Cards already visible at page load remain static. Content has no hidden waiting state, so it remains readable without JavaScript or IntersectionObserver support.

All motion styles in `src/_motion.scss` are gated by `prefers-reduced-motion: no-preference`. A changed preference cancels active reveals and disconnects observers. Keyboard focus immediately cancels a card reveal, and focus outlines remain instant. Link text transitions to a contrast-safe gold on hover over 160ms; toggle backgrounds and submit-button borders also transition over 160ms; card images scale to 1.02 over 180ms on hover-capable fine pointers. Text/background swaps remain instant to maintain contrast.

Run `npm run build` followed by `npm run test:motion` for the motion regression checks. They cover one-time reveals, reduced-motion changes, keyboard focus, unsupported observers, back/forward restoration and reduced-motion gating in the compiled CSS. JavaScript and SCSS lint checks pass; existing JavaScript spacing has been aligned with the WordPress formatter.

## Archive and detail layouts

Work and Projects use image-led archive cards. Posts uses editorial rows with an image, title, date and excerpt. These layouts use core Query Loop, Post Template, Featured Image, Post Title, Post Date, Post Excerpt and Pagination blocks, with BEM classes for theme styling. They are saved in the database rather than generated by the theme. A fresh installation needs these blocks configured in the corresponding page editors; the theme does not seed or replace page content.

The Posts index renders the content saved in the configured Posts page. Its Query Loop should inherit the archive query so WordPress controls chronology and pagination. Posts and direct children of Work/Projects have shared editorial title/image styling and a narrow content column. Featured images come from the Media Library and article bodies remain native editor blocks. Both colour modes and responsive layouts are supported.
