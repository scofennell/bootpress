<?php

/**
 * A abstract class for dealing with collections of assets.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

abstract class Registry {

	public function __construct() {

		// Define our settings.
		$this -> set_fields();

	}

	/**
	 * Get the array that defines our plugin post meta fields.
	 * 
	 * @return array Our plugin post meta fields.
	 */
	function get_fields() {

		return $this -> fields;

	}

	/**
	 * Get the value for a meta field.
	 * 
	 * @param  integer $post_id    The post ID.
	 * @param  string  $section_id The section ID.
	 * @param  string  $setting_id The setting ID.
	 * @return mixed               The post meta value from the DB.
	 */ 
	function get_value( $object_id = FALSE, $section_id, $setting_id ) {

		if( ! $object_id ) {
			$object_id = get_the_ID();
		}

		$out = get_post_meta( $object_id, $this -> get_prefix() . '-' . $section_id . '-' . $setting_id, TRUE );

		return $out;

	}

	function get_setting( $section_id, $setting_id ) {

		$fields  = $this -> get_fields();
		$setting = $fields['sections'][ $section_id ]['settings'][$setting_id];
		return $setting;

	}

	function get_default( $section_id, $setting_id ) {

		$out = NULL;

		$setting = $this -> get_setting( $section_id, $setting_id );
		if( isset( $setting['default'] ) ) {
			$out = $setting['default'];
		}

		return $out;

	}

}