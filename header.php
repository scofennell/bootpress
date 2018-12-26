<?php

/**
 * The header for our theme.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

?><!doctype html>
<html <?php language_attributes(); ?> <?php echo get_moose() -> template_tags -> get_og_prefix(); ?> class="no-js no-svg">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="profile" href="http://gmpg.org/xfn/11">

		<?php wp_head(); ?>
		
	</head>

	<body <?php body_class(); ?>>

        <header class=''>
         
        </header>

        <div class='wrapper'>

