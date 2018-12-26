<?php

/**
 * Register our post classes.
 *
 * @package WordPress
 * @subpackage Moost
 * @since Moost 0.1
 */

namespace MOOSE;

class PostClass {

	public function __construct() {

		$this -> conditionals = get_moose() -> conditionals;

		add_filter( 'post_class', array( $this, 'not_singular' ) );
	
	}

	function not_singular( $classes ) {

		if( ! in_the_loop() ) { return $classes; }		
		if( is_singular() ) { return $classes; }

		$classes[] = 'not_singular';

		return $classes; 

	}

}