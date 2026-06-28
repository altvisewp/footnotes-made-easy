=== Footnotes Made Easy ===
Contributors: lumiblog, dartiss, manuell, ocenchris
Tags: footnotes, bibliography, formatting, reference, citations
Donate link: https://lumumbas.blog/support-wp-plugins
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 3.2.2-beta.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows post authors to easily add and manage footnotes in posts and pages.

== Description ==

Footnotes Made Easy is a simple but powerful plugin for adding footnotes to your WordPress posts and pages. Wrap any text in double parentheses, and it becomes a footnote; automatically numbered, linked, and displayed at the bottom of your content.

**Full documentation is available at [docs.altvisewp.com/footnotes-made-easy](https://docs.altvisewp.com/footnotes-made-easy/)**

**Key features:**

* Simple inline syntax — wrap text in `(( ))` to create a footnote anywhere in a post or page
* Combine identical footnotes automatically
* Paginated post support with manual start number control
* Reference a previous footnote by number using `((ref:1))`
* Pretty tooltips — show footnote content on hover using jQuery
* Suppress footnotes on specific page types (home, archives, search, feeds)
* Exclude footnotes from specific post categories or custom URLs
* Dashboard with live usage stats; footnote counts across all posts and pages
* Export/import Footnotes Made Easy settings across different sites
* Multisite support; network-managed mode or per-subsite override
* Compatible with the Classic Editor and the Gutenberg block editor
* Lots of configuration options

**Footnotes Made Easy is a fork of [WP Footnotes](https://github.com/drzax/wp-footnotes "Github - wp-footnotes"), a plugin by Simon Elvery.**

**For the latest code, planned enhancements and known issues, visit the [GitHub page](https://github.com/altvisewp/footnotes-made-easy/s "Github").**

== Getting Started ==

[youtube https://www.youtube.com/watch?v=Bl9p2-lSZMU]

Creating a footnote is simple; wrap your footnote text in double parentheses:

`This is a sentence ((and this is your footnote)).`

The footnote will appear at the bottom of your post or page, automatically numbered and linked.

**Important:** Include a space before your opening double parentheses or the footnote will not work.

== Settings ==

The settings page is organised into four tabs:

* **Display** — Control footnote identifier style, back-link format, header and footer text, and tooltip behaviour.
* **Behaviour** — Configure combining identical footnotes, back-link position, and processing priority.
* **Suppress** — Choose which page types (home, archives, search, feeds, previews) should not display footnotes. Also suppress by post type and exclude footnotes from specific URLs or post categories
* **Advanced** — Change the opening and closing delimiters.

== Paginated Posts ==

By default, each page of a paginated post restarts footnote numbering from 1. To maintain a continuous sequence, add a start number tag between each `<!--nextpage-->` marker:

`<!--startnum=5-->`

Replace `5` with the number you want the first footnote on that page to start at.

== Referencing ==

To reference a previous footnote a second time, you can either repeat the exact same text (recommended — works with the Combine Identical Footnotes option) or use the number reference syntax:

`((ref:1))`

Note: number referencing does not work across pages in a paginated post, and risks pointing to the wrong footnote if new footnotes are inserted before it. The exact-text method is more robust.

== Multisite Support ==

On WordPress multisite networks, the plugin can be configured from the network admin in two modes:

* **Network managed** — all settings controlled centrally; the Footnotes menu is hidden from subsite admins
* **Subsite override** — each subsite admin can manage their own footnote settings independently

== Available in 8 Languages ==

Footnotes Made Easy is fully internationalised and ready for translation.

**Thanks to the following translators:**

* [David Artiss](https://profiles.wordpress.org/dartiss/), English (UK)
* [Mark Robson](https://profiles.wordpress.org/markscottrobson/), English (UK)
* [Annabelle W](https://profiles.wordpress.org/yayannabelle/), English (UK)
* [maboroshin](https://profiles.wordpress.org/maboroshin/), Japanese
* [Laurent MILLET](https://profiles.wordpress.org/wplmillet/), French (France)
* [B. Cansmile Cha](https://profiles.wordpress.org/cansmile/), Korean
* [danbilabs](https://profiles.wordpress.org/danbilabs/), Korean
* [denelan](https://profiles.wordpress.org/danbilabs/), Dutch
* [Peter Smits](https://profiles.wordpress.org/psmits1567/), Dutch
* [Pieterjan Deneys](https://profiles.wordpress.org/nekojonez/), Dutch (Belgium)
* [Alex Grey](https://profiles.wordpress.org/alexvgrey/), Russian

**To add a translation, visit the [Translating WordPress](https://translate.wordpress.org/projects/wp-plugins/footnotes-made-easy "Translating WordPress") page.**

== Installation ==

Footnotes Made Easy can be found and installed via the Plugin menu within WordPress administration (Plugins → Add New). Alternatively, download from WordPress.org and install manually:

1. Upload the entire `footnotes-made-easy` folder to your `wp-content/plugins/` directory.
2. Activate the plugin through the Plugins menu in WordPress administration.

No configuration required — the plugin works immediately after activation with the default `(( ))` syntax.

== Frequently Asked Questions ==

= How do I add a footnote? =

Wrap your footnote text in double parentheses anywhere in your post or page content:

`This is a sentence ((and this is your footnote)).`

The plugin replaces the marker with a numbered superscript and appends the reference at the bottom of the post.

= Can I change the `(( ))` syntax to something else? =

Yes. Go to **Footnotes → Footnotes Settings → Advanced** and set custom opening and closing delimiters.

= Can I style the footnotes output? =

Yes. Use the CSS editor in your theme customiser. `ol.footnotes` targets the footnotes list and `ol.footnotes li` targets individual footnotes.

= Can I disable footnotes on specific pages or categories? =

Yes. The **Suppress** tab lets you disable footnotes on the home page, archives, search results, feeds, and previews. The **Advanced** tab lets you exclude specific URLs or post categories.

= Does the plugin work with the block editor (Gutenberg)? =

Yes. Add the `(( ))` syntax directly inside any text block.

= Does the plugin work on multisite? =

Yes. See the Multisite section above for configuration details.

= Does the plugin remove its data when uninstalled? =

Yes. Deleting the plugin via the WordPress admin removes all stored settings and user meta from the database. If you want to preserve settings for a future reinstall, enable the **Preserve settings on uninstall** option in the Tools page before deleting.

= Where can I find the full documentation? =

Full documentation is at [docs.altvisewp.com/footnotes-made-easy](https://docs.altvisewp.com/footnotes-made-easy/).

== Screenshots ==

1. Preview showing footnotes on a page
2. List at the bottom of the page
3. Behaviour settings
4. Display settings
5. Exclude specific URLs
6. Export/import footnote settings

== Changelog ==

I use semantic versioning, with the first release being 1.0.

= 3.2.1 [June 23, 2026] =
* Fixed: Duplicate footnote text appearing at the bottom of the page when Pretty Tooltips was enabled. [#51](https://github.com/altvisewp/footnotes-made-easy/issues/51)
* Fixed: Footnote font size appeared too large on themes that don't load the block library stylesheet. [#52](https://github.com/altvisewp/footnotes-made-easy/issues/52)

= 3.2.0 [June 12, 2026] =
* New: Fully redesigned admin UI with a dedicated top-level Footnotes menu (Dashboard, Footnotes Settings, Tools, and so much more)
* New: Dashboard page with live plugin usage statistics — footnote counts across all posts and pages
* New: Tabbed settings interface — Display, Behaviour, Suppress, and Advanced tabs
* New: Tools page with settings export and import (JSON), factory reset, and preserve-on-uninstall option
* New: Multisite support — network-managed mode and per-subsite override mode, configurable from network admin
* Enhancement: Suppress footnotes by post categories and by specific URL list
* Fix: HTML in the Footnotes Header and Footer fields (e.g. `<h2>References</h2>`) was being double-encoded on save, causing `&lt;h2&gt;` to appear in the textarea on subsequent edits. [Issue #39](https://github.com/altvisewp/footnotes-made-easy/issues/39).
* Enhancement: New Back-link position setting in Display → Back-links. Choose whether the back-link appears at the end (default, existing behaviour) or the beginning of each footnote. [Issue #5](https://github.com/altvisewp/footnotes-made-easy/issues/5).
* Fix: Footnotes Header and Footer text was not translated by WPML

= 3.1.0 [November 29, 2025] =
* Compatibility: WordPress 6.9 compatibility test passed

= 3.0.9 [November 8, 2025] =
* Fix: Footnotes header now correctly appears before the list [(not inside it)](https://wordpress.org/support/topic/version-3-0-8-moves-footnotes-header-inside-ol-tag/).
* Fix: [Restored 'footnote-link' CSS class](https://wordpress.org/support/topic/custom-css-not-working-anymore-3/) for backward compatibility with custom CSS.

= 3.0.8 [November 2, 2025] =
* CRITICAL SECURITY FIX: CVE-2025-11733 — Fixed unauthenticated stored XSS vulnerability (CVSS 7.2)
* Security: Complete security overhaul with 5-layer protection
* Security: Proper authentication, CSRF protection, input sanitisation, and output escaping
* Fix: 32 output escaping issues resolved
* Fix: 18 translation strings corrected
* Fix: All code now complies with WordPress coding standards
* Performance: 20–30% faster page loads with optimised resource loading
* Compatibility: WordPress 6.8 and PHP 8.4
* Quality: Zero Plugin Check errors or warnings

= 3.0.7 [August 9, 2025] =
* Fix: PHP 8.4 compatibility issue
* Compatibility: WordPress 6.8 compatibility test passed

= 3.0.6 [February 2, 2025] =
* Fix: PHP 8.2 compatibility issue

== Upgrade Notice ==

= 3.2.1 =
Fixes a duplicate footnote appearing at the bottom of the page with Pretty Tooltips enabled, and footnote text rendering too large on some themes. Recommended for all users.
