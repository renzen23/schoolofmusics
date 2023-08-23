<?php

namespace ACA\EC\Column\Event\Field;

use ACA\EC\Column\Event;
use ACA\EC\Editing;
use ACA\EC\Search;

/**
 * @since 1.1.2
 */
class Radio extends Event\Field {

	public function editing() {
		return new Editing\Event\Field\Dropdown( $this );
	}

	public function search() {
		return new Search\Event\Field\Options( $this->get_meta_key(), $this->get_meta_type(), $this->get_field_options() );
	}

	private function get_field_options() {
		$options = explode( "\r\n", $this->get( 'values' ) );

		return array_combine( $options, $options );
	}

}