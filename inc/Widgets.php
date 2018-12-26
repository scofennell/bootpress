<?php

/**
 * Register our widgets.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */

namespace MOOSE;

class Widgets {

	public function __construct() {

		add_action( 'widgets_init', array( $this, 'register' ) );

	}

	public function register() {

		$arr = $this -> get();

		foreach( $arr as $widget ) {
			register_widget( __NAMESPACE__ . '\\' . $widget );
		}

	}

	public function get() {

		$out = array();

		$scandir = scandir( MOOSE_PATH . 'inc' );

		foreach( $scandir as $file ) {

			$first_few = substr( $file, 0, 6 );
			$needle = 'Widget';

			if( $first_few !== $needle ) { continue; }

			if( $file === 'WidgetAreas.php' ) { continue; }
			if( $file === 'Widgets.php' ) { continue; }
			if( $file === 'Widget.php' ) { continue; }

			$class_name = str_replace( '.php', '', $file );

			$out[] = $class_name;

		}

		return $out;

	}

	function get_widgets_of_sidebar( $sidebar_id ) {

		// Start an array of widget instance ID's.
		$this_widgets = array();

		// Grab all the widgets.
		$sidebars_widgets = get_option( 'sidebars_widgets' );
		
		// For each sidebar...
		foreach( $sidebars_widgets as $sidebar => $widgets ) {
			
			// If there are no widgets in a given sidebar, skip it.
			if( ! is_array( $widgets ) ) { continue; }

			// If this sidebar is not the one we are looking for, skip it.
			if( $sidebar != $sidebar_id ) {
				continue;
			
			// Cool, we found the widgets.
			} else {
				$this_widgets = $widgets;
				break;
			}

		}

		// Count the widgets.
		$this_widgets_count = count( $this_widgets );
		if( empty( $this_widgets_count ) ) {
			return FALSE;
		}

		return $this_widgets;

	}

	/**
	 * Grab the sidebar for a widget instance.
	 * 
	 * @param  string The widget instance ID.
	 * @return array The ID of the widget area for that widget instance, and a list of siblings.
	 */
	public static function get_sidebar_of_widget( $widget_id ) {

		// Will hold two array members:  The name of the sidebar, and a list of sidebar siblings.
		$out = array();

		// Grab all the widgets.
		$sidebars_widgets = get_option( 'sidebars_widgets' );
		
		// For each sidebar...
		foreach( $sidebars_widgets as $sidebar => $widgets ) {
			
			// No widgets?  Bail.
			if( ! is_array( $widgets ) ) { continue; }

			// Is this not the correct sidebar?  Bail.
			if( ! in_array( $widget_id, $widgets ) ) { continue; }

			// Nice, we're in the correct sidebar.  Now let's dig in and get the siblings.
			$out['parent'] = $sidebar;

			// For each widget in this sidebar...
			foreach( $widgets as $w ) {
 
				// If this is our widget, skip it, since it's not its own sibling.
				if( $w == $widget_id ) { continue; }

				// Nice!  We found a sibling.
				$out['siblings'][ $w ] = self::get_widget_definition( $w );
				
			}

		}	

		return $out;

	}

	/**
	 * Get a widget object via its ID.
	 * 
	 * @param  string $widget_id A widget instance ID.
	 * @return object A dummy instance of that widget.
	 */
	public static function get_widget( $widget_id ) {

		// Get all the widgets.
		global $wp_registered_widgets;

		// May hold an instance of the $widget_id.
		$widget_obj = FALSE;

		// For each widget...
		foreach( $wp_registered_widgets as $w ) {
			
			// Is this not the one we passed it?
			if( $w['id'] != $widget_id ) {
				continue;

			// Okay, here it is.
			} else {
				
				// We have to drill into it like this, I have no idea why.
				$obj = $w['callback'][0];
				
				return $obj;

			}
		
		}

	}

	/**
	 * Get the definition array from when we register a widget in our php code.
	 * 
	 * @param  string $widget_id The ID of the widget we're defining.
	 * @return array  An associative array that defines this widget.
	 */
	public static function get_widget_definition( $widget_id ) {

		// Get an instance of this widget.
		$widget_obj = self::get_widget( $widget_id );

		// Turn it into an array.
		$arr = json_decode( json_encode( $widget_obj ), TRUE );
		
		return $arr;

	}

	/**
	 * Get the settings for a widget.
	 * 
	 * @param  string $widget_id The ID of the widget whose settings we want.
	 * @return array  An array of widget settings.
	 */
	public static function get_settings_of_widget( $widget_id ) {

		// Grab the instance of this widget.
		$widget_obj = self::get_widget( $widget_id );

		if( ! $widget_obj ) { return FALSE; }

		// Grab the settings of this widget.
		$settings = $widget_obj -> get_settings();

		$widget_index_id = self::get_widget_type_index( $widget_id );

		$this_widget_settings = $settings[ $widget_index_id ];

		return $this_widget_settings;

	}

	/**
	 * Given a widget instance ID, determine which instance of the widget type it is.
	 * 
	 * Which instance of this widget are we dealing with?
	 * Like, might have multiple marquee widgets on the page.
	 * 
	 * @param  string $widget_id A widget instance ID.
	 * @return int    A widget type index number.
	 */
	static function get_widget_type_index( $widget_id ) {

		// Break the ID into chunks at each hyphen.
		$widget_type_index_arr = explode( '-', $widget_id );
		if( ! is_array( $widget_type_index_arr ) ) { return FALSE; }
			
		// Grab the last chunk.
		$widget_type_index = array_pop( $widget_type_index_arr );

		// Should just be an integer.
		$out = absint( $widget_type_index );

		return $out;
	
	}

}