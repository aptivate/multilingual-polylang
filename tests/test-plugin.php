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
}
