<?php

/**
 * Register our template tags.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class TemplateTags {

	function __construct() {

	}

	function get_og_prefix() {

		return 'prefix="og: http://ogp.me/ns#"';

	}

	function get_queried_object_name() {

		global $wp_query;

		if( is_post_type_archive() ) {
		
			$query = $wp_query -> query;

			$post_type_slug = $query['post_type'];

			$post_type_obj  = get_post_type_object( $post_type_slug );

			$out = $post_type_obj -> labels -> name;
		
		} elseif( is_tax() || is_tag() || is_category() ) {

			$out = $this -> get_term_title();

		} elseif( is_author() ) {

			$out = $this -> get_author_title();

		}

		return $out;

	}

	function get_gravatar( $size = 56, $link = TRUE ) {

		if( ! $this -> check_for_gravatar() ) { return FALSE; }
		
		$out = '';

		$img = get_avatar( get_the_author_meta( 'user_email' ), $size );

		$img = "<img src='$url' class='avatar photo avatar-$size'>";

		if( $link ) {
			if( ! empty( $img ) ) {
				$href = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
				$out = "<a class='' href='$href'>$img</a>";
			}
		} else {
			$out = "<span class=''>$img</span>";
		}

		return $out;

	}

	function check_for_gravatar() {
		
		$hash = md5( get_the_author_meta( 'user_email' ) );

		$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';

		$headers = @get_headers( $uri );
		if ( ! preg_match("|200|", $headers[ 0 ] ) ) {
			$out = FALSE;
		} else {
			$out = TRUE;
		}
		return $out;

	}	

	function get_comments() {

		$class = get_moose() -> formatting -> get_class( __CLASS__, __FUNCTION__ );
		
		$comments_list = '';

		if( have_comments() ) {
			
			$comments_number = get_comments_number();
			
			if ( '1' === $comments_number ) {
			
				$comment_count_title = esc_html__( 'One Comment', 'moose' );
			
			} else {
			
				$comment_count_title = sprintf(
					esc_html__(
						'%1$s Comments',
						'moose'
					),
					$comments_number
				);

			}
			
			ob_start();
			wp_list_comments(
				array(
					'avatar_size' => 100,
					'style'       => 'div',
					'short_ping'  => true,
				)
			);
			$list_comments = ob_get_clean();

			$comments_list = "
				<div class='$class-list'>
					$list_comments
				</div>
			";

			$pagination = get_the_comments_pagination( array(
				'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Newer', 'moose' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Older', 'moose' ) . '</span>',
			) );

		}

		if( ! comments_open() ) {
			$form = esc_html( 'Comments closed.', 'moose' );
		} else {

			ob_start();
			comment_form();
			$form = ob_get_clean();

		}

		$out = "
			<div id='comments' class='$class'>
				$form
				$comments_list
			</div>
		";

		return $out;

	}

}