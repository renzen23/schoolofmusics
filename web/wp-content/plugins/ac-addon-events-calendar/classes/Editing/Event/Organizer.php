<?php

namespace ACA\EC\Editing\Event;

use AC;
use ACP;

class Organizer extends ACP\Editing\Model\Meta implements ACP\Editing\PaginatedOptions {

	public function get_view_settings() {
		return [
			'type'               => 'select2_dropdown',
			'ajax_populate'      => true,
			'multiple'           => true,
			'clear_button'       => true,
			'store_single_value' => true,
		];
	}

	public function get_paginated_options( $s, $paged, $id = null ) {
		$entities = new ACP\Helper\Select\Entities\Post( [
			's'         => $s,
			'paged'     => $paged,
			'post_type' => 'tribe_organizer',
		] );

		return new AC\Helper\Select\Options\Paginated(
			$entities,
			new ACP\Helper\Select\Formatter\PostTitle( $entities )
		);
	}

	public function get_edit_value( $id ) {
		$values = [];
		$ids = $this->column->get_raw_value( $id );

		if ( ! $ids ) {
			return $values;
		}

		foreach ( $ids as $_id ) {
			$values[ $_id ] = html_entity_decode( get_the_title( $_id ) );
		}

		return $values;
	}

	public function save( $id, $values ) {
		delete_post_meta( $id, $this->column->get_meta_key() );

		foreach ( $values as $value ) {
			add_post_meta( $id, $this->column->get_meta_key(), $value );
		}

		return true;
	}

}