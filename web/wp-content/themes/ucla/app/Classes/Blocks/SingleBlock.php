<?php

namespace App\Builder;

use WP_Query;
use function App\ifset;
use function App\ifset_then;
use function App\fpo;

// Block class for single post pages calling builder block modules outside of the Section ACF Builder
// Class name parameter rather than ACF array

class SingleBlock
{
    public $position = 0;
    public $classes = [];
    public $styles = [];

    public function __construct($template)
    {
        global $count;
        $this->position = intval($count++);
        $this->id = 'block-' . $this->position;
        $this->classes = [
            'b-' . str_replace('_', '-', $template),
        ];
        $this->styles = [];

        $fields = get_fields();
        if (!empty($fields)) {
            foreach ($fields as $key => $prop) {
                $this->{$key} = $prop;
            }
        }
    }

    public function add_class($class)
    {
        $this->classes[] = $class;
    }

    public function add_classes(array $classes)
    {
        foreach ($classes as $class) {
            $this->classes[] = $class;
        }
    }

    public function has_class(String $class = '')
    {
        return in_array($class, $this->classes);
    }

    // Has all classes
    public function has_classes(array $classes = [])
    {
        return count(array_intersect($classes, $this->classes)) == count($classes);
    }

    // Has one or more classes
    public function has_classes_or(array $classes = [])
    {
        return count(array_intersect($classes, $this->classes)) != 0;
    }

    public function classes()
    {
        return join(' ', $this->classes);
    }

    public function styles()
    {
        $styles = [];
        foreach ($this->styles as $prop => $value) {
            $styles[] = $prop . ': ' . $value . ';';
        }
        return join(' ', $styles);
    }

    public function show_wrapper()
    {
        if (isset($this->show_wrapper)) {
            return $this->show_wrapper;
        }
        return true;
    }

    /* --------------------------------------------------------------- */
    /* --------------------------------------------------------------- */
    // POST ARCHIVE
    public function post_news_grid()
    {
        // SECTION HEADER COMPONENT
        $this->section_header = new Component();
        $this->news_hero = false;
        $this->type = false;

        $this->add_classes(['cards-3', 'cards-margin-bottom-6rem']);

        // GET POSTS
        global $wp_query;
        // var_dump($wp_query);
        $args = [
            'tag' => $wp_query->query_vars['tag'],
            'category_name' => $wp_query->query_vars['category_name'],
            'posts_per_page' => '9',
        ];
        $posts = get_posts($args);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                setup_postdata($id);
                $this->card_grid->components[] = new Component([
                    'classes' => ['news'],
                    'url' => get_the_permalink($id),
                    'image_src' => ifset(get_the_post_thumbnail_url($id, 'medium_large') ?? false, fpo()->image),
                    'title' => $post->post_title,
                    'text' => get_the_excerpt($post->ID) ? get_the_excerpt($post->ID) : \App\trimContent($post->post_content),
                ]);
                wp_reset_postdata();
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    /* --------------------------------------------------------------- */
    // EVENTS SINGLE PAGE
    public function tribe_events_hero()
    {
        // BLOCK CLASSES AND STYLES
        $this->add_classes(['background-image', 'no-header']);
        $this->image_src = ifset(get_the_post_thumbnail_url(get_the_ID(), 'full') ?? false, fpo()->hero_image);
        $this->styles['background-image'] = 'url(' . $this->image_src . ')';


    }

    /* --------------------------------------------------------------- */
    public function tribe_events_event()
    {
        // GET POSTS
        $id = get_the_ID();
        $post = get_post($id);
        setup_postdata($id);

        // PROPERTIES
        $this->event_box = [
            'cost' => $this->attendance == 'paid' ? '$' . $this->event_price : 'Free',
            'start_date_month' => tribe_get_start_date($id, false, 'M'),
            'start_date_day' => tribe_get_start_date($id, false, 'D'),
            'start_date' => tribe_get_start_date($id, false, 'j'),
            'start_time' => tribe_get_start_date($id, false, 'g:ia'),
            'gcal' => tribe_get_gcal_link($id),
            // "ical" => tribe_get_ical_link($id),
            'ical' => get_permalink($id) . '?ical=1',
        ];

        $this->categories = join(', ', wp_get_post_terms($id, 'tribe_events_cat', ['fields' => 'slugs']));

        $this->title = $post->post_title;
        $this->subtitle = tribe_get_venue($id);
        $this->content = wpautop($post->post_content);

        wp_reset_postdata();
    }

    /* --------------------------------------------------------------- */
    public function tribe_events_call_to_action()
    {
        if ($this->show_call_to_action) {
            // BLOCK CLASSES AND STYLES
            $this->add_class('white-bg');

            // PROPERTIES
            if ($this->call_to_action['rsvp_override'] == true) {
                $this->content = $this->call_to_action['content'];
            } else {
                $global_rsvp_info = get_field('rsvp_info_defaults', 'option');
                $key = $this->call_to_action['rsvp']['value'];
                $this->content = $global_rsvp_info[$key]['rsvp_info_default'];
            }

            // CTA BUTTON COMPONENT
            $this->links_cta = [];
            // Repeater has a min of 1, test if first link url has a value
            if (isset($this->call_to_action['links'][0]['link']['url'])) {
                foreach ($this->call_to_action['links'] as $link) {
                    $this->links_cta[] = new Component([
                        'url' => ifset($link['link']['url'] ?? false),
                        'link_text' => ifset($link['link']['title'] ?? false, 'Learn More'),
                        'target' => ifset($link['link']['target'] ?? false),
                        'classes' => ['pill-cta', $this->has_class('blue-bg') ? 'yellow' : 'blue'],
                    ]);
                }
            }
        } else {
            // HIDE WRAPPER
            // If show show call to action button is toggled off
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    public function tribe_events_video()
    {
        $this->section_header = new Component();

        // Toggle has to be set or an error is shown when calling empty ACF fields.
        if ($this->show_video) {
            // SECTION HEADER COMPONENT
            $this->section_header->header = $this->video['header'];

            // BLOCK CLASSES AND STYLES
            $this->add_class('white-bg');
            $this->add_class($this->video['source']);

            // PROPERTIES
            $this->url = $this->has_class('livestream') ? $this->video['livestream'] : get_field('video', false, false)['field_event_video_video'];
            $this->video = $this->video['video'];
        }

        if (!ifset($this->url ?? false)) {
            // HIDE WRAPPER
            // If the video url is undefined or empty
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    public function tribe_events_info_grid()
    {
        $this->section_header = new Component();
        $this->card_grid = new Component();

        if ($this->show_info_grid) {
            // SECTION HEADER COMPONENT
            $this->section_header->header = $this->info_grid['header'];

            // BLOCK CLASSES AND STYLES
            $this->classes[] = 'cards-2';

            // INFO GRID
            if ($this->info_grid['ticket_override'] == 'true') {
                $this->info_grid['tickets'] = $this->info_grid['tickets_info'];
            } else {
                $global_ticket_info = get_field('ticket_info_defaults', 'option');
                $key = $this->info_grid['tickets']['value'];
                $this->info_grid['tickets'] = $global_ticket_info[$key]['ticket_info_default'];
            }

            // CTA
            if ($this->info_grid['rsvp_override'] == 'true') {
                $this->info_grid['rsvp'] = $this->info_grid['rsvp_info'];
            }
        } else {
            // HIDE WRAPPER
            // If show info grid is toggled off
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    public function tribe_events_events_grid()
    {
        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            'header' => get_field('events_page_header_text', 'option'),
            'cta' => new Component([
                'classes' => ['circle-cta', 'flip', 'yellow'],
                'link_text' => ifset(get_field('events_grid_link', 'option')['title'] ?? false, 'Learn More'),
                'url' => ifset(get_field('events_grid_link', 'option')['url'] ?? false),
            ]),
        ]);

        // GET POSTS
        $id = get_the_id();
        $series = wp_get_post_terms($id, 'event_series', ['fields' => 'slugs']);
        $tribe_categories = wp_get_post_terms($id, 'tribe_events_cat', ['fields' => 'slugs']);
        $tribe_tags = wp_get_post_terms($id, 'post_tag', ['fields' => 'slugs']);

        // If post is in series
        $posts = [];
        if (!empty($series)) {
            $args = [
                'post__not_in' => [$id],
                'post_type' => 'tribe_events',
                'meta_value' => date('Y-m-d H:i:s'),
                'meta_compare' => '>',
                'order' => 'ASC',
                'orderby' => 'meta_value',
                'meta_key' => '_EventStartDate',
                'tax_query' => [
                    [
                        'taxonomy' => 'event_series',
                        'terms' => $series,
                        'field' => 'id',
                    ],
                ],
            ];
            $posts = get_posts($args);
        }

        if (empty($posts)) {
            wp_reset_query();
            $this->section_header->header = get_field('events_page_header_not_series_text', 'option');
            $args = [
                'post__not_in' => [$id],
                'post_type' => 'tribe_events',
                'order' => 'ASC',
                'meta_value' => date('Y-m-d H:i:s'),
                'meta_compare' => '>',
                'orderby' => 'meta_value',
                'meta_key' => '_EventStartDate',
                'tax_query' => [
                    'relation' => 'OR',
                    [
                        'taxonomy' => 'tribe_events_cat',
                        'terms' => $tribe_categories,
                        'field' => 'id',
                    ],
                    [
                        'taxonomy' => 'post_tag',
                        'terms' => $tribe_tags,
                        'field' => 'id',
                    ],
                ],
            ];
            $posts = get_posts($args);
            // var_dump($args);
        }

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                setup_postdata($id);
                $this->card_grid->components[] = new Component([
                    'url' => get_the_permalink($id),
                    'image_src' => ifset(get_the_post_thumbnail_url($id, 'medium_large') ?? false, fpo()->image),
                    'title' => $post->post_title,
                    'text' => get_the_excerpt($post->ID) ? get_the_excerpt($post->ID) : \App\trimContent($post->post_content),
                    'event_box' => [
                        'cost' => get_field('attendance', $id) == 'paid' ? '$' . get_field('event_price', $id) : 'Free',
                        'start_date_month' => tribe_get_start_date($id, false, 'M'),
                        'start_date_day' => tribe_get_start_date($id, false, 'D'),
                        'start_date' => tribe_get_start_date($id, false, 'j'),
                        'start_time' => tribe_get_start_date($id, false, 'g:ia'),
                    ],
                    'subtitle' => join(', ', wp_get_post_terms($id, 'tribe_events_cat', ['fields' => 'slugs'])),
                    'caption' => tribe_get_venue($id),
                ]);
                wp_reset_postdata();
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }

    // RELATED POSTS FOR HIDDEN TAGS

    public function hidden_news_grid($tags,$postID) {

        $this->section_header = new Component([
            "header" => 'Related',
        ]);
        $this->classes[] = "cards-margin-bottom-6rem";

        // GET POSTS

        // hides tags that have boolean set to true to be hidden from main blog
        $hiddenTagsArgs = [
            'meta_key' => 'hide_from_main_blog',
            'meta_value' => '1',
            'meta_compare' => '=',
            'fields' => 'ids',
        ];
        $this->hiddenTags = get_terms($hiddenTagsArgs);

        $this->classes[] = "news-page-latest";
        $args =
            [
                'posts_per_page' => 3,
                'post_type' => 'post',
                'tag' => $tags[0]->slug,
                'post__not_in' => [$postID]
            ];

        $query = new WP_Query($args);
        $posts = $query->posts;
        $this->args = $args;
        $this->type = 'latest';


        $this->add_classes(["grid", "cards-3"]);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $this->card_grid->components[] = new Component([
                    "url" => get_the_permalink($id),
                    "image_src" => ifset(get_the_post_thumbnail_url($id, "medium_large") ?? false, fpo()->image),
                    "image_src_full" => ifset(get_the_post_thumbnail_url($id, "full") ?? false, fpo()->image),
                    "title" => $post->post_title,
                    "text" => get_the_excerpt($id) ?: \App\trimContent($post->post_content, $this->has_class("cards-1") ? 60 : 30)
                ]);
            }
            $this->card_grid->type = 'post';
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    /* --------------------------------------------------------------- */
    // NEWS SINGLE PAGE
    public function news_news_grid()
    {
        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            'header' => 'Related News',
        ]);

        // GET POSTS
        $args = [
            'numberposts' => 3,
        ];
        $posts = get_posts($args);
    }

    /* --------------------------------------------------------------- */
    /* --------------------------------------------------------------- */
    // PEOPLE SINGLE PAGE
    public function people_profile()
    {
        // BLOCK CLASSES AND STYLES
        $this->add_classes(['white-bg', 'extra-spacing']);

        $departments = wp_get_post_terms(get_the_ID(), 'department');
        $studios = wp_get_post_terms(get_the_ID(), 'music_performance_studio');
        $degree_program = wp_get_post_terms(get_the_ID(), 'degree_program', ['fields' => 'names']);

        // PROPERTIES
        $this->tags = array_merge($departments, $studios);
        $this->header = get_the_title();
        $this->image_src = ifset($this->portrait['sizes']['medium_large'] ?? false, fpo()->image);
        $this->external_links = $this->external_links;
        if (!empty($degree_program)):
            if ($this->title) {
                if ( count($degree_program) > 1 ) {
                    $this->title .= '; ';
                }
                else {
                    $this->title .= ', ';
                }

            }
        $this->title .= implode(', ', $degree_program);
        endif;
    }

    /* --------------------------------------------------------------- */
    public function people_video()
    {
        // BLOCK CLASSES AND STYLES
        $this->add_class('black-bg');

        // PROPERTIES
        $this->url = get_field('video', false, false);

        // HIDE WRAPPER
        // If url is not found
        $this->show_wrapper = !empty($this->url);
    }

    /* --------------------------------------------------------------- */
    public function people_research_accordion()
    {
        // BLOCK CLASSES AND STYLES
        $this->add_class('light-bg');

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            'header' => get_the_title() . "'s Research",
        ]);

        // GET POSTS
        // Get all reasearch associated with the person
        $args = [
            'post_type' => 'publications',
            'numberposts' => 10,
            'orderby' => 'date',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'authors',
                    'value' => get_the_ID(),
                    'compare' => 'LIKE',
                ],
            ],
        ];
        $posts = get_posts($args);

        // ACCORDION COMPONENT
        $index = 0;
        $this->accordion_group = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $link = get_field('link', $id);
                $date = get_field('date', $id);
                $text = get_field('description', $id);

                $this->accordion_group->components[] = new Component([
                    'trigger_id' => 'trigger-block-' . $this->position . '-index-' . $index,
                    'collapse_id' => 'collapse-block-' . $this->position . '-index-' . $index,
                    'index_first' => ($index == 0),
                    'title' => $post->post_title,
                    'text' => $text,
                    'date' => ifset($date ?? false),
                    'year' => ifset_then($date ?? false, date('Y', strtotime($date))),
                    'publication' => get_field('publication', $id),
                    'categories' => join(', ', wp_get_post_terms($id, 'category', ['fields' => 'names'])),
                    'authors' => join('<br>', array_column(get_field('authors', $id) ?: [], 'post_title')),
                    'author' => get_the_title(),
                    'cta' => new Component([
                        'classes' => ['circle-cta', 'blue'],
                        'link_text' => ifset($link['title'] ?? false, 'Learn More'),
                        'url' => ifset($link ?? false),
                        'target' => '_blank',
                    ]),
                ]);
                $index++;
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    public function people_news_grid()
    {
        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            'header' => 'Related News',
            'cta' => new Component([
                'classes' => ['circle-cta', 'yellow', 'flip'],
                'url' => '/tag/' . sanitize_title(get_the_title()),
                'link_text' => 'More ' . get_the_title() . ' News',
            ]),
        ]);

        // GET POSTS
        $args = [
            'numberposts' => 3,
            'category__not_in' => 357,
            'tax_query' => [
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'name',
                    'terms' => get_the_title(),
                ],
            ],
        ];
        $posts = get_posts($args);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $this->card_grid->components[] = new Component([
                    'classes' => ['news'],
                    'url' => get_the_permalink($id),
                    'image_src' => ifset(get_the_post_thumbnail_url($id, 'medium_large') ?? false, fpo()->image),
                    'title' => $post->post_title,
                    'text' => \App\trimContent($post->post_content),
                ]);
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
        $this->add_classes(['grid', 'cards-3']);
    }

    /* --------------------------------------------------------------- */
    public function people_press_accordion()
    {
        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            'header' => 'Related Press',
        ]);

        // GET POSTS
        // Get all press posts associated with the person
        $args = [
            'numberposts' => 10,
            'orderby' => 'date',
            'order' => 'ASC',
            'category__in' => 357,
            'tax_query' => [
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'name',
                    'terms' => get_the_title(),
                ],
            ],
        ];
        $posts = get_posts($args);

        // ACCORDION COMPONENT
        $index = 0;
        $this->accordion_group = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $link = get_field('link', $id);
                $date = get_field('date', $id);
                $this->accordion_group->components[] = new Component([
                    'trigger_id' => 'trigger-block-' . $this->position . '-index-' . $index,
                    'collapse_id' => 'collapse-block-' . $this->position . '-index-' . $index,
                    'index_first' => ($index == 0),
                    'title' => $post->post_title,
                    'text' => $post->post_content,
                    'date' => ifset($date ?? false),
                    'year' => ifset_then($date ?? false, date('Y', strtotime($date))),
                    'publication' => get_field('publication', $id),
                    'author' => get_the_title(),
                    'cta' => new Component([
                        'classes' => ['circle-cta', 'blue'],
                        'link_text' => ifset($link['title'] ?? false, 'Learn More'),
                        'url' => ifset($link['url'] ?? false),
                        'target' => ifset($link['target'] ?? false),
                    ]),
                ]);
                $index++;
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    public function people_related_press()
    {
        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            'header' => 'Related Press',
        ]);

        // GET POSTS
        // Get all press posts associated with the person
        $args = [
            'numberposts' => 10,
            'orderby' => 'date',
            'order' => 'ASC',
            'category__in' => 357,
            'tax_query' => [
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'slug',
                    'terms' => get_post_field( 'post_name', get_post() ),
                ],
            ],
        ];
        $posts = get_posts($args);

        // ACCORDION COMPONENT
        $index = 0;
        $this->press_related = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $link = get_field('link', $id);
                $date = get_field('date', $id);
                $this->press_related->components[] = new Component([
                    'id' => $post->ID,
                    'title' => $post->post_title
                ]);
                $index++;
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    public function people_people_grid()
    {
        $id = get_the_id();

        // GET POSTS
        $department = wp_get_post_terms($id, 'department', ['fields' => 'all']);
        $area_ensemble = wp_get_post_terms($id, 'area_ensemble', ['fields' => 'ids']);

        //var_dump($department);

        $args = [
            'post__not_in' => [$id],
            'orderby' => 'rand',
            'post_type' => 'people',
            'numberposts' => -1,
            'tax_query' => [
                [
                    'field' => 'id',
                    'taxonomy' => 'department',
                    'terms' => $department[0]->term_id,
                ],
            ],
        ];
        // if (isset($department) && isset($area_ensemble)) :
        //     $args['tax_query']['relation'] = 'OR';
        //     $args['tax_query']['field'] = 'id';
        // endif;
        // if (isset($area_ensemble)) :
        //     $args['tax_query'] = [
        //         'taxonomy' => 'area_ensemble',
        //         'terms' => $area_ensemble[0],
        //     ];
        // endif;
        // if (isset($department)) :
        //     $args['tax_query'] = [
        //         'taxonomy' => 'department',
        //         'terms' => $department[0],
        //     ];
        // endif;

        $posts = get_posts($args);

        // BLOCK CLASSES AND STYLES
        // Change grid styling based on number of posts
        if (sizeof($posts) == 1) {
            $this->add_classes(['grid', 'feature']);
        } elseif (sizeof($posts) == 2) {
            $this->add_classes(['grid', 'cards-2']);
        } elseif (sizeof($posts) == 3) {
            $this->add_classes(['grid', 'cards-3']);
        } else {
            $this->add_classes(['grid', 'cards-3', 'slider']);
        }

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            'header' => 'Related ' . (ucfirst($people_categories[0] ?? '')) . ' Members',
            'is_slider' => $this->has_class('slider') ?? false,
            'slider_cta_text' => 'See All ' . $department[0]->name . ' Faculty',
            'slider_cta_url' => '/about/faculty/?department=' . $department[0]->slug,
        ]);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                if ($this->has_class('feature')) {
                    $this->card_grid->components[] = new Component([
                        'url' => get_the_permalink($id),
                        'image_src' => ifset($fields['portrait']['sizes']['medium_large'] ?? false, fpo()->image),
                        'cta' => new Component([
                            'classes' => ['circle-cta', 'blue'],
                            'link_text' => 'Learn More'
                        ]),
                        'title' => $post->post_title,
                        'subtitle' => get_field('title', $id),
                        'text' => \App\trimContent(get_field('bio', $id), 60),
                        'external_links' => ifset(get_field('external_links') ?? false),
                    ]);
                } else {
                    $this->card_grid->components[] = new Component([
                        'url' => get_the_permalink($id),
                        'image_src' => ifset(get_field('portrait', $id)['sizes']['medium_large'] ?? false, fpo()->image),
                        'title' => $post->post_title,
                        'text' => get_field('title', $id),
                    ]);
                }
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }

    /* --------------------------------------------------------------- */
    public function people_degrees_grid()
    {
        // BLOCK CLASSES AND STYLES
        $this->add_class('blue-bg');

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            'header' => get_field('degrees_header_text', 'option'),
        ]);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        foreach (get_field('degrees', 'option') as $degree) {
            $tags = [];
            foreach ($degree['links'] as $link) {
                $tag = (object)[
                    'url' => get_the_permalink($link->ID),
                    'link_text' => str_replace($title . ' ', '', $link->post_title),
                ];
                $tags[] = $tag;
            }

            $this->card_grid->components[] = new Component([
                'url' => get_the_permalink($degree['page']->ID),
                'title' => ifset($degree['degrees_title_override'], $degree['page']->post_title),
                'text' => ifset($degree['description']),
                'tags' => $tags,
            ]);
        }
    }

    /* --------------------------------------------------------------- */
    /* --------------------------------------------------------------- */
}
