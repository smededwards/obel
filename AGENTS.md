# Repository conventions

- Make and test changes locally. Wait for explicit code approval before committing, pushing or opening/updating a PR.
- Use lowercase, hyphenated WordPress-style PHP filenames. Organise PHP classes in responsibility-based directories directly under the project root, without a PHP `src/` wrapper. Use namespaces and separate classes for new custom PHP functionality, with Composer classmap autoloading where needed.
- Use sentence case for package names: Aeon, Obel and Rune. Keep lowercase code identifiers and package slugs unchanged.
- Use Conventional Commits for every new commit and PR title: `type(scope): description`, with an optional scope and optional `!` for breaking changes.
- Accepted types: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`, `revert`.
- The sole exception is the repository's first commit, whose subject is exactly `Initial Commit`.
- Keep PR descriptions focused on the core premise and features, using one short paragraph or a few brief bullets plus a short validation line. Omit implementation inventories and work logs unless necessary for review. Use British English in documentation.
- Never commit secrets, certificates, local environment files, uploads or generated dependency directories.

## Branch and release workflow

- Create focused feature branches from the latest `dev`; target feature PRs at `dev` and squash-merge using their Conventional Commit titles.
- Release connected batches through a `dev` to `main` PR using a merge commit with a Conventional Commit subject. Preserve feature history and fast-forward `dev` to released `main` before new work.
- Version each repository independently. Tag only the approved release commit on `main`; published tags are immutable. Releases and deployments require explicit agreement.
- Keep `main` as the GitHub default branch. Avoid stacked PRs unless agreed; merge dependencies into `dev` first.
- The local approval requirement above applies before publishing new changes.

- Prefer returned markup built with `sprintf()` in PHP helpers. Use escaped `printf()` only at template or WordPress callback output boundaries, and `fwrite(STDOUT, ...)` in standalone CLI scripts; avoid direct `echo` statements. Escape dynamic values for their actual HTML, attribute or URL context. Formatting and returning values do not replace validation, capability checks or escaping.

- Use BEM for custom HTML and SCSS classes: `block`, `block__element`, `block--modifier` or `block__element--modifier`. Preserve WordPress-owned classes and keep block metadata, saved markup and selectors aligned when renaming classes.

- Prefer native WordPress blocks and editor controls wherever they meet the requirement. Use Page List for page navigation and Query Loop for dynamic card layouts; introduce custom blocks, shortcodes or renderers only for an agreed gap in core.

## SCSS formatting

- Follow the expanded formatting in the current SCSS sources, including rules with just one declaration. Put the opening brace on the selector line, declarations on separate indented lines, and the closing brace on its own line. Never compress a rule into one line.
- Indent with tabs (display width 4), use LF line endings and retain a final newline. Remove trailing whitespace.
- Put every selector in a comma-separated selector list on its own line, including nested selectors and pseudo-classes.
- Use one blank line between sibling rules or sections. Do not add a blank line immediately after an opening brace or before a closing brace, including in media queries, mixins and nested rules.
- Use a space after declaration colons, trailing semicolons, and no padding inside function parentheses. Preserve meaningful declaration order; do not reorder properties as part of formatting.
- Organise SCSS by responsibility: `src/scss/foundations/` for fonts, colour tokens and element defaults; `src/scss/layouts/` for shared layout integration; `src/scss/components/` for BEM blocks; `src/scss/utilities/` for shared behaviour such as motion. Keep only `scripts.js` and `styles.scss` directly in `src/`. Place supporting JavaScript in `src/js/` and SCSS in `src/scss/`; the frontend and editor JavaScript entries import the shared root style entry.
- Keep each BEM component’s elements, modifiers, interaction states and responsive rules in its own partial. Use `&__element`, `&--modifier` and nested `&:hover` / `&:focus-visible` where appropriate, with shallow nesting. Preserve native WordPress classes and avoid files for individual BEM elements.
- Preserve compiled selector specificity and intentional cascade order during refactors; verify frontend and editor asset paths after entry-point renames.
- `npm run lint:scss` enforces this style through WordPress's SCSS stylistic preset and the local overrides. Use `npm run lint:scss:fix` for formatting corrections, and check the diff to preserve the author's values and layout choices.
