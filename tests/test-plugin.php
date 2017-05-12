<?php
/**
 * Class PluginTest
 *
 * @package Polylang_Multilingual/
 */

/**
 * Plugin test case.
 */
class PluginTest extends WP_UnitTestCase {

	function test_check_canonical_url_returns_false() {
		$url = 'http://example.com/foo/fr';

		$url = apply_filters( 'pll_check_canonical_url', $url, 'fr' );

		$this->assertThat( $url, $this->identicalTo( false ) );
	}

	function test_sticky_posts_filter_removed() {
		$original_posts = array( 1, 2, 3 );

		$posts = apply_filters( 'option_sticky_posts', $original_posts );

		$this->assertThat( $posts, $this->equalTo( $original_posts ) );
	}

	function test_get_query_returns_posts_in_all_languages() {
		register_taxonomy( 'post_translations', array( 'post' ), array() );

		$query = PolylangMultilingual::get_query();

		$this->assertThat( $query->query['lang'], $this->equalTo( '' ) );
	}
}
