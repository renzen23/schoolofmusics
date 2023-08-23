<?php

namespace ACA\EC\Column\Event;

use AC;
use ACP;

class Categories extends AC\Column
	implements ACP\Filtering\Filterable, ACP\Editing\Editable, ACP\Export\Exportable, ACP\Search\Searchable {

	public function __construct() {
		$this->set_type( 'events-cats' )
		     ->set_original( true );
	}

	public function get_taxonomy() {
		return 'tribe_events_cat';
	}

	public function editing() {
		return new ACP\Editing\Model\Post\Taxonomy( $this );
	}

	public function filtering() {
		return new ACP\Filtering\Model\Post\Taxonomy( $this );
	}

	public function export() {
		return new ACP\Export\Model\Post\Taxonomy( $this );
	}

	public function search() {
		return new ACP\Search\Comparison\Post\Taxonomy( $this->get_taxonomy() );
	}

}