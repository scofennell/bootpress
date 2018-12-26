<?php

/**
 * Register our conditional tags.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class Conditionals {

	public function __construct() {

	}

	function is_page_on_front() {

		$page_on_front = absint( get_option( 'page_on_front' ) );
		if( empty( $page_on_front ) ) { return FALSE; }

		global $post;
		if( ! isset( $post -> ID ) ) { return FALSE; }
		if( $post -> ID != $page_on_front ) { return FALSE; }

		return TRUE;

	}

	/**
	 * Determine if the theme has just received an update.
	 * 
	 * @return boolean Returns TRUE if the theme has just received an update, else FALSE.
	 */
	function is_update() {

		$update = new Update;

		$out = $update -> get_is_update();

		return $out;

	}

	/**
	 * Determines if the current view has pagination.
	 * 
	 * @return boolean Returns TRUE if the current view has pagination, else FALSE.
	 */
	function has_pagination() {

		if( ! is_home() && ! is_archive() && ! is_search() ) {
			return FALSE;
		}
	
		global $wp_query;

		$max_page = $wp_query -> max_num_pages;

		if( $max_page < 2 ) { return FALSE; }

		return TRUE;

	}

	function is_first_page() {

		$out = FALSE;

		$paged = absint( get_query_var( 'paged' ) );

		if( $paged < 2 ) { $out = TRUE; }

		return $out;

	}

	function is_staging() {

		$url = $this -> get_current_url();

		if( stristr( $url, 'staging' ) ) { return TRUE; }

		return FALSE;

	}

	function is_production() {

		if( ! $this -> is_staging() && ! $this -> is_localhost() ) {
			return TRUE;
		}
	
		return FALSE;

	}

	function is_localhost() {

		$url = $this -> get_current_url();

		if( stristr( $url, 'localhost' ) ) { return TRUE; }
		
		return FALSE;

	}

	function get_current_url() {

		$url = new Url;
		$out = $url -> get_current_url();

		return $out;

	}	

}