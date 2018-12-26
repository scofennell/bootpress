<?php

/**
 * Template tags for getting fonts.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class Fonts {

	public function __construct() {

		add_action( 'wp_head', array( $this, 'the_tag' ) );

	}


	function get_fonts() {
		$out = array(
			'Noto Sans' => array(
				'versions' => array(
					'400',
					'400i',
					'700',
					'700i',
				),
			),
			'Noto Serif' => array(
				'versions' => array(
					'400',
					'400i',
					'700',
					'700i',
				),
			),
		);

		return $out;

	}

	function get_tag() {

		$family = '';

		$fonts = $this -> get_fonts();

		$fonts_str = '';

		foreach( $fonts as $family_name => $family ) {

			$versions = implode( ',', $family['versions'] );

			$fonts_str .= urlencode( $family_name ) . ':' . $versions . '|';

		}

		$fonts_str = rtrim( $fonts_str, '|' );

		$out = "<link href='https://fonts.googleapis.com/css?family=$fonts_str' rel='stylesheet'>";

		return $out;

	}

	function the_tag() {

		if( is_admin() ) { return FALSE; }

		echo $this -> get_tag();

	}

}