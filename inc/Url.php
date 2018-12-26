<?php

/**
 * A class for dealing with urls.
 *
 * @package WordPress
 * @subpackage Moost
 * @since Moost 0.1
 */

namespace MOOSE;

class Url {

	public function __construct() {

		$this -> conditionals = get_moose() -> conditionals;
	
	}

	function get_current_url( $action_args = TRUE ) {

		if( is_ssl() ) {
			$proto = 'https://';
		} else {
			$proto = 'http://';
		}

		$out = esc_url_raw( $proto . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] );

		if( ! $action_args ) {
			$out = $this -> remove_action_args( $out );
		}

		return $out;

	}
}