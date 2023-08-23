<?php

namespace ACA\EC\Editing\Event;

use ACP;

class AllDayEvent extends ACP\Editing\Model\Meta {

	/**
	 * @param int    $id
	 * @param string $value
	 *
	 * @return bool
	 */
	public function save( $id, $value ) {
		if ( '0' === $value ) {
			return false !== delete_post_meta( $id, $this->column->get_meta_key() );
		}

		return parent::save( $id, $value );
	}

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'togglable';
		$data['options'] = [
			'0'   => __( 'No', 'codepress-admin-columns' ),
			'yes' => __( 'Yes', 'codepress-admin-columns' ),
		];

		return $data;
	}

}