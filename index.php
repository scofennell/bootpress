<?php

/**
 * The single post template file.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

?>

<?php get_header(); ?>

	<?php if( have_posts() ) { ?>
		
		<?php while( have_posts() ) { ?>

			<?php the_post(); ?>

			<h1>
				<?php the_title(); ?>
			</h1>
			<div>
				<?php the_content(); ?>
			</div>

		<?php } ?>

	<?php } else { ?>

	<?php } ?>

<?php get_footer(); ?>