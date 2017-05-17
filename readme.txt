=== Multilingual Polylang ===
Contributors: Aptivate
Tags: polylang, multilingual, bilingual, translate, translation, language, multilanguage, international, localization
Requires at least: 4.0
Tested up to: 4.7.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This plugin, which requires [polylang](https://wordpress.org/plugins/polylang/)
provides the means to include posts that have no translation in the current
language on pages of posts.

= Usage =

Multilingual Polylang provides two public functions:

`MultilingualPolylang::get_query()` returns a `WP_Query` object with posts from
all languages but where there is a translated post, only the post in the current
language will be included

`MultilingualPolylang::get_permalink()` can be used as a drop-in replacement to
`get_permalink()`. This will replace the language in a post URL so that a post
can be viewed in a language different to that of the rest of the interface.

[Follow this project on Github](https://github.com/aptivate/multilingual-polylang)


== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory.
2. Activate it through the **Plugins** menu in WordPress.

== Changelog ==

= 1.0.0 =
* First version

== Upgrade Notice ==

= 1.0.0 =
* First version


== Development ==

This plugin uses [wp-cli](http://wp-cli.org/) and [PHPUnit](https://phpunit.de/) for testing.

* Grab the latest source from github:

`
$ git clone git@github.com:aptivate/multilingual-polylang.git
`

* Install [wp-cli](http://wp-cli.org/#install)
* Install [PHPUnit](https://phpunit.de/)

* Install the test WordPress environment:

`
cd multilingual-polylang
bash bin/install-wp-tests.sh test_db_name db_user 'db_password' db_host version
`

where:

    `test_db_name` is the name for your **temporary** test WordPress database
    `db_user` is the database user name
    `db_password` is the password
    `db_host` is the database host (eg `localhost`)
    `version` is the version of WordPress (eg `4.7.5` or `latest`)

* Run the tests
`phpunit`
