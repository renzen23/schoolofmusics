<?php

namespace ACA\EC\Editing\Event;

use AC;
use ACP;

class Venue extends ACP\Editing\Model\Meta implements ACP\Editing\PaginatedOptions {

	public function get_view_settings() {
		return [
			'type'               => 'select2_dropdown',
			'ajax_populate'      => true,
			'multiple'           => false,
			'clear_button'       => true,
			'store_single_value' => true,
		];
	}

	public function get_paginated_options( $s, $paged, $id = null ) {
		$entities = new ACP\Helper\Select\Entities\Post( [
			's'         => $s,
			'paged'     => $paged,
			'post_type' => 'tribe_venue',
		] );

		return new AC\Helper\Select\Options\Paginated(
			$entities,
			new ACP\Helper\Select\Formatter\PostTitle( $entities )
		);
	}

	public function get_edit_value( $id ) {
		$post = get_post( $this->column->get_raw_value( $id ) );

		if ( ! $post ) {
			return false;
		}

		return [
			$post->ID => $post->post_title,
		];
	}

}