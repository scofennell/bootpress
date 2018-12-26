<?php

/**
 * Register our excerpt filters.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class FilterExcerpt {

	public function __construct() {

		$this -> conditionals = get_moose() -> conditionals;

		// add_filter( 'excerpt_length', array( $this, 'length' ) );
	
	}

	function length( $out ) {

		$out = 100;

		return $out;

	}

}