<?php

namespace ACA\EC\Column;

use ACP;

abstract class Meta extends ACP\Column\Meta {

	public function __construct() {
		$this->set_group( 'events_calendar' );
	}

	public function editing() {
		return new ACP\Editing\Model\Meta( $this );
	}

	public function filtering() {
		return new ACP\Filtering\Model\Meta( $this );
	}

	public function sorting() {
		return new ACP\Sorting\Model\Meta( $this );
	}

}