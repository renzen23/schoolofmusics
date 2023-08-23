<?php

namespace ACA\EC\Column\Event\Field;

use ACA\EC\Column\Event;
use ACA\EC\Editing;

/**
 * @since 1.1.2
 */
class Textarea extends Event\Field {

	public function editing() {
		return new Editing\Event\Field\Textarea( $this );
	}

}