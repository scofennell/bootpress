<?php

/**
 * A abstract class for dealing with collections of assets.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

abstract class Collection {

	function __construct( $args = array() ) {

		$this -> args = $args;

		$this -> set_object_id();

	}

	function get_slug() {

		if( ! isset( $this -> slug ) ) {
			return FALSE;
		}

		return $this -> slug;
	
	}

	function set_object_id() {

		if( ! isset( $this -> args['object_id'] ) ) { return FALSE; }

		$this -> object_id = $this -> args['object_id'];

	}

	function get() {

		$args = $this -> args;

		$args['post_type'] = $this -> get_slug();

		$query = new \WP_Query( $args );

		return $query;

	}

	function get_archive_url() {

		$out = get_post_type_archive_link( $this -> get_slug() );

		return $out;

	}

	function get_count() {

		$query = $this -> get();

		$out = $query -> found_posts;

		return $out;

	}

	function get_as_kv() {

		$query = $this -> get();

		$out = array();

		while( $query -> have_posts() ) {
			
			$query -> the_post();

			$id    = absint( get_the_ID() );
			$title = esc_html( get_the_title() );

			$out[ $id ] = $title;

		}

		wp_reset_postdata();

		return $out;

	}

	function get_as_edit_kv() {

		$class = get_acemod() -> formatting -> get_class( __CLASS__, __FUNCTION__ );

		$query = $this -> get();

		$out = array();

		while( $query -> have_posts() ) {
			
			$query -> the_post();

			$id        = absint( get_the_ID() );
			$title     = esc_html( get_the_title() );
			$edit_url  = get_edit_post_link( $id );
			$link_icon = '<span class="dashicons dashicons-edit"></span>';
			$link      = "<a class='$class-edit_link' href='$edit_url'>$link_icon</a>";

			$out[ $id ] = "$title&nbsp;$link";

		}

		wp_reset_postdata();

		return $out;

	}	

	function get_as_nav() {

		$class = get_acemod() -> formatting -> get_class( __CLASS__, __FUNCTION__ );

		$query = $this -> get();

		$out = '';

		while( $query -> have_posts() ) {
			
			$query -> the_post();

			$title = esc_html( get_the_title() );
			$href  = esc_url( get_permalink( get_the_ID() ) );

			$out .= "<a href='$href' class='$class-link'>$title</a>";

		}

		wp_reset_postdata();

		if( empty( $out ) ) { return FALSE; }

		$out = "<div class='$class'>$out</div>";

		return $out;

	}

	function get_meta_keys() {

		$meta_fields = get_moose() -> post_meta_fields;
		$fields      = $meta_fields -> get_fields();

		$fields = $fields[  $this -> slug ];
		$sections = $fields['sections'];
		foreach( $sections as $section_id => $section ) {
			$section_label = $section['label'];
			$settings = $section['settings'];
			foreach( $settings as $setting_id => $setting ) {
				$setting_label = $setting['label'];
				$out[ MOOSE . '-' . $section_id . '-' . $setting_id ] = "$section_label: $setting_label";
			}
		}

		return $out;

	}

}