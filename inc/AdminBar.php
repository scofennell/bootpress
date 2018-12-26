<?php

/**
 * A class for dealing with the admin bar.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class AdminBar {

	function __construct() {

		add_filter( 'show_admin_bar', array( $this, 'show_admin_bar' ) );

	}

	/**
	 * The theme already has a pos fix nav menu so I don't want to conflict with that.
	 */
	function show_admin_bar() {

		if( get_moose() -> conditionals -> is_localhost() ) {
			if( current_user_can( 'update_core' ) ) {
				return TRUE;
			}
		}

		return FALSE;

	}

}