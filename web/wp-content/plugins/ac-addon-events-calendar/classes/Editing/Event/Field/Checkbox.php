<?php

namespace ACA\EC\Editing\Event\Field;

use ACA\EC\Column\Event\Field;
use ACP;

/**
 * @property  Field $column
 * @since 1.1.2
 */
class Checkbox extends ACP\Editing\Model\Meta {

	public function get_edit_value( $id ) {
		$value = $this->column->get_raw_value( $id );
		$values = explode( '|', $value );

		return array_combine( $values, $values );
	}

	/**
	 * @return array
	 */
	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['options'] = $this->get_field_options();
		$data['type'] = 'checklist';

		return $data;
	}

	/**
	 * @param int   $id
	 * @param array $value
	 *
	 * @return bool
	 */
	public function save( $id, $value ) {
		return parent::save( $id, implode( '|', $value ) );
	}

	/**
	 * @return array
	 */
	private function get_field_options() {
		$options = explode( "\r\n", $this->column->get( 'values' ) );

		return array_combine( $options, $options );
	}

}