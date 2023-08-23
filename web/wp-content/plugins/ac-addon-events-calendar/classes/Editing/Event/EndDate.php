<?php

namespace ACA\EC\Editing\Event;

use ACP;
use DateTime;

class EndDate extends ACP\Editing\Model\Meta {

	public function save( $id, $value ) {
		$end_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $value );
		$start_date = DateTime::createFromFormat( 'Y-m-d H:i:s', get_post_meta( $id, '_EventStartDate', true ) );

		$args = [
			'EventStartDate'   => $start_date->format( 'Y-m-d' ),
			'EventStartHour'   => $start_date->format( 'H' ),
			'EventStartMinute' => $start_date->format( 'i' ),
			'EventEndDate'     => $end_date->format( 'Y-m-d' ),
			'EventEndHour'     => $end_date->format( 'H' ),
			'EventEndMinute'   => $end_date->format( 'i' ),
		];

		tribe_update_event( $id, $args );

		return true;
	}

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'date_time';

		return $data;
	}

}