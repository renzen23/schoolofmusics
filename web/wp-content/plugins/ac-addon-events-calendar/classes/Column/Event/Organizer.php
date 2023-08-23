<?php

namespace ACA\EC\Column\Event;

use AC;
use ACA\EC\Column\Meta;
use ACA\EC\Editing;
use ACA\EC\Filtering;
use ACA\EC\Search;
use ACA\EC\Settings;
use ACP;

class Organizer extends Meta
	implements AC\Column\Relation, ACP\Export\Exportable, ACP\Search\Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-event_organizer' )
		     ->set_label( __( 'Organizer', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	public function get_relation_object() {
		return new AC\Relation\Post( 'tribe_organizer' );
	}

	public function get_meta_key() {
		return '_EventOrganizerID';
	}

	public function get_value( $id ) {
		$values = [];
		$post_ids = $this->get_raw_value( $id );

		if ( ! $post_ids ) {
			return $this->get_empty_char();
		}

		foreach ( $post_ids as $post_id ) {
			$values[] = $this->get_formatted_value( get_the_title( $post_id ), $post_id );
		}

		$setting_limit = $this->get_setting( 'number_of_items' );

		return ac_helper()->html->more( $values, $setting_limit ? $setting_limit->get_value() : false );
	}

	public function get_raw_value( $id ) {
		$value = $this->get_meta_value( $id, $this->get_meta_key(), false );

		$value = array_filter( $value );

		if ( ! $value ) {
			return false;
		}

		return $value;
	}

	public function register_settings() {
		$this->add_setting( new Settings\NonPublicPostLink( $this ) );
		$this->add_setting( new AC\Settings\Column\NumberOfItems( $this ) );
	}

	public function editing() {
		return new Editing\Event\Organizer( $this );
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