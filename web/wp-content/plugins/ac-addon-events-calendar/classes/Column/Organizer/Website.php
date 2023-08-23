<?php

namespace ACA\EC\Column\Organizer;

use ACA\EC\Column;
use ACA\EC\Editing;
use ACP\Search;
use ACP\Search\Searchable;

class Website extends Column\Meta
	implements Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-organizer_website' )
		     ->set_label( __( 'Website', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	public function get_meta_key() {
		return '_OrganizerWebsite';
	}

	public function editing() {
		return new Editing\Website( $this );
	}

	public function search() {
		return new Search\Comparison\Meta\Text( $this->get_meta_key(), $this->get_meta_type() );
	}

}