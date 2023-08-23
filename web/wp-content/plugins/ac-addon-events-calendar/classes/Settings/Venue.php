<?php

namespace ACA\EC\Settings;

use AC;

class Venue extends AC\Settings\Column\Post {

	public function get_dependent_settings() {
		$setting = [];

		if ( 'website' !== $this->get_post_property_display() ) {
			$setting[] = new NonPublicPostLink( $this->column );
		}

		return $setting;
	}

	protected function get_display_options() {
		$options = [
			'title'   => __( 'Name' ),
			'city'    => __( 'City' ),
			'country' => __( 'Country' ),
			'website' => __( 'Website' ),
		];

		asort( $options );

		return $options;
	}

	public function format( $value, $original_value ) {
		switch ( $this->get_post_property_display() ) {
			case 'country' :
				$_value = tribe_get_country( $original_value );

				break;
			case 'city' :
				$_value = tribe_get_city( $original_value );

				break;
			case 'title' :
				$_value = ac_helper()->post->get_title( $original_value );

				break;
			case 'website' :
				$_value = tribe_get_venue_website_link( $original_value );

				break;
			default:
				$_value = false;
		}

		return $_value;
	}

}