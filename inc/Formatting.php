<?php

/**
 * A singleton for formatting strings and dates and such.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class Formatting {

	/**
	 * Parse a datetime into an "ago" date.
	 * 
	 * @param  string $date_string Any formatted date.
	 * @return string              The $date_string, as an "ago" date.
	 */
	public static function agoify( $from_date, $to_date = FALSE ) {

		if( ! $to_date ) {
			$to_date = current_time( 'timestamp', get_option( 'gmt_offset' ) );
		}

		$human_time = human_time_diff( $from_date, $to_date );

		$out = sprintf( esc_html__( '%s ago', 'lxb-af' ), $human_time );

		return $out;

	}

	/**
	 * Clean and format text from a wp_editor() instance.
	 * 
	 * @param  string $editor_content The text that the user entered in a wp_editor().
	 * @return string                 The $editor_content text, ready for output.
	 */
	public function editorify( $editor_content ) {

		$out = $editor_content;

		$out = stripslashes( $out );
		$out = wpautop( $out );

		return $out;

	}


	function get_class( $class, $function = '' ) {

		$class = str_replace( __NAMESPACE__, '', $class );

		$out = '';

		if( ! empty( $class ) && ! empty( $function ) ) {
			$out = sanitize_html_class( $class . '-' . $function );
		} elseif( ! empty( $class ) ) {
			$out = sanitize_html_class( $class );
		}  elseif( ! empty( $function ) ) {
			$out = sanitize_html_class( $function );
		}

		return $out;

	}

	function get_datetime( $timestamp ) {

		$date_format = get_option( 'date_format' );

		$time_format = get_option( 'time_format' );

		$out = date( "$date_format, $time_format", $timestamp );

	}

}