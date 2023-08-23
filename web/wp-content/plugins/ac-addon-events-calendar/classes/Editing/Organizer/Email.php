<?php

namespace ACA\EC\Editing\Organizer;

use ACP;

class Email extends ACP\Editing\Model\Meta {

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'email';

		return $data;
	}

}