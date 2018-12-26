<?php

/**
 * Register our image sizes.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class ImageSizes {

	public function __construct() {

		// Add our post types.
		add_action( 'after_setup_theme', array( $this, 'register' ), 900 );		

	}

	function get_sizes() {

		$out = array(

			/**
			 * 1:1
			 */

				'100_100_h' => array(
					'width'   => 100,
					'height'  => 100,
					'crop'    => TRUE,
				),

				'200_200_h' => array(
					'width'   => 200,
					'height'  => 200,
					'crop'    => TRUE,
				),

				'400_400_h' => array(
					'width'   => 400,
					'height'  => 400,
					'crop'    => TRUE,
				),				

				'800_800_h' => array(
					'width'   => 800,
					'height'  => 800,
					'crop'    => TRUE,
				),				

			/**
			 * 3:2
			 */
				'100_66_h' => array(
					'width'   => 100,
					'height'  => 66,
					'crop'    => TRUE,
				),

				'200_133_h' => array(
					'width'   => 200,
					'height'  => 133,
					'crop'    => TRUE,
				),	

				'400_266_h' => array(
					'width'   => 400,
					'height'  => 266,
					'crop'    => TRUE,
				),							

				'800_532_h' => array(
					'width'   => 800,
					'height'  => 532,
					'crop'    => TRUE,
				),

				'1600_1064_h' => array(
					'width'   => 1600,
					'height'  => 1064,
					'crop'    => TRUE,
				),

			/**
			 * 2:1
			 */

				'100_50_h' => array(
					'width'   => 100,
					'height'  => 50,
					'crop'    => TRUE,
				),

				'200_100_h' => array(
					'width'   => 200,
					'height'  => 100,
					'crop'    => TRUE,
				),

				'400_200_h' => array(
					'width'   => 400,
					'height'  => 200,
					'crop'    => TRUE,
				),

				'800_400_h' => array(
					'width'   => 800,
					'height'  => 400,
					'crop'    => TRUE,
				),				

				'1600_800_h' => array(
					'width'   => 1600,
					'height'  => 800,
					'crop'    => TRUE,
				),

		);

		return $out;

	}

	function register() {

		add_theme_support( 'post-thumbnails' );

		$sizes = $this -> get_sizes();

		foreach( $sizes as $size_k => $size ) {

			add_image_size( $size_k, $size['width'], $size['height'], $size['crop'] );

		}

	}

}