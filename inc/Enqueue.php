<?php

/**
 * A class for enqueing stuff.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class Enqueue {

	function __construct() {

		// Add our JS.
		add_action( 'wp_enqueue_scripts',    array( $this, 'script' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'script' ) );

		// Add our CSS.
		add_action( 'wp_enqueue_scripts',    array( $this, 'style' ), 900 );
		add_action( 'admin_enqueue_scripts', array( $this, 'style' ), 900 );			

		add_action( 'wp_enqueue_scripts',    array( $this, 'localize' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'localize' ) );
        
	}

	/**
	 * Register and enqueue our JS.
	 * 
	 * @param string $hook The current page slug.
	 */
	function script( $hook = '' ) {

		// wp_register_script( 'moment', MOOSE_URL . 'lib/moment.min.js', array( 'jquery' ), MOOSE_VERSION, FALSE );
		// wp_enqueue_script( 'moment' );

		// wp_register_script( 'cookie', MOOSE_URL . 'lib/js.cookie.min.js', array( 'jquery' ), MOOSE_VERSION, FALSE );
		// wp_enqueue_script( 'cookie' );

		// wp_register_script( 'fitvids', MOOSE_URL . 'lib/jquery.fitvids.js', array( 'jquery' ), MOOSE_VERSION, FALSE );
		// wp_enqueue_script( 'fitvids' );

		if( ! is_admin() ) {

			wp_enqueue_script( 'jquery' );

			wp_register_script(
				'bs-bundle',
				'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js',
				array(),
				MOOSE_VERSION,
				TRUE
			);
			wp_enqueue_script( 'bs-bundle' );

		}

		wp_register_script(
			MOOSE . '-script',
			MOOSE_URL . 'js/script.js',
			array( 'jquery' ),
			MOOSE_VERSION,
			TRUE
		);
		wp_enqueue_script( MOOSE . '-script' );

	}

	/**
	 * Register and enqueue our CSS.
	 * 
	 * @param string $hook The current page slug.
	 */
	function style( $hook = '' ) {

		if( ! is_admin() ) {

			wp_register_style(
				'fa',
				'https://use.fontawesome.com/releases/v5.0.6/css/all.css',
				FALSE,
				MOOSE_VERSION
			);
			wp_enqueue_style( 'fa' );	

			wp_register_style(
				'bs',
				'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css',
				FALSE,
				MOOSE_VERSION
			);
			wp_enqueue_style( 'bs' );

		    wp_register_style(
				'jquery-ui-theme',
				'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'
			);

			// Register our CSS for when we need it.
			wp_register_style( MOOSE . '-style', MOOSE_URL . 'css/style.css', array(), MOOSE_VERSION );
			wp_enqueue_style( MOOSE . '-style' );

		}

		if( is_admin() ) {

			// Register our CSS for when we need it.
			wp_register_style( MOOSE . '-admin', MOOSE_URL . 'css/admin.css', array(), MOOSE_VERSION );
			wp_enqueue_style( MOOSE . '-admin' );


		}

	}

	function localize() {

		if( is_admin() ) { return FALSE; }

		$vars = $this -> get_localization();

		wp_localize_script( MOOSE . '-script', MOOSE . '_globals', $vars );

	}

	function get_localization() {

		// This allows WP to use get_current_user_id() in WP API callbacks.
		$nonce = wp_create_nonce( 'wp_rest' );
		
		$out = array(
			'nonce' => $nonce,
		);

		return $out;

	}

}