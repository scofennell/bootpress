<?php

/**
 * Template tags for getting icons.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class Icons {

	function get( $slug, $screen_reader_text = '', $spin = FALSE ) {

		$slug = esc_attr( $slug );

		$maybe_spin = '';
		if( $spin ) {
			$maybe_spin = 'fa-spin';
		}

		if( ! empty( $screen_reader_text ) ) {
			$screen_reader_text = "<span class='sr-only'>$screen_reader_text</span>";
		}

		$cat = 'fa';
		$brands = $this -> get_brands_as_kv();
		if( array_key_exists( $slug, $brands ) ) {
			$cat = 'fab';
		}

		$out = "<i class='$cat fa-$slug $maybe_spin'>$screen_reader_text</i>";

		return $out;

	}

	/**
	 * @todo This needs to be reworked so it comes from a full array that denotes if each item is a brand.
	 */
	function get_as_kv() {

		$out = array(
			'twitter'          => esc_html__( 'Twitter', 'acemod' ),
			'twitter-sign'     => esc_html__( 'Twitter Sign', 'acemod' ),
			'twitter-circle'   => esc_html__( 'Twitter Circle', 'acemod' ),
			'twitter-square'   => esc_html__( 'Twitter Square', 'acemod' ),
			'reddit'           => esc_html__( 'Reddit', 'acemod' ),
			'reddit-alien'     => esc_html__( 'Reddit Alien', 'acemod' ),
			'facebook'         => esc_html__( 'Facebook', 'acemod' ),
			'facebook-f'       => esc_html__( 'Facebook F', 'acemod' ),
			'facebook-sign'    => esc_html__( 'Facebook Sign', 'acemod' ),
			'facebook-circle'  => esc_html__( 'Facebook Circle', 'acemod' ),
			'facebook-square'  => esc_html__( 'Facebook Square', 'acemod' ),
			'pinterest'        => esc_html__( 'Pinterest', 'acemod' ),
			'pinterest-p'      => esc_html__( 'Pinterest P', 'acemod' ),
			'pinterest-sign'   => esc_html__( 'Pinterest Sign', 'acemod' ),
			'pinterest-circle' => esc_html__( 'Pinterest Circle', 'acemod' ),
			'pinterest-square' => esc_html__( 'Pinterest Square', 'acemod' ),
			'instagram'        => esc_html__( 'Instagram', 'acemod' ),
			'instagram-sign'   => esc_html__( 'Instagram Sign', 'acemod' ),
			'instagram-circle' => esc_html__( 'Instagram Circle', 'acemod' ),
			'instagram-square' => esc_html__( 'Instagram Square', 'acemod' ),
			'linkedin-in'      => esc_html__( 'LinkedIn In', 'acemod' ),
			'linkedin'         => esc_html__( 'LinkedIn', 'acemod' ),
			'youtube'          => esc_html__( 'YouTube', 'acemod' ),
			'youtube-sign'     => esc_html__( 'YouTube Sign', 'acemod' ),
			'youtube-circle'   => esc_html__( 'YouTube Circle', 'acemod' ),
			'youtube-square'   => esc_html__( 'YouTube Square', 'acemod' ),
		);

		return $out;

	}

	function get_brands_as_kv() {

		return $this -> get_as_kv();

	}

}