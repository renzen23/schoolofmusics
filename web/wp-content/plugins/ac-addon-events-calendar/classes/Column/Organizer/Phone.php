<?php

namespace ACA\EC\Column\Organizer;

use ACA\EC\Column;
use ACP\Search;
use ACP\Search\Searchable;

class Phone extends Column\Meta
	implements Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-organizer_phone' )
		     ->set_label( __( 'Phone', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	public function get_meta_key() {
		return '_OrganizerPhone';
	}

	public function search() {
		return new Search\Comparison\Meta\Text( $this->get_meta_key(), $this->get_meta_type() );
	}

}