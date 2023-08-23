<?php

namespace ACA\EC\Editing\Event;

use ACP;

class Date extends ACP\Editing\Model\Meta {

	public function get_edit_value( $id ) {
		$raw_value = $this->column->get_raw_value( $id );

		if ( ! $raw_value ) {
			return false;
		}

		return date( 'Ymd', strtotime( $raw_value ) );
	}

	public function save( $id, $value ) {
		$meta_key_utc = $this->column->get_meta_key() . 'UTC';

		$time = strtotime( '1970-01-01 ' . date( 'H:i:s', strtotime( $this->column->get_raw_value( $id ) ) ) );
		$time_UTC = strtotime( '1970-01-01 ' . date( 'H:i:s', strtotime( get_post_meta( $id, $meta_key_utc, true ) ) ) );

		$date = date( 'Y-m-d H:i:s', strtotime( $value ) + $time );
		$date_UTC = date( 'Y-m-d H:i:s', strtotime( $value ) + $time_UTC );

		update_metadata( $this->column->get_meta_type(), $id, $this->column->get_meta_key(), $date );
		update_metadata( $this->column->get_meta_type(), $id, $meta_key_utc, $date_UTC );

		return true;
	}

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'date';

		return $data;
	}

}