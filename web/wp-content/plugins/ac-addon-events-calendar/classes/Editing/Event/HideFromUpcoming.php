<?php

namespace ACA\EC\Editing\Event;

use ACP;

class HideFromUpcoming extends ACP\Editing\Model\Meta {

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'togglable';
		$data['options'] = [
			''    => __( 'No', 'codepress-admin-columns' ),
			'yes' => __( 'Yes', 'codepress-admin-columns' ),
		];

		return $data;
	}

	public function save( $id, $value ) {
		parent::save( $id, $value );

		if ( ! $value ) {
			delete_post_meta( $id, $this->column->get_meta_key() );
		}

		return true;
	}
}