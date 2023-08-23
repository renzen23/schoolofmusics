<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add a class based on ACF Flex module */
    if (have_rows('sections')) {
        while (have_rows('sections')) : the_row();
            $template = get_row_layout();
            $classes[] = 'has-' . str_replace('_', '-', $template);
        endwhile;
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    // return ' &hellip; <a href="' . get_permalink() . '">' . __('Read More', 'sage') . '</a>';
    // return ' &hellip; <span>Learn More</span>';
    // return " &hellip;";
    return "";
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);
    if ($template) {
        echo template($template, $data);
        return get_stylesheet_directory() . '/index.php';
    }
    return $template;
}, PHP_INT_MAX);

/**
 * Tell WordPress how to find the compiled path of comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
    $comments_template = str_replace(
        [get_stylesheet_directory(), get_template_directory()],
        '',
        $comments_template
    );
    return template_path(locate_template(["views/{$comments_template}", $comments_template]) ?: $comments_template);
});

/**
 * ACF External Link icon choices
 */
add_filter('acf/load_field/name=external_link_icon', function ($field) {

    // reset choices
    $field['choices'] = array();
    // if has rows
    if (have_rows('external_link_icons', 'option')) {

        // while has rows
        while (have_rows('external_link_icons', 'option')) {

            // instantiate row
            the_row();

            // vars
            $value = get_sub_field('icon');
            $label = get_sub_field('name');

            // append to choices
            $field['choices'][$value] = $label;
        }
    }
    // return the field
    return $field;
});

/**
 * ACF Department or Program choices
 */
add_filter('acf/load_field/name=department_or_program', function ($field) {

    // reset choices
    $field['choices_departments'] = array();
    $field['choices_programs'] = array();
    // if has rows
    if (have_rows('departments', 'option')) {

        // while has rows
        while (have_rows('departments', 'option')) {

            // instantiate row
            the_row();

            // vars
            $value = get_sub_field('department_title');
            $label = get_sub_field('department_title');

            // append to choices
            $field['choices_departments'][$value] = $label;
        }
    }

    if (have_rows('programs', 'option')) {

        // while has rows
        while (have_rows('programs', 'option')) {

            // instantiate row
            the_row();

            // vars
            $value = get_sub_field('program_title');
            $label = get_sub_field('program_title');

            // append to choices
            $field['choices_programs'][$value] = $label;
        }
    }
    $field['choices'] = array_merge($field['choices_departments'], $field['choices_programs']);
    // return the field
    return $field;
});

/**
 * ACF Application Journey Icons
 */
add_filter('acf/load_field/name=application_journey_icon', function ($field) {
    $field['choices'] = array();
    if (have_rows('application_journey_icons', 'option')) {
        while (have_rows('application_journey_icons', 'option')) {
            the_row();
            $label = get_sub_field('name');
            $value = get_sub_field('icon');
            $field['choices'][$value["url"]] = $label;
        }
    }
    return $field;
});

/**
 * Edit Tribe Events API
 */
add_filter('tribe_rest_event_data', function (array $event_data) {


    $event_data["cost"] = get_field("attendance", $event_data["id"]) == "paid" ? "$" . get_field("event_price", $event_data["id"]) : "Free";

    $event_data['preview_image'] = get_field('preview_image', $event_data["id"]);

    // unset( $event_data['json_ld'] );
    unset($event_data['status']);
    unset($event_data['rest_url']);
    unset($event_data['slug']);
    unset($event_data['utc_start_date']);
    unset($event_data['utc_end_date']);
    unset($event_data['utc_start_date_details']);
    unset($event_data['utc_end_date_details']);
    unset($event_data['timezone']);
    unset($event_data['timezone_abbr']);
    unset($event_data['website']);

    $event_data['image'] = $event_data['image']['url'];

    unset($event_data['sticky']);
    unset($event_data['hide_from_listings']);
    unset($event_data['show_map_link']);
    unset($event_data['tags']);
    unset($event_data['venue']['image']);
    unset($event_data['venue']['id']);
    unset($event_data['venue']['author']);
    unset($event_data['venue']['date']);
    unset($event_data['venue']['date_utc']);
    unset($event_data['venue']['status']);
    unset($event_data['venue']['modified']);
    unset($event_data['venue']['modified_utc']);
    unset($event_data['venue']['slug']);
    unset($event_data['venue']['url']);
    unset($event_data['venue']['address']);
    unset($event_data['venue']['city']);
    unset($event_data['venue']['country']);
    unset($event_data['venue']['state']);
    unset($event_data['venue']['zip']);
    unset($event_data['venue']['stateprovince']);
    unset($event_data['venue']['geo_lat']);
    unset($event_data['venue']['geo_lng']);
    unset($event_data['venue']['show_map']);
    unset($event_data['venue']['show_map_link']);
    unset($event_data['venue']['global_id']);
    unset($event_data['venue']['global_id_lineage']);

    unset($event_data['organizer']);

    unset($event_data['categories']['slug']);
    unset($event_data['categories']['term_group']);
    unset($event_data['categories']['term_taxonomy_id']);
    unset($event_data['categories']['taxonomy']);
    unset($event_data['categories']['description']);
    unset($event_data['categories']['parent']);
    unset($event_data['categories']['count']);
    unset($event_data['categories']['filter']);
    unset($event_data['categories']['id']);
    unset($event_data['categories']['urls']);



    unset($event_data['global_id']);
    unset($event_data['global_id_lineage']);
    unset($event_data['author']);
    unset($event_data['date']);
    unset($event_data['date_utc']);
    unset($event_data['modified']);
    unset($event_data['modified_utc']);
    unset($event_data['start_date_details']);
    unset($event_data['end_date_details']);
    // unset( $event_data['cost'] );
    unset($event_data['cost_details']);

    $series = wp_get_post_terms($event_data['id'], 'event_series', array("fields" => "all"));

    $event_data['series'] = $event_data;

    //print_r( $series['categories'] );

    $id = $event_data['id'];
    $event_data['date'] = [
        'month' => tribe_get_start_date($id, false, 'M'),
        'day' => tribe_get_start_date($id, false, 'D'),
        'date' => tribe_get_start_date($id, false, 'j'),
    ];
    $primary_category_id = intval(get_post_meta($id, '_yoast_wpseo_primary_tribe_events_cat', true));
    $categories = $event_data['categories'];
    $selected_category = (count($categories) > 1
        &&
        $primary_category_id ? collect($categories)->first(function ($cat) use ($primary_category_id) {
            return $primary_category_id === $cat->term_id;
        })
        : $categories[0]);
    $event_data['category'] = $selected_category['name'];

    return $event_data;
});

/**
 * ACF Event details default entries
 */

// RSVP Selection
add_filter('acf/load_field/name=rsvp', function ($field) {

    // Reset choices
    $field['choices'] = array();

    // if( have_rows('rsvp_info_defaults', 'option') ) {
    //     while( have_rows('rsvp_info_defaults', 'option') ) {
    //         the_row();
    //         $label = get_sub_field('rsvp_info_select_title');
    //         $value = get_sub_field('rsvp_info_default');
    //         $field['choices'][ $value ] = $label;
    //     }
    // }
    foreach (get_field("rsvp_info_defaults", "option") as $key => $default) {
        $label = $default["rsvp_info_select_title"];
        $field['choices'][$key] = $label;
    }
    return $field;
});

// Ticket Selection
add_filter('acf/load_field/name=tickets', function ($field) {

    // Reset choices
    $field['choices'] = array();

    // if( have_rows('ticket_info_defaults', 'option') ) {
    //     while( have_rows('ticket_info_defaults', 'option') ) {
    //         the_row();
    //         $label = get_sub_field('ticket_info_select_title');
    //         $value = get_sub_field('ticket_info_default');
    //         $field['choices'][ $value ] = $label;
    //     }
    // }
    foreach (get_field("ticket_info_defaults", "option") as $key => $default) {
        $label = $default["ticket_info_select_title"];
        $field['choices'][$key] = $label;
    }
    return $field;
});

// Left Column info default
add_filter('acf/load_field/name=left_column_info', function ($field) {

    $content = get_field('left_column_default', 'option');
    $field['default_value'] = $content;

    return $field;
});

// Right Column info default
add_filter('acf/load_field/name=right_column_info', function ($field) {

    $content = get_field('right_column_default', 'option');
    $field['default_value'] = $content;

    return $field;
});



/*
 * The Events Calendar Remove Events from Month and List Views
 * add coding to theme's functions.php
 * @version 3.12
*/
add_action('pre_get_posts', function ($query) {

    if (isset($query->query_vars['eventDisplay']) && !is_singular('tribe_events')) {

        if ($query->is_main_query() && $query->query_vars['eventDisplay'] == 'custom' && !is_tax(\Tribe__Events__Main::TAXONOMY) && $query->is_tax !== true) {
            $query->set('tax_query', [
                [
                    'taxonomy' => \Tribe__Events__Main::TAXONOMY,
                    'field'    => 'slug',
                    'terms'    => ['student-recital'],
                    'operator' => 'NOT IN'
                ]
            ]);
        }
    }

    return $query;
});


/**
 * Display Categories with Upcoming Events only
 */
function categories($data)
{

    // if ( false === ( $event_cats = get_transient( 'dt_categories' ) ) ) {

    $events = tribe_get_events([
        'posts_per_page' => -1,
        'start_date' => date('Y-m-d H:i:s'),
    ]);

    $event_cats = [];

    foreach ($events as $event) {
        //Put each upcoming category in an array
        $cats = get_the_terms($event->ID, 'tribe_events_cat');

        if (is_array($cats)) {
            foreach ($cats as $cat) {
                $result = array_filter($event_cats, function ($item) use ($cat) {
                    if (isset($item->term_id)) {
                        return ($item->term_id == $cat->term_id);
                    }
                    return true;
                });

                if (!$result) {
                    $event_cats[] = $cat;
                }
            }
        }
    }

    function cmp($a, $b)
    {
        return strcmp($a->name, $b->name);
    }

    usort($event_cats, __NAMESPACE__ . '\\cmp');

    // cache for 2 hours
    // set_transient( 'dt_categories', $event_cats, 60*60*2 );

    // }

    return $event_cats;
}

/**
 * Display Series with Upcoming Events only
 */
function series($data)
{

    // if ( false === ( $event_cats = get_transient( 'dt_series' ) ) ) {

    $events = tribe_get_events([
        'posts_per_page' => -1,
        'start_date' => date('Y-m-d H:i:s'),
    ]);

    $event_series = [];

    foreach ($events as $event) {
        //Put each upcoming category in an array
        $series = get_the_terms($event->ID, 'event_series');

        if (is_array($series)) {
            foreach ($series as $serie) {
                $result = array_filter($event_series, function ($item) use ($serie) {
                    if (isset($item->term_id)) {
                        return ($item->term_id == $serie->term_id);
                    }
                    return true;
                });

                if (!$result) {
                    $event_series[] = $serie;
                }
            }
        }
    }

    function cmp($a, $b)
    {
        return strcmp($a->name, $b->name);
    }

    usort($event_series, __NAMESPACE__ . '\\cmp');

    // cache for 2 hours
    // set_transient( 'dt_series', $event_series, 60*60*2 );

    // }

    return $event_series;
}


// register the endpoint
add_action('rest_api_init', function () {

    register_rest_route(
        'events',
        '/categories/',
        [
            'methods' => 'GET',
            'callback' => __NAMESPACE__ . '\\categories',
        ]
    );

    register_rest_route(
        'events',
        '/series/',
        [
            'methods' => 'GET',
            'callback' => __NAMESPACE__ . '\\series',
        ]
    );
});

/**
 * Include Program Query Var to Show under Event's Single Page
 */
add_filter( 'query_vars', function( $query_vars ) {
    $query_vars[] = 'program';
    return $query_vars;
} );

/**
 * Make ACF object response available as Array on Event's Layout
 */
add_filter('sober/controller/acf/array', function () {
    return true;
});