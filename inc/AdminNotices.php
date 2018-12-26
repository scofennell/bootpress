<?php

/**
 * A class for drawing admin notices.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class AdminNotices {

	function __construct() {

		// Tell the user about errors uploading audio files.
		//add_action( 'admin_notices', array( $this, 'pngs' ) );

	}

	function pngs() {

		$out = '';

		$current_screen = get_current_screen();
		$base = $current_screen -> base;
		if( $base != 'post' ) { return FALSE; }

		if( ! isset( $_GET['post'] ) ) { return FALSE; }

		$post_id = absint( $_GET['post'] );
		if( empty( $post_id ) ) { return FALSE; }

		if( ! get_acemod() -> conditionals -> has_pngs( $post_id ) ) { return FALSE; }
	
		$out = "<div class='notice warning error'><p>Your post contains .png images.  Please use .jpg images instead.</p></div>";

		echo $out;

	}

}