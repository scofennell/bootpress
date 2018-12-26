<?php

/**
 * A class with helper functions for drawing widgets.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

trait Widget {

	function register_shortcode() {

		add_shortcode( $this -> id_base, array( $this, 'shortcode' ) );
		add_shortcode( strtoupper( $this -> id_base ), array( $this, 'shortcode' ) );
		add_shortcode( ucfirst( $this -> id_base ), array( $this, 'shortcode' ) );
		
	}

	function shortcode( $instance = array() ) {

		$class = get_moose() -> formatting -> get_class( __CLASS__, __FUNCTION__ );

		$args = array(
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',									
		);

		ob_start();
		$this -> widget( $args, $instance );
		$out = ob_get_clean();

		return $out;

	}

	public function get_form_fields( $instance, $settings ) {

		$out = '';

		if( ! is_array( $settings ) ) { return FALSE; }

		foreach( $settings as $setting_slug => $setting ) {

			$id    = esc_attr( $setting['id'] );
			$name  = esc_attr( $setting['name'] );
			$type  = esc_attr( $setting['type'] );
			$label = esc_html( $setting['label'] );
			$value = '';
			if( isset( $instance[ $setting_slug ] ) ) {
				if( $type == 'textarea' ) {
					$value = esc_textarea( $instance[ $setting_slug ] );
				} else {
					$value = $this -> recursive_sanitize( $instance[ $setting_slug ], 'esc_attr' );
				}
			}

			$atts = '';
			if( isset( $setting['atts'] ) ) {
				$atts = $this -> get_attrs_from_array( $setting['atts'] );
			}

			if( isset( $setting['choices'] ) ) {
				$choices = $setting['choices'];
			}

			if( $type == 'textarea' ) {

				$field = "

					<label for='$id'>$label</label>
					<br>
					<textarea class='widefat' id='$id' name='$name' type='$type' value='$value' $atts>$value</textarea>

				";

			} elseif( $type == 'checkbox' ) {

				$checked = checked( 1, $value, FALSE );

				$field = "
					<input id='$id' name='$name' type='$type' $checked value='1'>
					<label for='$id'>$label</label>
				";

			} elseif( $type == 'checkbox_group' ) {

				$fields = new Fields( $value, $id, $name . '[]', $choices );
				$checkboxes = $fields -> get_array_as_checkbox_group();

				$field = "
					<label for='$id'>$label</label>
					<br>
					<div>
						$checkboxes
					</div>
				";	

			} elseif( $type == 'select' ) {

				$fields = new Fields( $value, $id, $name, $choices );
				$options = $fields -> get_array_as_options();

				$field = "
					<label for='$id'>$label</label>
					<br>
					<select class='widefat' id='$id' name='$name' $atts>
						$options
					</select>
				";

			} elseif( $type == 'color' ) {

				wp_enqueue_script( 'iris' );

				$field = "
					<label for='$id'>$label</label>
					<br>
					<input value='$value' class='widefat color_picker' id='$id' name='$name' type='text' $atts>
				";

			} else {

				$field = "
					<label for='$id'>$label</label>
					<br>
					<input class='widefat' id='$id' $atts name='$name' type='$type' value='$value'>
				";

			}

			$out .= "
				<p>$field</p>
			";

		}

		return $out;

	}

	function get_pages_as_array( $post_type = 'page' ) {

		$out = array();

		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => 100,
		);

		$pages = get_posts( $args );

		foreach( $pages as $page ) {
			
			$id   = absint( $page -> ID );
			$name = esc_attr( $page -> post_title );

			$out[ $id ] = $name;
		}

		return $out;

	}	

	function get_image_sizes_as_array() {

		$out = array();

		$featured_image = get_moose() -> featured_image;

		$get_sizes = $featured_image -> get_sizes();

		foreach( $get_sizes as $size_slug => $size ) {
			
			$label = esc_attr( $size['label'] );

			$out[ $size_slug ] = $label;
		}

		return $out;

	}

	function get_pages_as_options( $instance, $slug, $post_type = 'page', $default = FALSE ) {
		
		$current_value = FALSE;
		if( ! empty( $default ) ) {
			$current_value = $default;
		}		
		if( isset( $instance[ $slug ] ) ) {
			$current_value = $instance[ $slug ];
		}

		$id = $this -> id . '-' . $slug;

		$name = $this -> name . '-' . $slug;

		$choices = $this -> get_pages_as_array( $post_type );

		$fields = new Fields( $current_value, $id, $name, $choices );

		$out = $fields -> get_array_as_options();

		return $out;

	}

	function get_image_sizes_as_options( $instance, $slug, $default = FALSE ) {
		
		$current_value = FALSE;
		if( ! empty( $default ) ) {
			$current_value = $default;
		}		
		if( isset( $instance[ $slug ] ) ) {
			$current_value = $instance[ $slug ];
		}

		$id = $this -> id . '-' . $slug;

		$name = $this -> name . '-' . $slug;

		$choices = $this -> get_image_sizes_as_array();

		$fields = new Fields( $current_value, $id, $name, $choices );

		$out = $fields -> get_array_as_options();

		return $out;

	}	

	function get_pages_as_checkbox_group( $instance, $slug, $post_type = 'page', $default = array() ) {

		$current_value = array();
		if( ! empty( $default ) ) {
			$current_value = $default;
		}
		if( isset( $instance[ $slug ] ) ) {
			$current_value = $instance[ $slug ];
		}

		$id = $this -> id . '-' . $slug;

		$name = 'widget' . '-' . $this -> id_base . '[' . $this -> number . ']' . '[' . $slug . ']' . '[]';

		$choices = $this -> get_pages_as_array( $post_type );

		$fields = new Fields( $current_value, $id, $name, $choices );

		$out = $fields -> get_array_as_checkbox_group();

		return $out;
	
	}

	function get_pages_as_radios( $instance, $slug, $post_type = 'page', $default = array() ) {

		$current_value = FALSE;
		if( ! empty( $default ) ) {
			$current_value = $default;
		}
		if( isset( $instance[ $slug ] ) ) {
			$current_value = $instance[ $slug ];
		}

		$id = $this -> id . '-' . $slug;

		$name = 'widget' . '-' . $this -> id_base . '[' . $this -> number . ']' . '[' . $slug . ']';

		$choices = $this -> get_pages_as_array( $post_type );

		$fields = new Fields( $current_value, $id, $name, $choices );

		$out = $fields -> get_array_as_radios();

		return $out;
	
	}	

	function get_array_as_checkbox_group( $instance, $slug, $choices, $default = FALSE ) {

		$current_value = array();
		if( ! empty( $default ) ) {
			$current_value = $default;
		}
		if( isset( $instance[ $slug ] ) ) {
			$current_value = $instance[ $slug ];
		}

		$id = $this -> id . '-' . $slug;

		$name = 'widget' . '-' . $this -> id_base . '[' . $this -> number . ']' . '[' . $slug . ']' . '[]';

		$fields = new Fields( $current_value, $id, $name, $choices );

		$out = $fields -> get_array_as_checkbox_group();

		return $out;

	}	

	function get_array_as_options( $instance, $slug, $choices ) {

		$current_value = FALSE;
		if( isset( $instance[ $slug ] ) ) {
			$current_value = $instance[ $slug ];
		}

		$id = $this -> id . '-' . $slug;

		$name = 'widget' . '-' . $this -> id_base . '[' . $this -> number . ']' . '[' . $slug . ']' . '[]';

		$fields = new Fields( $current_value, $id, $name, $choices );

		$out = $fields -> get_array_as_options();

		return $out;

	}		

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$settings = $this -> get_form_settings( $instance );

		$fields = $this -> get_form_fields( $instance, $settings );

		$shortcode_builder = $this -> get_shortcode_builder(  $instance, $settings  );

		$out = $fields . $shortcode_builder;

		echo $out;

	}

	function get_shortcode_builder( $instance, $settings ) {

		$id = $this -> id;

		$class = get_moose() -> formatting -> get_class( basename( __FILE__ ), __FUNCTION__ );

		$class_name = '';
		$reflect    = new \ReflectionClass( $this );
		$class_name = $reflect -> getShortName();
		$class_name = str_replace( 'Widget', '', $class_name );

		$title = esc_html__( 'Shortcode Builder', 'moose' );

		$args = '';
		foreach( $instance as $instance_k => $instance_v ) {

			if( $instance_v === '' ) { continue; }

			if( is_array( $instance_v ) ) {
				$instance_v = implode( ',', $instance_v );
			}

			$args .= " $instance_k=\"$instance_v\" ";

		}

		$copy = esc_html__( '(copy to clipboard)', 'moose' );

		$out = "
			<div class='$class'>
				<h6 class='$class-title'>$title</h6>
				<div>
					<a href='#' data-copy='$class-shortcode-code-$id' class='$class-copy'><i>$copy</i></a>
				</div>
				<div class='$class-shortcode'>
					<input readonly data-copied='$class-shortcode-code-$id' class='$class-shortcode-code' value='[$class_name $args]'>
				</div>
			</div>
		";

		return $out;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();

		foreach( $new_instance as $k => $dirty ) {
			$clean = $this -> recursive_sanitize( $dirty, 'wp_kses_post' );
			$instance[ $k ] = $clean;
		}

		return $instance;
	
	}

	function get_widget_area_of_widget() {

		// Grab all the widgets.
		$sidebars_widgets = get_option( 'sidebars_widgets' );
		
		// For each sidebar...
		foreach( $sidebars_widgets as $sidebar => $widgets ) {
			
			// No widgets?  Bail.
			if( ! is_array( $widgets ) ) { continue; }

			// Is this not the correct sidebar?  Bail.
			if( ! in_array( $this -> id, $widgets ) ) { continue; }

			return $sidebar;

		}	

		return FALSE;

	}	

}