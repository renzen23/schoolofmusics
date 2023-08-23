<?php

namespace ACA\EC\Column\Organizer;

use ACA\EC\Column;
use ACA\EC\Editing;
use ACP\Search;
use ACP\Search\Searchable;

class Email extends Column\Meta
	implements Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-organizer_email' )
		     ->set_label( __( 'Email', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	public function get_meta_key() {
		return '_OrganizerEmail';
	}

	public function editing() {
		return new Editing\Organizer\Email( $this );
	}

	public function search() {
		return new Search\Comparison\Meta\Text( $this->get_meta_key(), $this->get_meta_type() );
	}

}