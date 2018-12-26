<?php

/**
 * Register our content filters.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class FilterShortcodes {

	public function __construct() {

		$this -> conditionals = get_moose() -> conditionals;

		// add_filter( 'shortcode_atts_gallery', array( $this, 'gallery' ), 999, 3 );
	
		add_filter( 'wp_nav_menu_items', 'do_shortcode' );

	}

	function gallery( $out ) {

		$out['link'] = 'file';

		return $out;

	}

}