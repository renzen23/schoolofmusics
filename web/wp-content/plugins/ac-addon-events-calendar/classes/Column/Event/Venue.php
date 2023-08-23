<?php

namespace ACA\EC\Column\Event;

use AC;
use ACA\EC\Column\Meta;
use ACA\EC\Editing;
use ACA\EC\Filtering;
use ACA\EC\Search;
use ACA\EC\Settings;
use ACP;

class Venue extends Meta
	implements AC\Column\Relation, ACP\Export\Exportable, ACP\Search\Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-event_venue' )
		     ->set_label( __( 'Venue', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	public function get_relation_object() {
		return new AC\Relation\Post( 'tribe_venue' );
	}

	public function get_meta_key() {
		return '_EventVenueID';
	}

	public function get_value( $id ) {
		$value = $this->get_raw_value( $id );

		if ( ! $value ) {
			return $this->get_empty_char();
		}

		return $this->get_formatted_value( $value );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Venue( $this ) );
	}

	public function editing() {
		return new Editing\Event\Venue( $this );
	}

	public function filtering() {
		return new Filtering\RelatedPost( $this );
	}

	public function sorting() {
		return new ACP\Sorting\Model\Value( $this );
	}

	public function export() {
		return new ACP\Export\Model\StrippedValue( $this );
	}

	public function search() {
		return new Search\Event\Relation( $this->get_meta_key(), $this->get_meta_type(), $this->get_relation_object() );
	}

}