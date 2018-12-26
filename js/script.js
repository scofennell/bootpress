/**
 * Our plugin-wide JS.
 *
 * @package WordPress
 * @subpackage ACEMOD
 * @since ACEMOD 0.1
 */

/**
 * A global object with members that any of our jQuery plugins can use.
 * 
 * @type {Object}
 */
var moose = {

	widgetEvents: 'ready widget-added widget-updated',

	/**
	 * Grab the value of a url var.
	 * @param  {string} url    Any url.
	 * @param  {string} sParam The key for any url variable.
	 * @return {string} The value for sParam in url.
	 */
	getUrlParameter : function ( url, sParam ) {

		// Will hold the value of sParam if it's present in url.
		var out = '';
	 
		// Break the url apart at the query string.
		var array = url.split( '?' );

		// Grab the second half of the url.
		var queryStr = array[1];

		// Break the query string into key value pairs.
		var pairs = queryStr.split( '&' );

		// For each pair...
		jQuery( pairs ).each( function( k, v ) {

			// Break the pair into a key and a value.
			var pair = v.split( '=' );

			// If the key for this pair is what we're looking for...
			if( pair[0] == sParam ) {

				// Grab the value.
				out = pair[1];

			}

		});

		return out;

	},

	getAssocFromForm : function( form ) {

		var out = {};
		jQuery( form ).find( '[name]' ).each( function() {
			out[this.name] = this.value;  
		});
		return out;

	},

	/**
	 * Is the admin bar showing?
	 * 
	 * @return {Boolean} True if the admin bar is showing, else false.
	 */
	hasAdminBar: function() {

		// If the body does not have the admin-bar class, then the admin bar is not even in the document.
		if( ! jQuery( 'body' ).hasClass( 'admin-bar' ) ) { return false; }

		// Even if the admin bar is in the document, we might be hiding it, such as at narrow widths.
		if( ! jQuery( '#wpadminbar' ).is( ':visible' ) ) { return false; }

		return true;

	},

	/**
	 * Grab the height of the admin bar.
	 * 
	 * @return {number} The height of the admin bar.
	 */
	getAdminBarHeight: function() {
		
		var out = jQuery( '#wpadminbar' ).outerHeight();
		
		return out;

	}	

};

/**
 * Our jQuery plugin for doing something.
 */
jQuery( document ).ready( function() {

	// Start an options object that we'll pass when we use our jQuery plugin.
	var options = {};

	// Apply our plugin to our thing.
	jQuery( '[data-MooseToggleTarget]' ).mooseToggle( options );

});

jQuery( document ).ready( function( $ ) {

	/**
	 * Define our jQuery plugin for doing things.
	 * 
	 * @param  {array}  options An array of options to pass to our plugin, documented above.
	 * @return {object} Returns the item that the plugin was applied to, making it chainable.
	 */
	$.fn.mooseToggle = function( options ) {

		// For each element to which our plugin is applied...
		return this.each( function() {

			// Save a reference to the thing, so that we may safely use "this" later.
			var that = this;

			var targetSel = $( that ).attr( 'data-mooseToggleTarget' );
			var targetSelHash = '#' + targetSel;
			var target = $( targetSelHash );

			var focusSel = $( that ).attr( 'data-mooseToggleFocus' );
			var focusSelHash = '#' + focusSel;
			var focus = $( focusSelHash );

			if( $( focus ).length == 0 ) {
				//var focus = $( target ).find( 'a:first' );
			}		

			var toggleStyle = $( that ).attr( 'data-mooseToggleStyle' );
			if( ! toggleStyle ) {
				var toggleStyle = 'fade';
			}

			var closer = $( "[data-mooseToggleCloses='" + targetSel + "']" );
			
			var arrow = $( that ).find( '.dashicons-arrow-down' );

			function focusOn() {

				if( $( target ).is( ':visible' ) ) {

					$( focus ).focus();

				} else {
					$( that ).focus();
					
				}

			}

			function toggleTarget() {

				$( arrow ).toggleClass( 'dashicons-arrow-down dashicons-arrow-up' );

				$( that ).toggleClass( 'MooseToggleOpen MooseToggleClosed' );

				if( toggleStyle == 'fade' ) {
					$( target ).fadeToggle( function() {
						focusOn();
					});
				} else {
					$( target ).slideToggle( function() {
						focusOn();
					});
				}

				$( that ).trigger( 'toggleChange' );

			}

			function closeTarget() {

				$( arrow ).removeClass( 'dashicons-arrow-up' );
				$( arrow ).addClass( 'dashicons-arrow-down' );

				$( that ).addClass( 'MooseToggleClosed' );
				$( that ).removeClass( 'MooseToggleOpen' );

				if( toggleStyle == 'fade' ) {
					$( target ).fadeOut( function() {
						focusOn();
					});
				} else {
					$( target ).slideUp( function() {
						focusOn();
					});
				}

				$( that ).trigger( 'toggleClose' );

			}			

			$( document ).on( 'keydown', function ( e ) {
				
				if ( e.keyCode === 27 ) { // ESC

					closeTarget();

				}
			
			});

			$( that ).on( 'click', function( e ) {

				e.preventDefault();

				$( arrow ).toggleClass( 'dashicons-arrow-down dashicons-arrow-up' );

				$( that ).toggleClass( 'MooseToggleOpen MooseToggleClosed' );

				if( toggleStyle == 'fade' ) {
					$( target ).fadeToggle( function() {
						focusOn();
					});
				} else {
					$( target ).slideToggle( function() {
						focusOn();
					});
				}

				$( that ).trigger( 'toggleChange' );

			});

			$( closer ).on( 'click', function( e ) {
				
				e.preventDefault();

				toggleTarget();

			});

			// Make our plugin chainable.
			return this;

		// End for each element to which our plugin is applied.
		});

	// End the definition of our plugin.
	};

}( jQuery ) );

/**
 * A jQuery plugin to do click-to-copy.
 */
jQuery( document ).ready( function() {

	// This is the wrapper class for each group of share buttons.
	var el = jQuery( '.Widgetphp-get_shortcode_builder-copy' );

	jQuery( el ).mooseCopy();

});

function mooseCopyInit() {

	// For each fieldset of the type, lxb_af-layout_tools.
	var el = jQuery( '.Widgetphp-get_shortcode_builder-copy' );

	jQuery( el ).mooseCopy();

}

/**
 * First we hide them on window load, since that seems to work
 * for both the customizer and widgets.php.
 */
jQuery( window ).load( function() {
	mooseCopyInit();
});
jQuery( document ).on( moose.widgetEvents, function() {
	mooseCopyInit();
});

jQuery( document ).ready( function( $ ) {

	$.fn.mooseCopy = function( options ) {

		// For each group of share buttons...
		return this.each( function() {

			// Store that for later.
			var that = this;
			
			// Need to avoid attaching multiple times.
			$( that ).off( 'click' );
			$( that ).on( 'click', function( event ) {

				event.preventDefault();

				var copiedSel = $( that ).attr( 'data-copy' );
				var copied = $( '*[data-copied="' + copiedSel + '"]' );

				copied.select();
				document.execCommand("copy");
				alert( "Copied the shortcode: " + $( copied ).val() );


			});


		});

	}

}( jQuery ) );

/**
 * A jQuery plugin for doing a fitVid.
 */
jQuery( document ).ready( function() {

	var el = jQuery( 'article.post .post-content' );
	
	jQuery( el ).mooseFitVids();
  	
});

( function ( $ ) {
	
	$.fn.mooseFitVids = function( options ) {

		return this.each( function() {
			
			var that = this;

			$( that ).fitVids();

		});

	};

}( jQuery ) );

/**
 * A jQuery plugin for doing a faceted search.
 */
jQuery( document ).ready( function() {

	var el = jQuery( '[data-faceted_search]' );
	
	jQuery( el ).mooseFacetedSearch();
  	
});

( function ( $ ) {
	
	$.fn.mooseFacetedSearch = function( options ) {

		return this.each( function() {
			
			var that = this;

			// Some elements need to hide until other elements have a particular value.
			var elementsThatRequireOtherElementsToHaveCertainValues = $( that ).find( '[data-requires_key]' ).hide().prop( 'disabled', true );

			// Some elements need to have a particular value in order for other elements to un-hide.
			var elementsThatOtherElemenentsRequireToHaveCertainValues = [];

			// For each element that requires other elements to have certain values...
			$( elementsThatRequireOtherElementsToHaveCertainValues ).each( function( k, v ) {

				var requiresKey = $( v ).attr( 'data-requires_key' );
				var requiresID  = '#' + requiresKey;
				var requiresEl  = $( requiresID );

				elementsThatOtherElemenentsRequireToHaveCertainValues.push( requiresEl );

			});

			// Based on the value of certain elements, update the show/hide status of dependant elements.
			function showHideElements() {

				// For each depenant element...
				$( elementsThatRequireOtherElementsToHaveCertainValues ).each( function( k, v ) {

					// This is the input that would show or hide.
					var input = $( v ).find( ':input' );
				
					// What does this element need?
					var requiresKey = $( v ).attr( 'data-requires_key' );
					var requiresVal = $( v ).attr( 'data-requires_value' );
					var requiresID  = '#' + requiresKey;
					var requiresEl  = $( requiresID );

					// What does the required element currently have?
					var currentVal = $( requiresEl ).val();

					// If the requirements have been met...
					if( requiresVal == currentVal ) {

						$( v ).show();
						$( input ).prop( 'disabled', false );

					// Else if they have not been met...
					} else {

						$( v ).hide();
						$( input ).val( '' );
						$( input ).find( 'option[value=""]').prop( 'selected', true );
						$( input ).prop( 'disabled', true );

					}

				});

			}

			// When required elements are changed, update the status of dependant elements.
			$( elementsThatOtherElemenentsRequireToHaveCertainValues ).each( function() {

				$( this ).on( 'change', function( event ) {
					showHideElements();
				});

			});

			// When the page first loads, update the status as well.
			showHideElements();

		});

	};

}( jQuery ) );

/**
 * A jQuery plugin for doing a login form.
 *
 * Basically the issue is that we want to use the core login form function,
 * but make it compatible with bootstrap.
 */
jQuery( document ).ready( function() {

	var el = jQuery( '.row [name="loginform"]' );
	
	jQuery( el ).mooseLogin();
  	
});

( function ( $ ) {
	
	$.fn.mooseLogin = function( options ) {

		return this.each( function() {
			
			var that = this;

			var email = $( that ).find( '[name="log"]' ).unwrap().addClass( 'form-control required email' ).attr( 'placeholder', 'Email Address' ).wrap( '<div class="form-group"></div>' );
			var emailLabel = $( that ).find( '[for="user_login"]' ).remove();

			var pw      = $( that ).find( '[name="pwd"]' ).unwrap().addClass( 'form-control required password' ).attr( 'placeholder', 'Password' ).wrap( '<div class="form-group"></div>' );
			var pwLabel = $( that ).find( '[for="user_pass"]' ).remove();

			var remember = $( that ).find( '.login-remember' ).remove();

			var submit = $( that ).find( '[type="submit"]' ).unwrap().removeClass( 'button button-primary' ).addClass( 'btn btn-brand col-md-12' );
			
			
		});

	};

}( jQuery ) );
