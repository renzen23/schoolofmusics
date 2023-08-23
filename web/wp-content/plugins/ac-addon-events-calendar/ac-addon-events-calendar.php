<?php
/*
Plugin Name:    Admin Columns Pro - Events Calendar
Version:        1.4.2
Description:    Events Calendar add-on for Admin Columns Pro
Author:         AdminColumns.com
Author URI:     https://www.admincolumns.com
Plugin URI:     https://www.admincolumns.com
Text Domain:    codepress-admin-columns
Requires PHP:   5.6.20
*/

use AC\Autoloader;
use ACA\EC\Dependencies;
use ACA\EC\EventsCalendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() ) {
	return;
}

require_once __DIR__ . '/classes/Dependencies.php';

add_action( 'after_setup_theme', function () {
	$dependencies = new Dependencies( plugin_basename( __FILE__ ), '1.4.2' );
	$dependencies->requires_acp( '5.1' );
	$dependencies->requires_php( '5.6.20' );

	if ( ! class_exists( 'Tribe__Events__Main', false ) ) {
		$dependencies->add_missing_plugin( __( 'The Events Calendar', 'the-events-calendar' ), $dependencies->get_search_url( 'Events Calendar' ) );
	}

	if ( $dependencies->has_missing() ) {
		return;
	}

	Autoloader::instance()->register_prefix( 'ACA\EC', __DIR__ . '/classes/' );

	$addon = new EventsCalendar( __FILE__ );
	$addon->register();
} );

function ac_addon_events_calendar() {
	return new EventsCalendar( __FILE__ );
}