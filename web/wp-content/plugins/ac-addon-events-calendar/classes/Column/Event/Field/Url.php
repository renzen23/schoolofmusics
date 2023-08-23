<?php

namespace ACA\EC\Column\Event\Field;

use ACA\EC\Column\Event;
use ACA\EC\Editing;

/**
 * @since 1.1.2
 */
class Url extends Event\Field {

	public function editing() {
		return new Editing\Event\Field\Url( $this );
	}

}