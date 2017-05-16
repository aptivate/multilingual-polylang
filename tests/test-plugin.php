<?php
/**
 * Class PluginTest
 *
 * @package Multilingual_Polylang/
 */

require_once dirname( __FILE__ ) . '/post-translations-factory.php';


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

		$query = MultilingualPolylang::get_query();

		$this->assertThat( $query->query['lang'], $this->equalTo( '' ) );
	}

	function test_get_query_preserves_defaults() {
		register_taxonomy( 'post_translations', array( 'post' ), array() );

		$query = MultilingualPolylang::get_query( array(
			'post_type' => 'resource',
		) );

		$this->assertThat( $query->query['post_type'], $this->equalTo( 'resource' ) );
	}

	function test_get_query_filters_duplicates() {
		register_taxonomy( 'post_translations', array( 'post' ), array() );

		$both1 = $this->get_new_translated_posts( array( 'en', 'fr' ) );
		$both2 = $this->get_new_translated_posts( array( 'en', 'fr' ) );
		$both3 = $this->get_new_translated_posts( array( 'en', 'fr' ) );

		// The current languages is French so we shouldn't see English
		$duplicates = array( $both1['en'], $both2['en'], $both3['en'] );
		asort( $duplicates );

		$query = MultilingualPolylang::get_query();
		$excluded_ids = $query->query['post__not_in'];
		asort( $excluded_ids );

		$this->assertThat( $excluded_ids, $this->equalTo( $duplicates ) );
	}

	function test_get_permalink_replaces_language() {
		register_taxonomy( 'post_translations', array( 'post' ), array() );

		$posts = $this->get_new_translated_posts( array( 'en', 'fr' ) );

		$english_post = get_post( $posts['en'] );
		$french_post = get_post( $posts['fr'] );

		global $mock_post_link;
		$mock_post_link = 'http://africaledspartnership.org/en/2017/05/02/test-post';
		$french_link = 'http://africaledspartnership.org/fr/2017/05/02/test-post';

		global $mock_post_language;
		$mock_post_language = 'en';

		add_filter( 'post_link', array( $this, 'post_link' ), 20, 2 );

		$permalink = MultilingualPolylang::get_permalink( $english_post );
		$this->assertThat( $permalink, $this->equalTo( $french_link ) );
	}

	public function post_link()
	{
		global $mock_post_link;

		return $mock_post_link;
	}

	private function get_new_translated_posts( $languages )
	{
		$posts = array();

		foreach ( $languages as $language ) {
			$post = $this->factory->post->create();
			$translations[ $language ] = $post;
			$posts[ $language ] = $post;
		}

		$factory = new PostTranslationsFactory();
		$term = $factory->create( array( 'description' => serialize( $translations ) ) );

		wp_set_post_terms( $posts[ $languages[0] ], array( $term ), 'post_translations' );

		return $posts;
	}
}
