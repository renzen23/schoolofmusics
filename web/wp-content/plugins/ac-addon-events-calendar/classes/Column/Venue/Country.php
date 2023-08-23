<?php

namespace ACA\EC\Column\Venue;

use ACA\EC\Column\Meta;
use ACA\EC\Editing;
use ACA\EC\Search;
use ACP\Search\Searchable;
use Tribe__View_Helpers;

class Country extends Meta
	implements Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-venue_country' )
		     ->set_label( __( 'Country', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	public function get_meta_key() {
		return '_VenueCountry';
	}

	public function editing() {
		return new Editing\Venue\Country( $this );
	}

	public function search() {
		return new Search\Venue\Country( $this->get_meta_key(), $this->get_meta_type(), $this->get_countries() );
	}

	public function get_countries() {
		if ( ! class_exists( 'Tribe__View_Helpers' ) ) {
			return [];
		}

		$countries = Tribe__View_Helpers::constructCountries();

		return array_combine( $countries, $countries );
	}

}