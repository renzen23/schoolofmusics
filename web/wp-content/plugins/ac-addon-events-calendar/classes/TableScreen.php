<?php

namespace ACA\EC;

use ACA\EC\Column;
use ACA\EC\ListScreen;
use WP_Query;

class TableScreen {

	/**
	 * @var array
	 */
	private $notices;

	/**
	 * @var array
	 */
	private $filter_vars;

	public function __construct() {
		add_action( 'ac/table_scripts', [ $this, 'table_scripts' ] );
		add_action( 'ac/table/list_screen', [ $this, 'add_events_filter_vars' ] );
		add_action( 'parse_query', [ $this, 'parse_query' ], 51 );
	}

	public function table_scripts() {
		wp_enqueue_style( 'aca-ec-table', ac_addon_events_calendar()->get_url() . 'assets/css/table.css', [], ac_addon_events_calendar()->get_version() );
	}

	/**
	 * @param WP_Query $query
	 */
	public function parse_query( WP_Query $query ) {
		if ( 'tribe_events' !== $query->get( 'post_type' ) ) {
			return;
		}

		if ( ! filter_input( INPUT_GET, 'orderby' ) ) {
			return;
		}

		// This prevents the default tribe event query changes
		$query->tribe_is_event = false;
	}

	public function add_events_filter_vars( $list_screen ) {
		if ( ! $list_screen instanceof ListScreen\Event ) {
			return;
		}

		$prefix = 'ac_related_filter_';

		$input = filter_input_array( INPUT_GET, [
			$prefix . 'post_type'  => FILTER_SANITIZE_STRING,
			$prefix . 'value'      => FILTER_SANITIZE_NUMBER_INT,
			$prefix . 'date'       => FILTER_SANITIZE_STRING,
			$prefix . 'return_url' => FILTER_SANITIZE_STRING,
		] );

		foreach ( $input as $k => $v ) {
			unset( $input[ $k ] );
			$input[ str_replace( $prefix, '', $k ) ] = $v;
		}

		$input = (object) $input;

		switch ( $input->post_type ) {
			case 'tribe_venue':
				$this->filter_on_venue( $input->value );

				break;
			case 'tribe_organizer':
				$this->filter_on_organizer( $input->value );

				break;
			default:
				return; // invalid post type
		}

		$post_type_object = get_post_type_object( $input->post_type );

		if ( ! $post_type_object ) {
			return;
		}

		switch ( $input->date ) {
			case 'future':
				$this->filter_on_future_events();
				$date = __( 'upcoming', 'codepress-admin-columns' );

				break;
			case 'past':
				$this->filter_on_past_events();
				$date = __( 'previous', 'codepress-admin-columns' );

				break;
			default:
				$date = __( 'all', 'codepress-admin-columns' );
		}

		// General notice
		$notice[] = sprintf( __( 'Filtering on %s: Showing %s events from %s.', 'codepress-admin-columns' ),
			$post_type_object->labels->singular_name,
			$date,
			get_the_title( $input->value )
		);

		// Return to related overview link
		$notice[] = sprintf( '<a href="%s" class="notice__actionlink">%s</a>',
			esc_url( admin_url( 'edit.php?' . base64_decode( $input->return_url ) ) ),
			sprintf( __( 'Return to %s', 'codepress-admin-columns' ), $post_type_object->labels->name )
		);

		// Remove filters and stay on this overview link
		$input_remove = [];

		foreach ( $input as $k => $v ) {
			$input_remove[ $prefix . $k ] = false;
		}

		$notice[] = sprintf( '<a href="%s" class="notice-aca-ec-filter__dismiss notice__actionlink">%s</a>',
			add_query_arg( $input_remove ),
			__( 'Remove this filter', 'codepress-admin-columns' )
		);

		$this->notices[] = [
			'type'   => 'success',
			'notice' => implode( ' ', $notice ),
		];

		add_action( 'admin_notices', [ $this, 'display_notices' ] );
		add_action( 'pre_get_posts', [ $this, 'events_query_callback' ] );
	}

	/**
	 * @param WP_Query $wp_query
	 */
	public function events_query_callback( WP_Query $wp_query ) {
		if ( ! $wp_query->is_main_query() ) {
			return;
		}

		$wp_query->query_vars = array_merge( $wp_query->query_vars, $this->filter_vars );
	}

	public function display_notices() {
		foreach ( $this->notices as $notice ) : ?>
			<div class="notice notice-<?php echo $notice['type']; ?> notice-aca-ec-filter">
				<div class="info">
					<p><?php echo $notice['notice']; ?></p>
				</div>
			</div>
		<?php endforeach;
	}

	private function filter_on_venue( $value ) {
		$column = new Column\Event\Venue();

		$this->filter_vars['meta_query'][] = [
			'key'   => $column->get_meta_key(),
			'value' => $value,
		];
	}

	private function filter_on_organizer( $value ) {
		$column = new Column\Event\Organizer();

		$this->filter_vars['meta_query'][] = [
			'key'   => $column->get_meta_key(),
			'value' => $value,
		];
	}

	private function filter_on_past_events() {
		$column = new Column\Event\EndDate();

		$this->filter_vars['meta_query'][] = [
			'key'     => $column->get_meta_key(),
			'value'   => date( 'Y-m-d H:i' ),
			'compare' => '<',
		];
	}

	private function filter_on_future_events() {
		$column = new Column\Event\StartDate();

		$this->filter_vars['meta_query'][] = [
			'key'     => $column->get_meta_key(),
			'value'   => date( 'Y-m-d H:i' ),
			'compare' => '>',
		];
	}

}