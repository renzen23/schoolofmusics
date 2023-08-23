<?php

namespace ACA\EC;

use AC;
use ACA\EC\ImportListscreens;
use ACA\EC\ListScreen;
use ACP\Storage\ListScreen\DecoderFactory;

final class EventsCalendar extends AC\Plugin {

	/**
	 * @var string
	 */
	protected $file;

	public function __construct( $file ) {
		$this->file = $file;
	}

	public function register() {
		add_action( 'ac/list_screen_groups', [ $this, 'register_list_screen_groups' ] );
		add_action( 'ac/list_screens', [ $this, 'register_list_screens' ] );
		add_action( 'ac/column_groups', [ $this, 'register_column_groups' ] );
		add_action( 'ac/admin_scripts', [ $this, 'admin_scripts' ] );

		$services = [
			new TableScreen(),
			new ImportListscreens\Message( new ImportListscreens\ImportedSetting() ),
			new ImportListscreens\Controller( new AC\Request(), AC()->get_storage(), new DecoderFactory( AC\ListScreenTypes::instance() ) ),
		];

		foreach ( $services as $service ) {
			if ( $service instanceof AC\Registrable ) {
				$service->register();
			}
		}
	}

	protected function get_file() {
		return $this->file;
	}

	protected function get_version_key() {
		return 'aca_events_calendar';
	}

	public function admin_scripts() {
		wp_enqueue_style( 'aca-ec-admin', $this->get_url() . 'assets/css/admin.css', [], $this->get_version() );
	}

	/**
	 * @param AC\Groups $groups
	 */
	public function register_list_screen_groups( $groups ) {
		$groups->register_group( 'events-calendar', 'Events Calendar', 7 );
	}

	/**
	 * @param AC\AdminColumns $ac
	 */
	public function register_list_screens( AC\ListScreens $list_screens ) {
		$list_screens->register_list_screen( new ListScreen\Event() )
		             ->register_list_screen( new ListScreen\Venue() )
		             ->register_list_screen( new ListScreen\Organizer() );
	}

	/**
	 * @param AC\Groups $groups
	 */
	public function register_column_groups( $groups ) {
		$groups->register_group( 'events_calendar', __( 'The Events Calendar', 'the-events-calendar' ), 11 );
		$groups->register_group( 'events_calendar_fields', __( 'The Events Calendar', 'the-events-calendar' ) . ' - ' . __( 'Additional Fields', 'tribe-events-calendar-pro' ), 11 );
	}

}