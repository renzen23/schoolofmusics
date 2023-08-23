<?php

namespace ACA\EC\Editing\Venue;

use ACP;
use Tribe__View_Helpers;

class Country extends ACP\Editing\Model\Meta {

	public function get_view_settings() {
		$data = parent::get_view_settings();

		if ( class_exists( 'Tribe__View_Helpers' ) ) {
			$countries = Tribe__View_Helpers::constructCountries();
			$data['type'] = 'select';
			$data['options'] = array_combine( $countries, $countries );
		}

		return $data;
	}

}