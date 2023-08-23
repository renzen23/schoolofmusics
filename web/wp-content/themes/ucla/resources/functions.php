<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

use Roots\Sage\Config;
use Roots\Sage\Container;

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Roots\\Sage\\Container')) {
    if (!file_exists($composer = __DIR__.'/../vendor/autoload.php')) {
        $sage_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
            __('Autoloader not found.', 'sage')
        );
    }
    require_once $composer;
}

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) use ($sage_error) {
    $file = "../app/{$file}.php";
    if (!locate_template($file, true, true)) {
        $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
    }
}, ['helpers', 'setup', 'filters', 'admin']);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress initially detects theme in themes/sage/resources
 * 2. Upon activation, we tell WordPress that the theme is actually in themes/sage/resources/views
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage/resources
 *
 * We do this so that the Template Hierarchy will look in themes/sage/resources/views for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage/resources
 *
 * This is not compatible with the WordPress Customizer theme preview prior to theme activation
 *
 * get_template_directory()   -> /srv/www/example.com/current/web/app/themes/sage/resources
 * get_stylesheet_directory() -> /srv/www/example.com/current/web/app/themes/sage/resources
 * locate_template()
 * ├── STYLESHEETPATH         -> /srv/www/example.com/current/web/app/themes/sage/resources/views
 * └── TEMPLATEPATH           -> /srv/www/example.com/current/web/app/themes/sage/resources
 */
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
Container::getInstance()
->bindIf('config', function () {
    return new Config([
        'assets' => require dirname(__DIR__).'/config/assets.php',
        'theme' => require dirname(__DIR__).'/config/theme.php',
        'view' => require dirname(__DIR__).'/config/view.php',
    ]);
}, true);


// class EntexAutoSubmenu {

//     /**
//      * Constructor
//      */
//     function __construct(){
//         add_action('publish_page', array($this, 'on_publish_page'));
//         add_action('post_updated', array($this, 'on_publish_page'),10, 3);
//     }

//     /**
//      * When publishing a new child page, add it to the appropriate custom menu.
//      */
//     function on_publish_page($post_id,$post_after="",$post_before=""){

//         // Theme supports custom menus?
//         if (!current_theme_supports('menus')) return;

//         // Published page has parent?
//         $post = get_post($post_id);
//         if ($post->post_type == 'page') {
//             if (!$post->post_parent) return;

//             $all_menus = get_terms('nav_menu', array('hide_empty' => true));

//             // Loop through the menus to find page parent
//             foreach ($all_menus as $menu) {

//                 $menu_parent = NULL;
//                 $menu_items = wp_get_nav_menu_items($menu->term_id, array('post_status' => 'publish,draft'));

//                 if (!is_array($menu_items)) continue;

//                 foreach ($menu_items as $menu_item){
//                     // Item already in menu?
//                     if ($menu_item->object_id == $post->ID) continue 2;
//                     if ($menu_item->object_id == $post->post_parent) $menu_parent = $menu_item;
//                 }

//                 // Add new item
//                 if ($menu_parent) {
//                     wp_update_nav_menu_item($menu->term_id, 0, array(
//                         'menu-item-object-id' => $post->ID,
//                         'menu-item-object' => $post->post_type,
//                         'menu-item-parent-id' => $menu_parent->ID,
//                         'menu-item-type' => 'post_type',
//                         'menu-item-status' => 'publish'
//                     ));
//                 }
//             }
//         }
//     }
// }

// $auto_submenu = new EntexAutoSubmenu();

// define the the_title callback
// add the filter
// add_filter( 'the_title', function ( $title, $id ) {
//     $parent_post_id = wp_get_post_parent_id($id);
//     $page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
//     if ($parent_post_id && $page=="nav-menus.php") :
//         $parent_post = get_post($parent_post_id);
//         $parent_post_title = $parent_post->post_title;
//         return $parent_post_title . " / " .$title;
//     else :
//         return $title;
//     endif;
// } , 10, 2 );



add_shortcode('ucla_tribe_community_form', function(){
    $form = new Tribe__Events__Community__Main();
    $form->maybeLoadAssets(true);
    $form->addScriptsAndStyles();

    return $form->doShortCode();
});


add_filter("rest_people_query", function ($args, $query) {

    $args["order"] = "ASC";
    $args["orderby"] = "meta_value";
    $args["meta_key"] = "last_name";

    return $args;

}, 10, 2);

add_filter("rest_publications_query", function ($args, $query) {
    //print_r($args);
    $args["order"] = "DESC";
    $args["orderby"] = "meta_value";
    $args["meta_key"] = "year";

    //added since author would imply faculty linked publication
    $args["meta_query"] = [
        [
            'key' => 'alumni_author',
            'value' => '',
            'compare' => '>'
        ]
    ];

    return $args;

}, 10, 2);

add_action( 'rest_api_init', 'wp_rest_filter_add_filters' );
 /**
  * Add the necessary filter to each post type
  **/
function wp_rest_filter_add_filters() {
    foreach ( get_post_types( array( 'show_in_rest' => true ), 'objects' ) as $post_type ) {
        add_filter( 'rest_' . $post_type->name . '_query', 'wp_rest_filter_add_filter_param', 10, 2 );
    }
}
/**
 * Add the filter parameter
 *
 * @param  array           $args    The query arguments.
 * @param  WP_REST_Request $request Full details about the request.
 * @return array $args.
 **/
function wp_rest_filter_add_filter_param( $args, $request ) {
    // Bail out if no filter parameter is set.
    if ( empty( $request['filter'] ) || ! is_array( $request['filter'] ) ) {
        return $args;
    }
    $filter = $request['filter'];
    if ( isset( $filter['posts_per_page'] ) && ( (int) $filter['posts_per_page'] >= 1 && (int) $filter['posts_per_page'] <= 100 ) ) {
        $args['posts_per_page'] = $filter['posts_per_page'];
    }
    global $wp;
    $vars = apply_filters( 'rest_query_vars', $wp->public_query_vars );
    function allow_meta_query( $valid_vars )
    {
        $valid_vars = array_merge( $valid_vars, array( 'meta_query', 'meta_key', 'meta_value', 'meta_compare' ) );
        return $valid_vars;
    }
    $vars = allow_meta_query( $vars );

    foreach ( $vars as $var ) {
        if ( isset( $filter[ $var ] ) ) {
            $args[ $var ] = $filter[ $var ];
        }
    }
    return $args;
}



add_filter( 'rest_prepare_publications', function ( $data, $post, $request ) {
    $data->data['category'] = wp_get_post_categories($post->ID,['fields'=>'names'])[0];
	return $data;
}, 10, 3 );

add_filter( 'rest_prepare_people', function ( $data, $post, $request ) {
    $data->data['degree_program_name'] = wp_get_post_terms($post->ID,'degree_program',['fields'=>'names']);
    $data->data['area_ensemble_name'] = wp_get_post_terms($post->ID,'area_ensemble',['fields'=>'names']);
    $data->data['department_name'] = wp_get_post_terms($post->ID,'department',['fields'=>'names']);
	return $data;
}, 10, 3 );



class Tribe__Events__REST__V2__Endpoints__Archive_Event extends Tribe__Events__REST__V1__Endpoints__Archive_Event {

	public function __construct(
		Tribe__REST__Messages_Interface $messages,
		Tribe__Events__REST__Interfaces__Post_Repository $repository,
		Tribe__Events__Validator__Interface $validator
	) {
		parent::__construct( $messages, $repository, $validator );
		$this->post_type = Tribe__Events__Main::POSTTYPE;
    }

	public function get_event_rest_data( $event_id, $context = '' ) {
		$event = get_post( $event_id );

		if ( empty( $event ) || ! tribe_is_event( $event ) ) {
			return new WP_Error( 'event-not-found', $this->messages->get_message( 'event-not-found' ) );
		}

		$meta = array_map( 'reset', get_post_custom( $event_id ) );

		$venue     = $this->get_venue_data( $event_id, $context );
		$organizer = $this->get_organizer_data( $event_id, $context );

		$data = array(
			'id'                     => $event_id,
			'global_id'              => false,
			'global_id_lineage'      => array(),
			'author'                 => $event->post_author,
			'status'                 => $event->post_status,
			'date'                   => $event->post_date,
			'date_utc'               => $event->post_date_gmt,
			'modified'               => $event->post_modified,
			'modified_utc'           => $event->post_modified_gmt,
			'status'                 => $event->post_status,
			'url'                    => get_the_permalink( $event_id ),
			'rest_url'               => tribe_events_rest_url( 'events/' . $event_id ),
			'title'                  => trim( apply_filters( 'the_title', $event->post_title ) ),
			'description'            => trim( apply_filters( 'the_content', $event->post_content ) ),
			'excerpt'                => trim( apply_filters( 'the_excerpt', $event->post_excerpt ) ),
			'slug'                   => $event->post_name,
			'image'                  => $this->get_featured_image( $event_id ),
			'all_day'                => isset( $meta['_EventAllDay'] ) ? tribe_is_truthy( $meta['_EventAllDay'] ) : false,
			'start_date'             => $meta['_EventStartDate'],
			'start_date_details'     => $this->get_date_details( $meta['_EventStartDate'] ),
			'end_date'               => $meta['_EventEndDate'],
			'end_date_details'       => $this->get_date_details( $meta['_EventEndDate'] ),
			'utc_start_date'         => $meta['_EventStartDateUTC'],
			'utc_start_date_details' => $this->get_date_details( $meta['_EventStartDateUTC'] ),
			'utc_end_date'           => $meta['_EventEndDateUTC'],
			'utc_end_date_details'   => $this->get_date_details( $meta['_EventEndDateUTC'] ),
			'timezone'               => isset( $meta['_EventTimezone'] ) ? $meta['_EventTimezone'] : '',
			'timezone_abbr'          => isset( $meta['_EventTimezoneAbbr'] ) ? $meta['_EventTimezoneAbbr'] : '',
			'cost'                   => tribe_get_cost( $event_id, true ),
			'cost_details'           => array(
				'currency_symbol'   => isset( $meta['_EventCurrencySymbol'] ) ? $meta['_EventCurrencySymbol'] : '',
				'currency_position' => isset( $meta['_EventCurrencyPosition'] ) ? $meta['_EventCurrencyPosition'] : '',
				'values'            => $this->get_cost_values( $event_id ),
			),
			'website'                => isset( $meta['_EventURL'] ) ? esc_html( $meta['_EventURL'] ) : get_the_permalink( $event_id ),
			'show_map'               => isset( $meta['_EventShowMap'] ) ? (bool) $meta['_EventShowMap'] : true,
			'show_map_link'          => isset( $meta['_EventShowMapLink'] ) ? (bool) $meta['_EventShowMapLink'] : true,
			'hide_from_listings'     => isset( $meta['_EventHideFromUpcoming'] ) && $meta['_EventHideFromUpcoming'] === 'yes' ? true : false,
			'sticky'                 => $event->menu_order == - 1 ? true : false,
			'featured'               => isset( $meta['_tribe_featured'] ) && $meta['_tribe_featured'] == 1 ? true : false,
			'categories'             => $this->get_categories( $event_id ),
			'tags'                   => $this->get_tags( $event_id ),
			'venue'                  => is_wp_error( $venue ) ? array() : $venue,
			'organizer'              => is_wp_error( $organizer ) ? array() : $organizer,
		);

		/**
		 * Filters the list of contexts that should trigger the attachment of the JSON LD information to the event
		 * REST representation.
		 *
		 * @since 4.6
		 *
		 * @param array $json_ld_contexts An array of contexts.
		 */
		$json_ld_contexts = apply_filters( 'tribe_rest_event_json_ld_data_contexts', array( 'single' ) );

		if ( in_array( $context, $json_ld_contexts, true ) ) {
			$json_ld_data = tribe( 'tec.json-ld.event' )->get_data( $event );

			if ( $json_ld_data ) {
				$data['json_ld'] = $json_ld_data[ $event->ID ];
			}
		}

		// Add the Global ID fields
		$data = $this->add_global_id_fields( $data, $event_id );

		/**
		 * Filters the data that will be returnedf for a single event.
		 *
		 * @param array   $data  The data that will be returned in the response.
		 * @param WP_Post $event The requested event.
		 */
		$data = apply_filters( 'tribe_rest_event_data', $data, $event );

		return $data;
	}

    public function get( WP_REST_Request $request ) {
        $args = array();
        $date_format = Tribe__Date_Utils::DBDATETIMEFORMAT;

        $args['paged'] = $request['page'];
        $args['posts_per_page'] = $request['per_page'];
        $args['start_date'] = isset( $request['start_date'] ) ?
            Tribe__Timezones::localize_date( $date_format, $request['start_date'] )
            : false;
        $args['end_date'] = isset( $request['end_date'] ) ?
            Tribe__Timezones::localize_date( $date_format, $request['end_date'] )
            : false;
        $args['s'] = $request['search'];

        if ( $post__in = $request['include'] ) {
            $args['post__in']                  = $request['include'];
            $args['tribe_remove_date_filters'] = true;
        }

        $args['post_parent'] = $request['post_parent'];

        /**
         * Allows users to override "inclusive" start and end dates and  make the REST API use a
         * timezone-adjusted date range.
         *
         * Example: wp-json/tribe/events/v1/events?start_date=2017-12-21&end_date=2017-12-22
         *
         * - The "inclusive" behavior, which is the default here, would set start_date to
         *   2017-12-21 00:00:00 and end_date to 2017-12-22 23:59:59. Events within this range will
         *   be retrieved.
         *
         * - If you set this filter to false on a site whose timezone is America/New_York, then the
         *   REST API would set start_date to 2017-12-20 19:00:00 and end_date to
         *   2017-12-21 19:00:00. A different range of events to draw from.
         *
         * @since 4.6.8
         *
         * @param bool $use_inclusive Defaults to true. Whether to use "inclusive" start and end dates.
         */
        if ( apply_filters( 'tribe_events_rest_use_inclusive_start_end_dates', true ) ) {

            if ( $args['start_date'] ) {
                $args['start_date'] = tribe_beginning_of_day( $request['start_date'] );
            }

            if ( $args['end_date'] ) {
                $args['end_date'] = tribe_end_of_day( $request['end_date'] );
            }
        }

        $args['meta_query'] = array_filter( array(
            $this->parse_meta_query_entry( $request['venue'], '_EventVenueID', '=', 'NUMERIC' ),
            $this->parse_meta_query_entry( $request['organizer'], '_EventOrganizerID', '=', 'NUMERIC' ),
            $this->parse_featured_meta_query_entry( $request['featured'] ),
        ) );

        $excludeRecitals = ($request['exclude_recitals']) ? array(
            'taxonomy'  => 'tribe_events_cat',
            'field'     => 'slug',
            'terms'     => 'student-recital', // exclude items media items in the news-cat custom taxonomy
            'operator'  => 'NOT IN'
        ) : [];

        $args['tax_query'] = array_filter( array(
            $this->parse_terms_query( $request['categories'], Tribe__Events__Main::TAXONOMY ),
            $this->parse_terms_query( $request['event_series'], 'event_series' ),
            $this->parse_terms_query( $request['tags'], 'post_tag' ),
            $excludeRecitals
        ) );

        $extra_rest_args = array(
            'venue'     => Tribe__Utils__Array::to_list( $request['venue'] ),
            'organizer' => Tribe__Utils__Array::to_list( $request['organizer'] ),
            'featured'  => $request['featured'],
        );
        $extra_rest_args = array_diff_key( $extra_rest_args, array_filter( $extra_rest_args, 'is_null' ) );

        // Filter by geoloc
        if ( ! empty( $request['geoloc'] ) ) {
            $args['tribe_geoloc'] = 1;
            $args['tribe_geoloc_lat'] = isset( $request['geoloc_lat'] ) ? $request['geoloc_lat'] : '';
            $args['tribe_geoloc_lng'] = isset( $request['geoloc_lng'] ) ? $request['geoloc_lng'] : '';
        }

        // When including specific posts date queries will be voided
        if ( isset( $args['post__in'] ) ) {
            unset( $args['start_date'], $args['end_date'] );
            $args['orderby'] = Tribe__Utils__Array::get( $args, 'orderby', array( 'date', 'ID' ) );
            $args['order']   = Tribe__Utils__Array::get( $args, 'order', 'ASC' );
        }

        $args = $this->parse_args( $args, $request->get_default_params() );

        if ( ! empty( $request['monitor'] ) ) {
            $data = array( 'events_data' => array() );
        } else {
            $data = array( 'events' => array() );
        }


        $data['rest_url'] = $this->get_current_rest_url( $args, $extra_rest_args );

        if ( null === $request['status'] ) {
            $cap = get_post_type_object( Tribe__Events__Main::POSTTYPE )->cap->edit_posts;
            $args['post_status'] = current_user_can( $cap ) ? 'any' : 'publish';
        } else {
            $args['post_status'] = $this->filter_post_status_list( $request['status'] );
        }

        // Due to an incompatibility between date based queries and 'ids' fields we cannot do this, see `wp_list_pluck` use down
        // $args['fields'] = 'ids';

        if ( empty( $args['posts_per_page'] ) ) {
            $args['posts_per_page'] = $this->get_default_posts_per_page();
        }

        //print_r($args);

        $events = tribe_get_events( $args );

        $page = $this->parse_page( $request ) ? $this->parse_page( $request ) : 1;

        if ( empty( $events ) && (int) $page > 1 ) {
            $message = $this->messages->get_message( 'event-archive-page-not-found' );

            return new WP_Error( 'event-archive-page-not-found', $message, array( 'status' => 404 ) );
        }

        $events = wp_list_pluck( $events, 'ID' );

        unset( $args['fields'] );

        if ( $this->has_next( $args, $page ) ) {
            $data['next_rest_url'] = $this->get_next_rest_url( $data['rest_url'], $page );
        }

        if ( $this->has_previous( $page, $args ) ) {
            $data['previous_rest_url'] = $this->get_previous_rest_url( $data['rest_url'], $page );;
        }

        foreach ( $events as $event_id ) {
            $event = $this->repository->get_event_data( $event_id );
            $event['livestream'] = (strpos(get_post_meta($event_id,'_ecp_custom_6')[0],'Live Stream') === false) ? 0 : 1;
            if ( ! empty( $request['monitor'] ) ) { //for the digital monitor on campus
                $event = array_intersect_key($event,array_flip(['title','start_date','venue']));
                $event['eventStart'] = $event['start_date']; unset($event['start_date']);
                $event['eventLocation'] = $event['venue']['venue']; unset($event['venue']);
                $event['eventDescription'] = '';
                $data['events_data'][] = $event;
            } else  {
                $data['events'][] = $event;
            }

        }


        $data['total'] = $total = $this->get_total( $args );
        $data['total_pages'] = $this->get_total_pages( $total, $args['posts_per_page'] );

        /**
         * Filters the data that will be returned for an events archive request.
         *
         * @param array           $data    The retrieved data.
         * @param WP_REST_Request $request The original request.
         */
        $data = apply_filters( 'tribe_rest_events_archive_data', $data, $request );

        unset($data['events']->id);

        $response = new WP_REST_Response( $data );

        if ( isset( $data['total'] ) && isset( $data['total_pages'] ) ) {
            $response->header( 'X-TEC-Total', $data['total'], true );
            $response->header( 'X-TEC-TotalPages', $data['total_pages'], true );
        }




        return $response;
    }


}





add_action( 'rest_api_init', function() {
    $messages = tribe( 'tec.rest-v1.messages' );
    $post_repository = tribe( 'tec.rest-v1.repository' );
    $validator = tribe( 'tec.rest-v1.validator' );
    $V2endpoint = new Tribe__Events__REST__V2__Endpoints__Archive_Event( $messages, $post_repository, $validator );

    tribe_singleton( 'tec.rest-v1.endpoints.archive-event', $V2endpoint );

    register_rest_route( 'ucla/events/v1', '/events', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array( $V2endpoint, 'get' ),
        'args'     => $V2endpoint->READ_args(),
    ) );
}
);


// add_filter('tribe_rest_events_archive_data', function($data, $request) {

//     if ($_GET['event_series']) :
//         $data = $V2endpoint->get( $request );

//     endif;

//     return $data;
// }, 10, 2);


function only_search_for_news( $query ) {
    if ( $query->is_search() && $query->is_main_query() && $_GET['news']) {
        $query->set( 'post_type', 'post' );
    }
}
add_action( 'pre_get_posts', 'only_search_for_news' );


// add_action( 'init', function () {
// 	wp_oembed_add_provider( '#https://(www\.)?ustream.tv/*#i', 'https://www.ustream.tv/oembed', true );
// } );

/// get rid of svg styles and ajax pagination that we are not using
add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style('bodhi-svgs-attachment');
    wp_dequeue_style('malinky-ajax-pagination');
}, 100);


//don't load wpcf7 scripts and styles everywhere
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );


function my_rss_modify_item() {
    global $post;
    // Add featured image
    $uploads = wp_upload_dir();
    if (has_post_thumbnail($post->ID)) {
      $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium_large');
      $image = $thumbnail[0];
      $ext = pathinfo($image, PATHINFO_EXTENSION);
      $mime = ($ext == 'jpg') ? 'image/jpeg' : 'image/' . $ext;
      $path = $uploads['basedir'] . substr($image, (strpos($image, '/uploads') + strlen('/uploads')));
      $size = filesize($path);
      echo '<enclosure url="' . esc_url($image) . '" length="' . intval($size) . '" type="' . esc_attr($mime) . '" />' . "\n";
    }
    // Add event location (fudged into the <source> tag)
    if ($post->post_type == 'tribe_events') {
      if ($location = strip_tags(tribe_get_venue($post->ID))) {
        echo '<source url="' . get_permalink($post->ID) . '">' . $location . '</source>'."\n";
      }
      $cats = get_the_terms( $post->ID, 'tribe_events_cat' );
      if (is_array($cats) && $cats[0]) {
        echo '<eventcategory>'.$cats[0]->name.'</eventcategory>'."\n";
      }
    }
  }
  add_action('rss2_item','my_rss_modify_item');

  // Modify feed query
function my_rss_pre_get_posts($query) {
if ($query->is_feed() && $query->tribe_is_event_query) {
    // Change number of posts retrieved on events feed
    $query->set('posts_per_rss', 100);
    // Add restriction to only show events within one week
    $query->set('end_date', date('Y-m-d H:i:s', mktime(23, 59, 59, date('n'), date('j') + 7, date('Y'))));
}
return $query;
}
add_action('pre_get_posts','my_rss_pre_get_posts',99);

// add_action('the_content_feed', 'swap_categories');
// function swap_categories($content) {
//     return "thom"; //preg_replace("/<category>(.*?)<\/category>/gm", "hello", $content);
// }

add_action('the_category_rss', 'clean_category');
function clean_category($content) {
    global $post;
    if ($post->post_type == 'tribe_events') {
        $cats = get_the_terms( $post->ID, 'tribe_events_cat' );
        if (is_array($cats) && $cats[0]) {
          $category = $cats[0]->name;
          return preg_replace("/<category>(.*?)<\/category>/im", "", $content)."<category><![CDATA[$category]]></category>";
        }
      }
    return $content;
}

add_filter( 'ppp_nonce_life', function () {
    return 60 * 60 * 24 * 5; // 5 days
});

// apply_filters('presspermit_unfiltered_content', true);