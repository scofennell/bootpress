<?php

/**
 * A widget for displaying logos.
 * 
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class WidgetAccountLinks extends \WP_Widget {

	use Widget;
	use Form;	

	function __construct() {
		parent::__construct(
			'AccountLinks',
			esc_html__( 'Account Links', 'acemod' ),
			array(
				'description' => esc_html__( 'Displays account links.', 'acemod' ),
			)
		);

		$this -> register_shortcode();

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$class = get_acemod() -> formatting -> get_class( __CLASS__, __FUNCTION__ );

		$content = $this -> get_content();

		if( empty( $content ) ) { return FALSE; }
		
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		$before_title = $args['before_title'];
		$after_title  = $args['after_title'];				

		$title = '';
		if( isset( $instance['title'] ) ) {
			wp_kses_post( $title = $instance['title'] );
		}	

		if( ! empty( $title ) ) {
			$title = $before_title . $title . $after_title;
		}
	
		$out = "
			$before_widget
				$title
				$content
			$after_widget
		";

		echo $out;

	}

	function get_form_settings( $instance ) {

		$out = array(

			'title' => array(
				'type'  => 'text',
				'name'  => $this -> get_field_name( 'title' ),
				'id'    => $this -> get_field_id( 'title' ),			
				'label' => esc_html__( 'Title', 'acemod' ),
			),

			/*'object_type' => array(
				'type'    => 'select',
				'name'    => $this -> get_field_name( 'object_type' ),
				'id'      => $this -> get_field_id( 'object_type' ),			
				'label'   => esc_html__( 'Object Type', 'acemod' ),
				'choices' => array(
					'PostTypes', 'get_as_kv'								
				),
			),*/			

			/*'orderby' => array(
				'type'    => 'select',
				'name'    => $this -> get_field_name( 'orderby' ),
				'id'      => $this -> get_field_id( 'orderby' ),			
				'label'   => esc_html__( 'Order By', 'acemod' ),
				'choices' => array(
					'title'      => esc_html__( 'Title' ),
					'meta_value' => esc_html__( 'Meta Value' ),
					'rand'       => esc_html__( 'Random' ),										
				),
			),*/					

		);

		return $out;

	}	

	function get_content() {

		$class = get_moose() -> formatting -> get_class( __CLASS__, __FUNCTION__ );

		$out = '';

		$links = array();

		foreach( $links as $url => $label ) {
			$out .= "<div class='mb-2'><a href='$url'>$label</a></div>";
		}

		return $out;

	}

}