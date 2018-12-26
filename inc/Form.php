<?php

/**
 * A class for common tasks when building and handling forms.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

trait Form {

	function sanitize( $dirty = array() ) {

		// Will hold cleaned values.
		$clean = array();

		if( ! is_array( $dirty ) ) { return $clean; }

		// For each section of settings...
		foreach( $dirty as $section => $settings ) {

			// For each setting...
			foreach( $settings as $k => $v ) {

				// Let's call it good to just to sanitize text field.
				if( is_scalar( $v ) ) {
					$v = wp_kses_post( $v );
				} elseif( is_array( $v ) ) {
					$v = array_map( 'wp_kses_post', $v );
				}

				// Nice!  Pass the cleaned value into the array.
				$clean[ $section ][ $k ] = $v;

			}
	
		}

		return $clean;

	}

	function recursive_sanitize( $dirty, $cb ) {

		$clean = array();

		if( is_array( $dirty ) ) {

			foreach( $dirty as $k => $v ) {

				$clean[ $k ] = $this -> recursive_sanitize( $v, $cb );
			
			}

		} else {

			$clean = $cb( $dirty );

		}

		return $clean;

	}

	/**
	 * Convert an associative array into html attributes.
	 * 
	 * @param  array $array An associative array.
	 * @return string       HTML attributes.
	 */
	function get_attrs_from_array( $array ) {

		$out = '';

		foreach( $array as $k => $v ) {

			$is_cb = FALSE;
			if( is_array( $v ) ) {

				if( class_exists( "MOOSE\\" .  $v[0] ) ) {

					$class = "MOOSE\\" .  $v[0];

					$object = new $class;

					if( method_exists( $object, $v[1] ) ) {
						$method = $v[1];
						$is_cb  = TRUE;
					}

				}

			}

			if( $is_cb ) {
				$v = call_user_func( array( $object, $method ) );
			}

			$k = sanitize_key( $k );
			$v = esc_attr( $v );

			$out .= " $k='$v' ";

		}

		return $out;

	}

}