# Obel

Obel is a WordPress block theme for the portfolio. It uses HTML templates, editable template parts and core blocks for page content.

## Name

Obel is shortened from *obelisk*. Its architectural associations of structure and permanence suit a theme that provides a restrained foundation for a site and remains useful on its own.

Develop Obel alongside `portfolio`, `aeon` and `rune`. The site Makefile runs tools in containers. Obel has its own Composer lock and checks.

Run `npm ci`, `npm run build`, `npm run start`, `npm run lint:js` and `npm run lint:scss` in the Node container. Site CI builds the generated assets; `build/` files are not committed.

Run `composer install` and `composer lint` in the PHP tools container. CI also syntax-checks PHP.

After checks pass, publish Obel separately and create a semantic version tag. Update the site’s production lock to use that tag. Production must use tagged releases rather than local path dependencies.

## Media Sizes

Obel generates three core image sizes: a cropped 150×150 Thumbnail, Medium up to 640×640 and Large up to 1120×1120. Medium and Large preserve the source aspect ratio. WordPress does not enlarge smaller uploads. The full-size original remains available for responsive images and higher-density displays, and WordPress handles oversized originals as usual.

`class-images.php` skips the overlapping core `medium_large`, `1536x1536` and `2048x2048` sizes while preserving sizes added by plugins. When the theme is activated, it sets the Settings → Media dimensions and default editor insertion size to Large. Administrators can change these settings later; Obel does not reset them on each request. Regenerate existing media after changing the sizes.

## Contribution Conventions

Use Conventional Commit subjects and PR titles, such as `feat(theme): add navigation` or `fix(blocks): correct editor styles`. Name the first commit `Initial Commit`. PR CI checks titles and commits. See `AGENTS.md` for details.

## Contact Form Styling

Rune’s Contact Form uses `src/scss/components/_contact-form.scss` for its BEM field, button, focus and validation styles. The frontend and editor share the styles, which use the theme colours and visible focus states. Aeon handles submissions; the rest of the page uses native blocks.

## Work and Projects Pages

Create Work and Projects as standard Pages, then add each entry as a child Page. Their archives use native Query Loop blocks scoped to the parent and sorted by Page Attributes → Order. Edit the loops in the page editor. Use Page List for simple navigation lists.

Homepage cards use native Query Loop blocks to show up to three child Pages, sorted by Page Attributes → Order. Featured Image, Post Title and Post Excerpt blocks in the Post Template make up each card. Edit the query, blocks and layout in the dashboard.

## Homepage Editing

Edit the homepage in **Pages → Home**. Its introduction, headings, links and sections are saved in WordPress and can be edited or reordered in the editor. The template displays this content with the native Post Content block.

Homepage Work and Projects cards draw from their child Pages. Change a card by editing its Page title, Featured Image or Excerpt. Set its position with Page Attributes → Order. The Query Loop blocks can be edited or moved with their section groups. A responsive decorative brand mark sits behind the homepage introduction and is part of the theme design.

## SCSS Formatting

Format SCSS with expanded rules, tab indentation and one declaration per line. Put each selector in a grouped selector list on its own line. Separate sibling rules with one blank line; leave no blank lines just inside braces. Apply the same format to single-declaration rules, media queries and nested BEM selectors. Group properties in this order: box model, background, layout, type, then other. Alphabetise properties within each group and separate groups with a blank line. Do not add group-label comments. Keep dependent fallbacks and shorthand/longhand declarations together in the order they require.

WordPress’s SCSS stylistic preset, local Stylelint overrides and `.editorconfig` enforce the format. Run `npm run lint:scss` to check it or `npm run lint:scss:fix` to apply formatting changes. CI runs the lint check.

## Motion

Homepage Work and Projects cards and cards in the Work, Projects and Posts archives rise 28px and fade in over 450ms when they enter the viewport. `src/js/motion.js` observes cards that begin offscreen. Cards already visible on page load stay still. Without JavaScript or IntersectionObserver support, all content remains visible.

The shared `motion.enabled` mixin in `src/scss/utilities/_motion.scss` enables component animation only when `prefers-reduced-motion: no-preference`. Reveal keyframes and lifecycle classes are defined there. If the motion preference changes, active reveals stop and observers disconnect. Keyboard focus also stops a reveal; focus outlines appear immediately. On hover, link text changes to gold over 160ms, toggle icons and submit-button borders transition over 160ms, and card images scale to 1.02 over 180ms on fine pointers. Text and background colour-mode changes are immediate to preserve contrast.

Run `npm run build` and `npm run test:motion` for the motion regression checks. They cover one-time reveals, reduced-motion changes, keyboard focus, unsupported observers, browser history restoration and reduced-motion rules in the compiled CSS. Run the JavaScript and SCSS lint checks as well.

## Archive and Detail Layouts

Work and Projects use image-led archive cards. The Posts archive lists titles, dates and excerpts without repeating the placeholder image. Category and tag archives use the same Query Loop layout and include the term description. Core Query Loop, Post Template, Post Title, Post Date, Post Excerpt, Term Description and Pagination blocks provide the layouts; BEM classes supply the theme styles. Edit Work and Projects listing content in their Pages. Block templates define the Posts query and shared detail layouts.

`templates/home.html` contains the Posts index and its inherited Query Loop, which uses WordPress chronology and pagination. The Page content remains saved, but the template controls the index layout. Posts use `templates/single.html`. Category and tag archives use `templates/category.html` and `templates/tag.html`, which share `parts/archive-content.html`. Work and Projects child Pages use their selectable entry templates, with shared title and image styling and a narrow content column. Featured images come from the Media Library; post and page content uses native editor blocks. Layouts support both colour modes and responsive screens.


## Site Editing and Navigation

Edit page frames, archive layouts and the Posts index in **Appearance → Editor → Templates**. Edit the Header and Footer in their template parts. Page and post content is edited in its usual editor. The frontend and editor use the same SCSS. The colour toggle is a core Button block; Obel supplies its icon and frontend behaviour.

Manage links in **Appearance → Editor → Navigation → Primary navigation**. Add, remove or reorder links there. Move the Navigation block in the Header template part to change its position, or select another saved menu using the block controls. The header uses WordPress’s native Navigation block.

The local migration created a `wp_navigation` entity and saved its ID in `obel_primary_navigation`. The hidden `obel/site-navigation` pattern reads that option so the theme does not contain a fixed database ID. Existing navigation entities and page content are preserved. On a fresh site, choose or create a menu in the Header’s Navigation block and save it in the Site Editor. Choose the Work entry or Project entry template for each new child Page in its Template setting.

The Site Editor saves templates and template parts in the database, where they take precedence over theme files. Export shared design changes to the theme when they should be version-controlled. Move database customisations with site content when launching. WordPress supplies document markup, head and footer hooks, and the skip link. HTML templates contain one main landmark with the `main` anchor.


## Asset and SCSS Structure

`src/scripts.js` imports `src/styles.scss` and the frontend behaviour. Webpack emits `build/scripts.js` and `build/styles.css`. WordPress reads the generated script dependency metadata and versions the stylesheet using its modification time, so CSS-only builds refresh the browser cache. `src/js/editor.js` imports the shared styles and `src/scss/editor.scss` for editor canvas defaults. Supporting JavaScript, including the colour toggle and motion code, lives in `src/js/`. Only `scripts.js` and `styles.scss` sit directly in `src/`.

SCSS under `src/scss/` is grouped into `foundations/` for fonts, colour tokens and element defaults; `layouts/` for shared page, grid and block layouts; `components/` for BEM blocks; and `utilities/` for shared behaviour, including reduced-motion handling. Each component partial contains its elements, modifiers, interaction states and responsive rules. Use shallow `&__element`, `&--modifier` and `&:hover` nesting, and retain WordPress’s native block classes. The Navigation component handles the gold link hover, overlay layout and 323px compact-menu breakpoint. The Header component defines the logo and toggle rows and navigation placement.

Linked Image and Featured Image blocks show the keyboard focus ring on the outer block, so image zoom does not clip it. `src/scss/utilities/_focus.scss` and `src/scss/components/_linked-image.scss` use a 3px theme-accent outline with a 5px offset. The ring appears when the link matches `:focus-visible`.

Rune’s editor colour toggle sets the canvas preview through the `--rune-canvas-*` variables. Obel’s editor tokens use those values, with light-colour fallbacks when Rune is inactive. This does not alter frontend colours or saved block styles.
