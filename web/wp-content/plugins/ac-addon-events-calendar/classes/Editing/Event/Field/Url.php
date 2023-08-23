<?php

namespace ACA\EC\Editing\Event\Field;

use ACP;

/**
 * @since 1.1.2
 */
class Url extends ACP\Editing\Model\Meta {

	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'url';

		return $data;
	}

}