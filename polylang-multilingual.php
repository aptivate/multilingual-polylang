<?php
/*
  Plugin Name: Polylang Multilingual
  Description: Display posts in all languages
  Version: 1.0.0
  Author: Aptivate
*/

class PolylangMultilingual {
	private static $current_language;
	private static $other_languages;

	public static function init() {
		if ( self::is_polylang_installed() && ! is_admin() ) {
			self::$current_language = pll_current_language();
			self::$other_languages = array_diff(
				pll_languages_list(),
				array( self::$current_language )
			);
		}
	}

	private static function is_polylang_installed() {
		return function_exists( 'pll_languages_list' );
	}

	public static function get_query() {
		/* https://wordpress.org/support/topic/show-posts-from-other-languages/?replies=18
		 * by way of
		 * http://wordpress.syllogic.in/2014/08/going-multi-lingual-with-polylang/
		 */
		$duplicated_post_ids = self::get_duplicated_posts();

		$all_languages = '';

		$args = array(
			'lang' => $all_languages,
			'post__not_in' => $duplicated_post_ids,
		);

		return new WP_Query( $args );
	}

	private static function get_duplicated_posts() {
		$duplicate_ids = array();

		$terms = get_terms( 'post_translations' );

		foreach ( $terms as $translation ) {
			$trans_post = unserialize( $translation->description );
			if ( self::is_in_current_language( $trans_post ) ) {
				foreach ( self::$other_languages as $other_language ) {
					$duplicate_ids[] = $trans_post[ $other_language ];
				}
			}
		}

		return $duplicate_ids;
	}

	private static function is_in_current_language( $trans_post ) {
		return $trans_post[ self::$current_language ] != 0;
	}
}

add_action( 'plugins_loaded', array( 'PolylangMultilingual', 'init' ) );
