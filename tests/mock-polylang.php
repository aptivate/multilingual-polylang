<?php
/**
 * Mock some Polylang functions
 */
function pll_languages_list() {
	return array( 'en', 'fr' );
}

function pll_current_language() {
	return 'fr';
}

function pll_get_post_language() {
	global $mock_post_language;

	return $mock_post_language;
}

class PLL_Frontend_Filters {
	function option_sticky_posts( $posts ) {
		return 'default sticky posts';
	}
}

global $polylang;
$polylang = new StdClass();
$polylang->filters = new PLL_Frontend_Filters();

add_filter( 'option_sticky_posts', array( $polylang->filters, 'option_sticky_posts' ) );
