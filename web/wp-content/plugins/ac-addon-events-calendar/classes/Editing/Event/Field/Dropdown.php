<?php

namespace ACA\EC\Editing\Event\Field;

use ACA\EC\Column\Event\Field;
use ACP;

/**
 * @property  Field $column
 * @since 1.1.2
 */
class Dropdown extends ACP\Editing\Model\Meta {

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['options'] = $this->get_options();
		$data['type'] = 'select';

		return $data;
	}

	/**
	 * @return array
	 */
	private function get_options() {
		$options = explode( "\r\n", $this->column->get( 'values' ) );

		return array_combine( $options, $options );
	}

}