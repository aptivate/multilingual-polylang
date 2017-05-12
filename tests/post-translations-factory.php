<?php
class PostTranslationsFactory extends WP_UnitTest_Factory_For_Term {
	function __construct( $factory = null, $taxonomy = 'post_translations' ) {

		parent::__construct( $factory, $taxonomy );

		$this->taxonomy = $taxonomy;

		$this->default_generation_definitions = array(
			'name' => new WP_UnitTest_Generator_Sequence( 'Term %s' ),
			'taxonomy' => $this->taxonomy,
			'description' => new WP_UnitTest_Generator_Sequence( 'Term description %s' ),
		);
	}
}