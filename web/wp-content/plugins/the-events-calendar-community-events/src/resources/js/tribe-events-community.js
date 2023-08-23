/**
 * Community Events JavaScript
 * Linked Posts toggle
 * Event image field function
 * Events list functions
 * Invoke dative datepicker on mobile
 */
var tribe_community_events = tribe_community_events || {};

(function ( window, $, obj ) {
	'use strict';
	/**
	 * jQuery Object of the window
	 *
	 * @type {jQuery}
	 */
	obj.$window = $( window );

	/**
	 * jQuery object of the Datepicker inputs
	 *
	 * @type {jQuery}
	 */
	obj.$datepickers = $();

	/**
	 * jQuery Object of the Timepicker inputs
	 *
	 * @type {jQuery}
	 */
	obj.$timepickers = $();

	/**
	 * Formats a Date object into string yyyy-MM-dd
	 *
	 * @since  4.5
	 *
	 * @param  {Date}   date  Date to be formated
	 * @return {string}
	 */
	obj.date_to_ymd = function ( date ) {
		var d = date.getDate();
		var m = date.getMonth() + 1;
		var y = date.getFullYear();

		return y + '-' + ( m <= 9 ? '0' + m : m ) + '-' + ( d <= 9 ? '0' + d : d );
	}

	/**
	 * Hides the Linked Posts toggle until there is more than one post visible
	 *
	 * @since  4.5
	 *
	 * @return {void}
	 */
	obj.init_linked_posts_handle = function () {

		var handle  = $( '.move-linked-post-group' );
		var trigger = $( '.saved-linked-post' );

		handle.addClass( 'hidden' );

		trigger.on( 'change', function () {
			$( trigger ).closest( handle ).removeClass( 'hidden' );
			$( this ).find( handle ).removeClass( 'hidden' );
		} )
	};

	/**
	 * Change input type from Text to Date when viewport width is
	 * 568px or less in order to invoke the native datepicker
	 *
	 * Add `disableTouchKeyboard` option to the timepicker to prevent
	 * the native keyboard from overlaying timepicker dropdown
	 *
	 * @since  4.5
	 *
	 * @return {void}
	 */
	obj.init_mobile_datetime_input = function () {
		obj.$datepickers = $( '#EventStartDate, #EventEndDate' );
		obj.$timepickers = $( '#EventStartTime, #EventEndTime' );

		obj.$window.on( {
			'resize.tribe': function () {
				var viewport_width = obj.$window.width();

				if ( viewport_width <= 568 ) {
					obj.$datepickers.on( 'change.tribe', obj.on_change_datepicker ).trigger( 'change' );

					// Look for a Tribe fork of jQuery Timepicker to prevent conflicts; load the default if not found.
					if ( 'undefined' !== typeof $.fn.tribeTimepicker ) {
						obj.$timepickers.tribeTimepicker( 'option', 'disableTouchKeyboard', true );
					} else {
						// @deprecated 4.5.6
						obj.$timepickers.timepicker( 'option', 'disableTouchKeyboard', true );
					}
				} else {
					obj.$datepickers.prop( 'type', 'text' );
					obj.$datepickers.off( 'change.tribe' );

					// Look for a Tribe fork of jQuery Timepicker to prevent conflicts; load the default if not found.
					if ( 'undefined' !== typeof $.fn.tribeTimepicker ) {
						obj.$timepickers.tribeTimepicker( 'option', 'disableTouchKeyboard', false );
					} else {
						// @deprecated 4.5.6
						obj.$timepickers.timepicker( 'option', 'disableTouchKeyboard', false );
					}
				}
			},

			'load': function () {
				obj.$window.trigger( 'resize.tribe' );
			}
		} );
	};

	/**
	 * Applied to on `change.tribe` for mobile screens only
	 * Gets turned off when screen is bigger then
	 *
	 * @since  4.5
	 *
	 * @return {void}
	 */
	obj.on_change_datepicker = function() {
		var $field     = $( this );
		var $other     = obj.$datepickers.not( $field );
		var type       = 'start';
		var instance   = $( this ).data( 'datepicker' );

		var date       = $.datepicker.parseDate( instance.settings.dateFormat || $.datepicker._defaults.dateFormat, $field.val(), instance.settings );
		var other_date = $.datepicker.parseDate( instance.settings.dateFormat || $.datepicker._defaults.dateFormat, $other.val(), instance.settings );

		if ( 'EventEndDate' === $field.attr( 'name' ) || 'EventEndDate' === $field.attr( 'id' ) ) {
			type = 'end';
		}

		if ( 'start' === type ) {
			$other.attr( 'min', obj.date_to_ymd( date ) );
			if ( other_date < date ) {
				$other.val( obj.date_to_ymd( date ) );
			}
		} else {
			if ( other_date > date ) {
				$other.val( obj.date_to_ymd( date ) );
			}
		}
	};

	/**
	 * Makes sure we are dealing with the Columns displayed with some sort of caching for user options
	 *
	 * @since  4.5
	 *
	 * @return {void}
	 */
	obj.init_local_storage = function () {
		if ( ! window.localStorage ) {
			return;
		}

		$( '.tribe-toggle-column' ).on( 'change', function() {
			var $button = $( this );
			var id      = $button.attr( 'id' );
			var stored  = window.localStorage.getItem( id );

			if ( $button.is( ':checked' ) ) {
				window.localStorage.removeItem( id );
			} else {
				window.localStorage.setItem( id, '1' );
			}
		} ).each( function() {
			var $button = $( this );
			var id      = $button.attr( 'id' );
			var stored  = window.localStorage.getItem( id );

			if ( stored ) {
				$button.prop( 'checked', false ).trigger( 'change' );
			}
		} );
	};

	/**
	 * Event Image Field Function
	 * Adds the value of the filename from the actual input into a disabled placeholder input
	 * this is all done in the name of pretty forms and accessibility
	 *
	 * @since  4.5
	 *
	 * @return {void}
	 */
	obj.init_image_input = function () {
		var $upload_area = $( '.tribe-image-upload-area ' );
		var $file_name   = $( '#uploadFile' );

		$( "#EventImage" ).change( function () {
			var fileName = $( this ).val();
			var clean    = fileName.split( '\\' ).pop();

			$( $upload_area ).addClass( 'uploaded' );
			$( $file_name ).val( clean );
			$( $file_name ).attr('size', clean.length);
		} );

		$( ".tribe-remove-upload a, .tribe-community-events-preview-image .submitdelete" ).click( function ( e ) {
			e.preventDefault();
			$( $upload_area ).removeClass( 'uploaded has-image' );
			$( "#uploadFile" ).val( '' );
		} );
	};

	/**
	 * Configure the My Events page Columns displayed menu toggle
	 *
	 * @since  4.5
	 *
	 * @return {void}
	 */
	obj.init_list_columns_menu = function () {
		var $display_options = $( '.table-menu' );

		$( '.table-menu-btn' ).click( function () {
			var clickedItem = $( this );
			clickedItem
				.toggleClass( 'menu-open' )
				.parent()
				.find( '.table-menu' )
				.toggleClass( 'table-menu-hidden' );
			return false;
		} );

		// assign click-away-to-close event
		$( document ).click( function ( e ) {
			if ( !$( e.target ).is( $display_options ) ) {
				if ( !$( e.target ).is( $( $display_options ).find( '*' ) ) ) {
					$( $display_options ).addClass( 'table-menu-hidden' );
				}
			}
		} );
	};

	/**
	 * Remove the no-js class from the Community Events container.
	 *
	 * @since  4.5
	 *
	 * @return {void}
	 */
	obj.remove_no_js_class = function() {
		$( document.getElementById( 'tribe-events' ) ).removeClass( 'tribe-no-js' ).addClass( 'tribe-js' );
	};

	/**
	 * Sets up the select2 dropdown for use in the Community editor
	 *
	 * @since  TND
	 *
	 * @return {void}
	 */
	obj.setup_dropdowns = function() {
		$('.tribe-dropdown').trigger('change');
	};

	/**
	 * Reuses the same logic used by TEC in the admin environment to prevent
	 * situations such as an event end time earlier than the event start time
	 * being set.
	 */
	obj.datetime_selectors = function() {
		if ( 'object' === typeof tribe_dynamic_helper_text ) {
			tribe_dynamic_helper_text.event_date_change();
		}
	};

	// Configure all function to run when Doc Ready
	$( document )
		.ready( obj.init_mobile_datetime_input )
		.ready( obj.init_image_input )
		.ready( obj.init_list_columns_menu )
		.ready( obj.init_local_storage )
		.ready( obj.init_linked_posts_handle )
		.ready( obj.remove_no_js_class )
		.ready( obj.remove_no_js_class )
		.ready( obj.setup_dropdowns )
		.ready( obj.datetime_selectors );

})( window, jQuery, tribe_community_events );