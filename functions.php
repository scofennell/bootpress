<?php

/**
 * A file for loading theme files.
 * 
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */
	
// Peace out if you're trying to access this up front.
if( ! defined( 'ABSPATH' ) ) { exit; }

// Watch out for plugin naming collisions.
if( defined( 'MOOSE' ) ) { exit; }
if( isset( $moose ) ) { exit; }

// A slug for our plugin.
define( 'MOOSE', 'MOOSE' );

// A constant to define the paths to our plugin folders.
define( 'MOOSE_FOLDER', trailingslashit( basename( __DIR__ ) ) );
define( 'MOOSE_FILE', __FILE__ );
define( 'MOOSE_PATH', trailingslashit( get_template_directory( MOOSE_FILE ) ) );

// A constant to define the urls to our plugin folders.
define( 'MOOSE_URL', trailingslashit( get_template_directory_uri( MOOSE_FILE ) ) );

// Establish a value for theme version to bust file caches.
define( 'MOOSE_VERSION', wp_get_theme( MOOSE_FOLDER ) -> get( 'Version' ) );

if ( ! isset( $content_width ) ) {
	$content_width = 750;
}

// Our master plugin object, which will own instances of various classes in our plugin.
$moose  = new stdClass();
$moose -> bootstrap = MOOSE . '\Bootstrap';

// Register an autoloader.
require_once( MOOSE_PATH . 'autoload.php' );

// Execute the plugin code!
new $moose -> bootstrap;

function get_moose() {

	global $moose;

	return $moose;

}
