<?php

namespace ACA\EC\Editing\Event;

use ACP;

class Sticky extends ACP\Editing\Model {

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'togglable';
		$data['options'] = [
			'0'  => __( 'Not sticky', 'codepress-admin-columns' ),
			'-1' => __( 'Sticky', 'codepress-admin-columns' ),
		];

		return $data;
	}

	public function save( $id, $value ) {
		$result = wp_update_post( [
			'ID'         => $id,
			'menu_order' => $value,
		] );

		if ( is_wp_error( $result ) ) {
			$this->set_error( $result );

			return false;
		}

		return false !== $result;
	}

}