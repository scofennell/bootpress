<?php

/**
 * A class for managing plugin dependencies and loading the plugin.
 *
 * @package WordPress
 * @subpackage Moose
 * @since Moose 0.1
 */
namespace MOOSE;

class Bootstrap {

	public function __construct() {

		add_action( 'after_setup_theme', array( $this, 'create' ), 100 );

	}

	/**
	 * Instantiate and store a bunch of our plugin classes.
	 */
	function create() {

		$moose = get_moose();

		$moose -> fonts                  = new Fonts;
		$moose -> formatting             = new Formatting;
		$moose -> conditionals           = new Conditionals;
		$moose -> image_sizes            = new ImageSizes;
		$moose -> enqueue                = new Enqueue;
		$moose -> icons                  = new Icons;
		$moose -> admin_notices          = new AdminNotices;
		$moose -> admin_ui               = new AdminUI;
		$moose -> template_tags          = new TemplateTags;	
		$moose -> widgets                = new Widgets;
		$moose -> widget_areas           = new WidgetAreas;
		$moose -> post_class             = new PostClass;	
		$moose -> post_type_support      = new PostTypeSupport;	
		$moose -> body_class             = new BodyClass;			
		$moose -> filter_excerpt         = new FilterExcerpt;
		$moose -> filter_shortcodes      = new FilterShortcodes;			
		$moose -> title_tag              = new TitleTag;	
		$moose -> editor_style           = new EditorStyle;
		$moose -> admin_bar		         = new AdminBar;
		$moose -> shortcodes             = new Shortcodes;
		
		return $moose;

	}

}