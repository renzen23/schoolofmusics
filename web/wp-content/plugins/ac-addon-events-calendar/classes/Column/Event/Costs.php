<?php

namespace ACA\EC\Column\Event;

use ACA\EC\Column\Meta;
use ACP;

class Costs extends Meta
	implements ACP\Search\Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-event_costs' );
		$this->set_label( __( 'Costs', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	public function get_meta_key() {
		return '_EventCost';
	}

	public function get_value( $id ) {
		$value = tribe_get_formatted_cost( $id );

		if ( ! $value ) {
			return $this->get_empty_char();
		}

		return $value;
	}

	public function filtering() {
		$model = new ACP\Filtering\Model\Meta( $this );
		$model->set_data_type( 'numeric' );
		$model->set_ranged( true );

		return $model;
	}

	public function sorting() {
		$model = new ACP\Sorting\Model\Meta( $this );
		$model->set_data_type( 'numeric' );

		return $model;
	}

	public function search() {
		return new ACP\Search\Comparison\Meta\Numeric( $this->get_meta_key(), $this->get_meta_type() );
	}

}