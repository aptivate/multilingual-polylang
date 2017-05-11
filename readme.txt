=== Polylang Multilingual ===
Contributors: Aptivate
Tags: polylang, multilingual, bilingual, translate, translation, language, multilanguage, international, localization
Requires at least: 4.0
Tested up to: 4.7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This plugin, which requires [polylang](https://wordpress.org/plugins/polylang/)
provides the means to include posts that have no translation in the current
language on pages of posts.

= Usage =

Polylang Multilingual provides two public functions:

`PolylangMultilingual::get_query()` returns a `WP_Query` object with posts from
all languages but where there is a translated post, only the post in the current
language will be included

`PolylangMultilingual::get_permalink()` can be used as a drop-in replacement to
`get_permalink()`. This will replace the language in a post URL so that a post
can be viewed in a language different to that of the rest of the interface.

[Follow this project on Github](https://github.com/aptivate/polylang-multilingual)


== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory.
2. Activate it through the **Plugins** menu in WordPress.

== Changelog ==

= 1.0.0 =
* First version

== Upgrade Notice ==

= 1.0.0 =
* First version
