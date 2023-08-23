<?php

namespace ACA\EC\Editing\Event;

use ACP;

class Featured extends ACP\Editing\Model\Meta {

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'togglable';
		$data['options'] = [
			'0' => __( 'False', 'codepress-admin-columns' ),
			'1' => __( 'True', 'codepress-admin-columns' ),
		];

		return $data;
	}

}