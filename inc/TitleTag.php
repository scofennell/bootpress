<?php

/**
 * Register our title tag.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class TitleTag {

	function __construct() {

		add_theme_support( 'title-tag' );

	}

}