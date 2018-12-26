<?php

/**
 * Register our widget areas.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class WidgetAreas extends Collection {

	public function __construct() {

		// Add our widget areas.
		add_action( 'widgets_init', array( $this, 'register' ), 10 );

		add_filter( 'the_content', array( $this, 'the_content' ) );

		$this -> mid_post_p = 3;

	}

	function the_content( $content ) {

		if( ! is_singular( array( 'post', 'page' ) ) ) { return $content; }

		if( ! in_the_loop() ) { return $content; }

		if( is_feed() ) { return $content; }

		if( is_admin() ) { return $content; }

		ob_start();
		dynamic_sidebar( 'mid_post' );
		$mid_post = ob_get_clean();

		if( ! $this -> is_post_long_enough_for_mid_post( $content ) ) {

			$out = $content . $mid_post;

		} else {

			// Explode the post by paragraph.
			$arr = explode( '</p>', $content );

			// The p after which we will run ads.
			$mid_post_p = $this -> mid_post_p;

			// Will hold the paragraphs before the mid_post_p.
			$before_arr = array();

			// For each paragraph before the p...
			$i = 0;
			while( $i < $mid_post_p ) {

				$i++;

				// Add it to the list, remove it from the remainder.
				$before_arr[] = array_shift( $arr );

			}

			$before = implode( '</p>', $before_arr );
			$before = force_balance_tags( $before );

			// Sew up the remaining paragraphs.
			$after = implode( '</p>', $arr );
			$after = force_balance_tags( $after );

			$out = "$before$mid_post$after";

		}

		return $out;

	}

	/**
	 * Define all of our widget areas in an array.
	 * 
	 * @return array All of our widget areas.
	 */
	public function get_widget_areas() {

		$out = array(

			// The ID for this widget area.
			'header' => array(
				
				// The UI label for when the admin user is managing this widget area.
				'name' => esc_html__( 'Header 1', 'acemod' ),
				
				// The UI text to describe this widget area in wp-admin.
				'description' => esc_html__( 'Appears first in the header.', 'acemod' ),

				'classes' => '',

			),

			// The ID for this widget area.
			'off-canvas-sidebar' => array(
				
				// The UI label for when the admin user is managing this widget area.
				'name' => esc_html__( 'Off Canvas', 'acemod' ),
				
				// The UI text to describe this widget area in wp-admin.
				'description' => esc_html__( 'Appears off of the canvas.', 'acemod' ),

				'classes' => '',	

			),		

			'footer-1' => array(
				
				// The UI label for when the admin user is managing this widget area.
				'name' => esc_html__( 'Footer 1', 'acemod' ),
				
				// The UI text to describe this widget area in wp-admin.
				'description' => esc_html__( 'Appears in the footer.', 'acemod' ),

				'classes' => '',

				'before_widget' => '<aside class="widget">',
				'after_widget'  => '</aside>',
				'before_title'  => '<div class="widget-title"><h6>',
				'after_title'   => '</div></h6>',

			),	

			'footer-2' => array(
				
				// The UI label for when the admin user is managing this widget area.
				'name' => esc_html__( 'Footer 2', 'acemod' ),
				
				// The UI text to describe this widget area in wp-admin.
				'description' => esc_html__( 'Appears in the footer.', 'acemod' ),

				'classes' => '',

				'before_widget' => '<aside class="widget">',
				'after_widget'  => '</aside>',
				'before_title'  => '<div class="widget-title"><h6>',
				'after_title'   => '</div></h6>',

			),											

			'colophon' => array(
				
				// The UI label for when the admin user is managing this widget area.
				'name' => esc_html__( 'Colophon', 'acemod' ),
				
				// The UI text to describe this widget area in wp-admin.
				'description' => esc_html__( 'Appears in the colophon.', 'acemod' ),

				'classes' => '',

			),
			
		);

		return $out;

	}

	function get_as_kv() {

		$out = array();

		$get_widget_areas = $this -> get_widget_areas();
		foreach( $get_widget_areas as $k => $v ) {
			$out[ $k ] = $v['name'];
		}
				
		return $out;

	}

	/**
	 * Add our widget areas.
	 */
	function register() {

		// A class to add to every widget title when the widget is output on the front end.
		$title_class  = 'widget_title';
		
		// A class to add to every widget wrapper.
		$widget_class = 'widget';

		// Grab the sidebars we defined above.
		$sidebars = $this -> get_widget_areas();

		// For each widget area...
		foreach( $sidebars as $id => $atts ) {
			
			// Build a CSS class for the widget titles in this sidebar.
			$this_title_class = "$id-widget_title";
			
			$inner_classes = '';
			if( isset( $atts['inner_classes'] ) ) { $inner_classes = $atts['inner_classes']; }

			$before_widget = '';
			if( isset( $atts['before_widget'] ) ) { $before_widget = $atts['before_widget']; }

			$after_widget = '';
			if( isset( $atts['after_widget'] ) ) { $after_widget = $atts['after_widget']; }

			$before_title = "<h3 class='$title_class $this_title_class'>";
			if( isset( $atts['before_title'] ) ) { $before_title = $atts['before_title']; }

			$after_title = '</h3>';
			if( isset( $atts['after_title'] ) ) { $after_title = $atts['after_title']; }			

			// Start some args for register_sidebar().
			$args = array(
				'name'          => $atts['name'],
				'id'            => $id,
				'description'   => $atts['description'],
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			);

			register_sidebar( $args );
		
		}
	
	}

	function is_post_long_enough_for_mid_post( $content ) {

		// Make sure there is at least one paragraph after the widget area.
		$min          = $this -> mid_post_p + 1;
		$substr_count = substr_count( $content, '</p>' );
 		if( $substr_count < $min ) { return FALSE; }

 		$word_arr = explode( ' ', wp_strip_all_tags( $content ) );

 		$word_count = count( $word_arr );

 		if( $word_count < 200 ) { return FALSE; }

 		return TRUE;

	}

}