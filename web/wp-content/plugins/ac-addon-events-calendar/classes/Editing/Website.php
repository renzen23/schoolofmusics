<?php

namespace ACA\EC\Editing;

use ACP;

class Website extends ACP\Editing\Model\Meta {

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'url';

		return $data;
	}

}