<?php

/**
 * Register our shortcodes.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class Shortcodes extends Registry {

	public function __construct() {

		$this -> register();

		$this -> conditionals = get_moose() -> conditionals;		

	}

	public function register() {

		$arr = $this -> get();

		foreach( $arr as $tag => $callback ) {
			add_shortcode( $tag, array( $this, $callback ) );
		}

	}

	public function get() {

		$out = array(
			'hello' => 'world',
		);

		return $out;

	}

	function world() {}

}