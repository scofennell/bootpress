<?php

/**
 * Register our body classes.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class BodyClass {

	public function __construct() {

		$this -> conditionals = get_moose() -> conditionals;

		add_filter( 'body_class', array( $this, 'has_post_thumbnail' ) );
		add_filter( 'body_class', array( $this, 'is_singular' ) );
		add_filter( 'body_class', array( $this, 'admin_bar' ) );
		add_filter( 'body_class', array( $this, 'widget_areas' ) );		
		
	}

	/**
	 * Add a body class for each active widget area.
	 */
	function widget_areas( $classes ) {
		$widget_areas = get_moose() -> widget_areas;
		$get = $widget_areas -> get_widget_areas();
		foreach( $get as $k => $v ) {

			if( is_active_sidebar( $k ) ) {
				$classes[] = 'has_' . $k;
			}

		}

		return $classes;
		
	}

	/**
	 * Determine if the current view has a post thumbnail.
	 * 
	 * @param  array  $classes The current body classes.
	 * @return array           The body classes, filtered.
	 */
	function has_post_thumbnail( $classes ) {

		if( ! is_singular() ) { return $classes; }

		if( has_post_thumbnail() ) {
		
			$classes[] = 'has_post_thumbnail';
		
		} else {
		
			$classes[] = 'has_no_post_thumbnail';	
		
		}

		return $classes;

	}

	function is_singular( $classes ) {

		if( ! is_singular() ) { return $classes; }

		$classes[] = 'is_singular';

		return $classes;

	}	

	function admin_bar( $classes ) {

		if( is_admin_bar_showing() ) { return $classes; }

		$classes[] = 'no_admin_bar';

		return $classes;

	}		

}