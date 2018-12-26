<?php

/**
 * Register our post meta fields.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class PostTypeSupport extends Registry {

	public function __construct() {

		parent::__construct();

		$this -> register();

	}

	function get_slug() {
		return 'post_type_support';
	}

	/**
	 * Store our meta fields definitions.
	 */
	function set_fields() {

		$out = array(

			// A post type.
			'page' => array(

				'add' => array(
					'excerpt',
				),

			),

		);

		$this -> fields = $out;

	}

	function register() {
		$fields = $this -> fields;

		foreach( $fields as $post_type => $supports ) {

			$add = $supports['add'];
			foreach( $add as $add_to_post_type ) {
				add_post_type_support( $post_type, $add_to_post_type );
			}

		}

	}

}