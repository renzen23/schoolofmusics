<?php

namespace ACA\EC\Column\Event;

use ACA\EC\Column\Meta;
use ACA\EC\Editing;
use ACA\EC\Filtering;
use ACA\EC\Search;
use ACP\Search\Searchable;

class Featured extends Meta
	implements Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-event_featured' )
		     ->set_label( __( 'Featured', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	public function get_meta_key() {
		return '_tribe_featured';
	}

	public function get_value( $id ) {
		$value = $this->get_raw_value( $id );

		if ( ! $value ) {
			return $this->get_empty_char();
		}

		return ac_helper()->icon->yes();
	}

	public function editing() {
		return new Editing\Event\Featured( $this );
	}

	public function filtering() {
		return new Filtering\Event\Featured( $this );
	}

	public function search() {
		return new Search\Event\Featured( $this->get_meta_key(), $this->get_meta_type() );
	}

}