<?php
/*
  Plugin Name: Multilingual Polylang
  Description: Display posts in all languages
  Version: 1.0.1
  Author: Aptivate
*/

class MultilingualPolylang {
	private static $current_language;
	private static $other_languages;

	public static function init() {
		if ( self::is_polylang_installed() && ! is_admin() ) {
			self::$current_language = pll_current_language();
			self::$other_languages = array_diff(
				pll_languages_list(),
				array( self::$current_language )
			);

			/* Don't redirect URLs in 'wrong' language so that we can
			 * have article in one language and interface in another
			 */
			add_filter( 'pll_check_canonical_url', '__return_false' );

			global $polylang;

			remove_filter(
				'option_sticky_posts',
				array( $polylang->filters, 'option_sticky_posts' ) );
		}
	}

	private static function is_polylang_installed() {
		return function_exists( 'pll_languages_list' );
	}

	public static function get_query( $args = array() ) {
		/* https://wordpress.org/support/topic/show-posts-from-other-languages/?replies=18
		 * by way of
		 * http://wordpress.syllogic.in/2014/08/going-multi-lingual-with-polylang/
		 */
		
		global $wp_query;
		
		$duplicated_post_ids = self::get_duplicated_posts();

		$all_languages = '';

		// Merge all original query vars with query to support CPT, Taxonomies, etc.
		$merge_args = array_merge($wp_query->query, $wp_query->query_vars);

		// Overwrite specific language args
		$merge_args['lang'] = $all_languages;
		$merge_args['post__not_in'] = $duplicated_post_ids;

		// Handle tax_query ([taxonomy] => language) arguments
		if(isset($merge_args['tax_query'])) {
			$needle = self::getIndex( 'taxonomy', 'language', $merge_args['tax_query'] );
			if ( $needle !== null ) {
				unset( $merge_args['tax_query'][ $needle ] );
			}
		}

		// Give the possibility to overwrite the $args
		$args = array_merge( $merge_args, $args );

		return new WP_Query( $args );
	}

	/**
	 * A replacement for the WordPress function get_permalink()
	 *
	 * This will replace the language in a post URL so that a post can be viewed
	 * in a language different to that of the rest of the interface.
	 */
	public static function get_permalink( $post ) {
		$link = get_permalink( $post );

		if ( self::is_polylang_installed() ) {
			$post_language = pll_get_post_language( $post->ID );

			if ( $post_language != self::$current_language ) {
				$search = "/$post_language/";
				$replace = '/' . self::$current_language . '/';

				$link = str_replace( $search, $replace, $link );
			}
		}

		return $link;
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
	
	private static function getIndex($needle, $name, $array) {
		foreach($array as $key => $value){
			if(is_array($value) && $value[$needle] == $name)
				return $key;
		}
		return null;
	}
}

add_action( 'plugins_loaded', array( 'MultilingualPolylang', 'init' ) );
