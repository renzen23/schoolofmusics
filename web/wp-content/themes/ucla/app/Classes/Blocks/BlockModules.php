<?php

namespace App\Builder;

use stdClass;
use WP_Query;

use function App\ifset;
use function App\ifset_then;
use function App\fpo;

// Block functions separate from parent class

class BlockModules extends Block
{

    /**
     * ifset($var ?? false, $default)
     * To resolve issues when calling empty ACF variables inside repeater fields.
     * Returns $var if $var is defined and true.
     * Returns $default if $var is undefined, empty, or false.

     * ifset_then($var ?? false, $default)
     * Returns $default if $var is defined and true.
     * Returns false if $var is undefined, empty, or false.
     * >> helpers.php
     */

    /* --------------------------------------------------------------- */
    public function hero()
    {

        $this->image_src = ($this->image["sizes"]["hero"] ?? false) ?: ($this->image["url"] ?? false) ?: fpo()->hero_image;

        // BLOCK CLASSES AND STYLES
        $this->add_class($this->style);
        // Trim and remove empty tags, if header empty then give block the class "no-header"
        $this->header = trim(preg_replace("/<[^\/>]*>([\s]?)*<\/[^>]*>/", '', $this->header));
        if (!$this->header) {
            $this->add_class("no-header");
        }

        if ($this->has_class("image-mask")) {
            $mask_class = $this->image_mask_style == "custom" ? "custom-mask" : "hero-mask";
            // .right class positions IMAGE mask on right side
            $this->add_classes(["right", $mask_class, $this->background]);
        } else if ($this->has_class("background-image")) {
            // Get full image with fpo default
            $this->styles['background-image'] = "url(" . $this->image_src . ")";
        }

        // CTA BUTTON COMPONENT
        $this->cta = new Component([
            "url" => ifset($this->link["url"] ?? false),
            "target" => ifset($this->link["target"] ?? false),
            "link_text" => ifset($this->link["title"] ?? false, "Learn More"),
            "classes" => ["circle-cta"],
        ]);

        if (!is_front_page()) {
            $this->cta->add_class("yellow");
            $this->cta->add_class("flip");
            $this->cta->add_class("white-text");
        } else {
            // Setup styling for the cta buttons based on background color/image

            if ($this->has_class("image-mask")) {
                if ($this->has_class("blue-bg")) {
                    $this->cta->add_classes(["yellow", "white-text"]);
                } else {
                    $this->cta->add_class("blue");
                }
            } else if ($this->has_class("background-image")) {
                $this->cta->add_classes(["blue", "flip", "white-text"]);
            }
        }

        // Emphasized link styling
        if ($this->link_style != "standard-link") {
            $this->cta->add_classes(explode(" ", $this->link_style));
            if ($this->link_style == "emphasized-cta em-link-text") {
                $this->cta->emphasized_link_text =  $this->emphasized_link_text;
            }
        }

        // IMAGE MASK COMPONENT
        if ($this->has_class("image-mask")) {

            if ($this->has_class("custom-mask")) {
                // Show single custom image mask, e.g. a, b, c, d, e
                $masks = [$this->mask];
            } else {
                if ($this->image_mask_style == "home-slider") {
                    // Multiple image masks to animate through on home page
                    $this->add_class("home-slider");
                    $masks = [
                        "original",
                        "ethnomusicology",
                        "musicology",
                        "music",
                        "music_industry",
                        "global_jazz",
                    ];
                    shuffle($masks);
                } else {
                    $masks = [$this->snakecase($this->image_mask_style)];
                }
            }

            $index = 0;
            $this->image_mask_group = new Component();
            foreach ($masks as $mask) {
                $mask_field = get_field($mask, "option");
                $image_mask = new Component([
                    "mask_id" => "mask-block-" . $this->position . "-mask-" . $index,
                    "index" => $index,
                ]);

                if ($this->has_class("hero-mask")) {
                    $image_mask->add_properties([
                        "image_src" => ifset($mask_field["image"]["url"] ?? false, fpo()->hero_image_mask),
                        "desktop_svg" => $mask_field["desktop_svg"]["url"],
                        "mobile_svg" => $mask_field["mobile_svg"]["url"],
                        "desktop_offset" => $mask_field["desktop_offset"],
                        "mobile_offset" => $mask_field["mobile_offset"],
                        "svg_title" => ifset($mask_field["image"]["title"] ?? false, ""),
                        "svg_description" => ifset($mask_field["image"]["alt"] ?? false, ""),
                    ]);
                }

                if ($this->has_class("custom-mask")) {
                    $image_mask->add_properties([
                        "image_src" => $this->image_src,
                        "image_caption" => $this->image_caption,
                        "right_svg" => $mask_field["right_svg"]["url"],
                        "svg_title" => ifset($this->image["title"] ?? false, ""),
                        "svg_description" => ifset($this->image["alt"] ?? false, ""),
                    ]);
                }

                $this->image_mask_group->components[] = $image_mask;
                $index++;
            }
        }

        // CONTACT INFO BOX
        $this->show_contact_info_box = $this->contact_info_box && $this->contact_info && $this->has_class("background-image");
        if ($this->show_contact_info_box) {
            $this->add_class("contact-hero");
        }
    }


    /* --------------------------------------------------------------- */
    public function info_content()
    {

        // BLOCK CLASSES AND STYLES
        $this->add_classes(explode(" ", $this->style));

        if ($this->has_classes_or(["standard", "image-block"])) {
            $this->image_src = ifset($this->image["sizes"]["large"] ?? false, fpo()->image);
            $this->add_class("extra-spacing");
        }

        if ($this->has_class("background-image")) {
            if ($this->has_class("inverted")) {
                $this->image_src = ($this->image["sizes"]["hero"] ?? false) ?: ($this->image["url"] ?? false) ?: fpo()->hero_image;
            } else {
                $this->image_src = ($this->image["url"] ?? false) ?: fpo()->hero_image;
            }
            $this->styles['background-image'] = "url(" . $this->image_src . ")";
        }

        if ($this->has_class("image-mask")) {
            $this->image_src =  ($this->image["url"] ?? false) ?: fpo()->image_mask;
            // Flip gradient only when image mask is on the left side
            $this->add_classes([$this->background, $this->has_class("left") ? "flip-gradient" : null, "custom-mask"]);
        }

        // PROPERTIES
        // Show header on left or right side on desktop
        $this->primary_header = false;
        $this->title = false;

        if ($this->has_classes_or(["standard", "background-image"])) {
            $this->primary_header = $this->header;
            // Change header font size based on string length / word character count
            $string = strip_tags($this->primary_header);
            $words  = explode(' ', $string);
            $longestWordLength = 0;
            foreach ($words as $word) {
                if (strlen($word) > $longestWordLength) {
                    $longestWordLength = strlen($word);
                }
            };

            if (strlen(strip_tags($this->primary_header)) > 30) {
                $this->add_class("small-header");
            } elseif ($longestWordLength > 13) {
                $this->add_class("medium-header");
            } else {
                $this->add_class("large-header");
            }
        } else {
            $this->title = $this->header;
            $this->details = false;
        }

        // CTA BUTTON COMPONENT
        // White text when links are against darker background
        $white_cta_text = $this->has_class("inverted") || ($this->has_classes(["image-mask", "blue-bg"])) ? "white-text" : null;

        $this->cta = new Component([
            "url" => ifset($this->body_link["url"] ?? false),
            "target" => ifset($this->body_link["target"] ?? false),
            "classes" => ["circle-cta", $this->has_class("blue-bg") ? "yellow" : "blue", $white_cta_text],
            "link_text" => ifset($this->body_link["title"] ?? false, "Learn More"),
        ]);


        // DETAILS CTA BUTTONS
        // SIDE CTA BUTTONS
        $this->details_links_cta = [];
        $this->header_links_cta = [];

        if ($this->has_classes_or(["standard", "background-image"])) {
            // Repeater has a min of 1, test if first link url has a value
            if (isset($this->details_links[0]["link"]["url"])) {
                foreach ($this->details_links as $link) {
                    $this->details_links_cta[] = new Component([
                        "url" => ifset($link["link"]["url"] ?? false),
                        "link_text" =>  ifset($link["link"]["title"] ?? false, "Learn More"),
                        "target" => ifset($link["link"]["target"] ?? false),
                        "classes" => ["circle-cta", "yellow", "small", $white_cta_text],
                    ]);
                }
            }
            // Repeater has a min of 1, test if first link url has a value
            if (isset($this->header_links[0]["link"]["url"])) {
                foreach ($this->header_links as $link) {
                    $this->header_links_cta[] = new Component([
                        "url" => ifset($link["link"]["url"] ?? false),
                        "link_text" =>  ifset($link["link"]["title"] ?? false, "Learn More"),
                        "target" => ifset($link["link"]["target"] ?? false),
                        "classes" => ["circle-cta", "blue", $white_cta_text],
                    ]);
                }
            }
        }

        // IMAGE MASK COMPONENT
        $index = 0;
        $side = $this->has_class("right") ? "right" : "left";
        $this->image_mask_group = new Component();
        if ($this->has_class("image-mask")) {
            $mask_field = get_field($this->mask, "option");
            $this->image_mask_group->components[] = new Component([
                "mask_id" => "mask-block-" . $this->position . "-mask-" . $index,
                "svg_title" => ifset($this->image["title"] ?? false, ""),
                "svg_description" => ifset($this->image["alt"] ?? false, ""),
                "image_src" => $this->image_src,
                "image_caption" => $this->image_caption,
                $side . "_svg" => $mask_field[$side . "_svg"]["url"],
            ]);
        }
    }


    /* --------------------------------------------------------------- */
    public function navigation_carousel()
    {

        // BLOCK CLASSES AND STYLES
        // explode because acf slug a has space in it
        $this->add_classes(explode(" ", $this->style));
        $this->add_class("custom-mask");

        // CTA BUTTON COMPONENT
        $this->cta = new Component([
            "classes" => ["circle-cta", "grey"],
        ]);

        // IMAGE MASK COMPONENT
        // Loop through both categories and items
        $index = 0;
        $side = $this->has_class("right") ? "right" : "left";
        $mask_field = get_field($this->mask, "option");
        $this->image_mask_group = new Component();
        foreach ($this->categories as $ul) {
            if (!empty($ul)) {
                foreach ($ul["items"] as $li) {
                    $this->image_mask_group->components[] = new Component([
                        "image_src" => ifset($li["image"]["url"] ?? false, fpo()->image_mask),
                        "svg_title" => ifset($li["image"]["title"] ?? false, ""),
                        "svg_description" => ifset($li["image"]["alt"] ?? false, ""),
                        "image_caption" => ifset($li["image_caption"] ?? false),
                        "mask_id" => "mask-block-" . $this->position . "-mask-" . $index,
                        $side . "_svg" => $mask_field[$side . "_svg"]["url"],
                    ]);
                    $index++;
                }
            }
        }
    }


    /* --------------------------------------------------------------- */
    public function quotes()
    {

        // BLOCK CLASSES AND STYLES
        $this->add_classes(explode(" ", $this->style));
        $this->add_class("custom-mask");

        // IMAGE MASK COMPONENT
        $index = 0;
        $side = $this->has_class("right") ? "right" : "left";
        $mask_field = get_field($this->mask, "option");
        $this->image_mask_group = new Component();
        foreach ($this->quotes as $quote) {
            $this->image_mask_group->components[] = new Component([
                "image_src" => ifset($quote["image"]["url"] ?? false, fpo()->image_mask),
                "svg_title" => ifset($quote["image"]["title"] ?? false, ""),
                "svg_description" => ifset($quote["image"]["alt"] ?? false, ""),
                "image_caption" => ifset($quote["image_caption"] ?? false),
                "mask_id" => "mask-block-" . $this->position . "-mask-" . $index,
                $side . "_svg" => $mask_field[$side . "_svg"]["url"],
            ]);
            $index++;
        }
    }


    /* --------------------------------------------------------------- */
    public function call_to_action()
    {

        // BLOCK CLASSES AND STYLES
        $this->add_classes(explode(" ", $this->style));

        // PROPERTIES
        if ($this->has_class("blue-bg")) {
            $this->content = false;
        } else {
            $this->header = false;
        }

        // CTA BUTTON COMPONENT
        $this->links_cta = [];
        if (isset($this->links[0]["link"]["url"])) {
            foreach ($this->links as $link) {
                $this->links_cta[] = new Component([
                    "url" => ifset($link["link"]["url"] ?? false),
                    "link_text" =>  ifset($link["link"]["title"] ?? false, "Learn More"),
                    "target" => ifset($link["link"]["target"] ?? false),
                    "classes" => ["pill-cta", $this->has_class("blue-bg") ? "yellow" : "blue"],
                ]);
            }
        }
    }


    /* --------------------------------------------------------------- */
    public function contacts_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // CARD GRID COMPONENT
        // Show wrapper if info is added on the first contact
        $this->card_grid = new Component();
        foreach ($this->contacts as $contact) {
            $text = ifset_then($contact["name"] ?? false, $contact["name"] . "<br>", "");
            $text .= ifset_then($contact["phone"] ?? false, $contact["phone"] . "<br>", "");
            $text .= ifset_then($contact["email"] ?? false, "<a href='mailto:" . $contact["email"] . "'>" . $contact["email"] . "</a>", "");
            $this->card_grid->components[] = new Component([
                "url" => ifset_then($contact["email"] ?? false, "mailTo:" . $contact["email"]),
                "title" => ifset($contact["reason_of_contact"] ?? false),
                "text" => ifset($text),
            ]);
        }
    }


    /* --------------------------------------------------------------- */
    public function degrees_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // BLOCK CLASSES AND STYLES
        $this->classes[] = $this->background;

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        foreach (get_field('degrees', 'option') as $degree) {

            $tags = [];
            foreach ($degree["links"] as $link) {
                $tag = (object)[
                    "url" => get_the_permalink($link->ID),
                    "link_text" => $link->post_title,
                ];
                $tags[] = $tag;
            }

            $this->card_grid->components[] = new Component([
                "url" => get_the_permalink($degree["page"]->ID),
                "title" => ifset($degree["degrees_title_override"], $degree["page"]->post_title),
                "text" => ifset($degree["description"]),
                "tags" => $tags,
            ]);
        }
    }


    /* --------------------------------------------------------------- */
    public function ensembles_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // GET POSTS
        $args = [
            'post_type' => 'ensembles',
            'posts_per_page' => 100,
            'order' => 'ASC',
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'category',
                    'terms' => $this->category
                ],
                [
                    'taxonomy' => 'ensembles_category',
                    'terms' => $this->ensembles_category
                ],
            ],
        ];
        $posts = $this->type === 'category' ? get_posts($args) : $this->ensembles;

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $hero = get_field("sections", $id)[0]["image"]["sizes"]["medium_large"];
                $alternative = get_field("alternative_thumb", $id)["sizes"]["medium_large"];
                if (get_field("options", $id) === 'alternative' && $alternative) {
                    $thumb = $alternative;
                } else if ($hero) {
                    $thumb = $hero;
                } else {
                    $thumb = fpo()->hero_image;
                }
                $this->card_grid->components[] = new Component([
                    "url" => get_the_permalink($id),
                    // Get post hero image
                    "image_src" => $thumb,
                    "cta" => new Component([
                        "url" => get_the_permalink($id),
                        "link_text" => ifset($post->post_title, "Learn More"),
                    ]),
                ]);
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }


    /* --------------------------------------------------------------- */
    public function events_slider()
    {

        $args = [
            'post_type' => 'tribe_events',
            'numberposts' => -1,
            'meta_value' => get_date_from_gmt(date('Y-m-d H:i:s'), 'Y-m-d H:i:s'),
            'meta_compare' => '>',
            'order' => 'ASC',
            'orderby' => 'meta_value',
            'meta_key' => '_EventStartDate',
        ];

        if (!$this->featured_events) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'tribe_events_cat',
                    'terms' => $this->event_category,
                    'field' => 'id',
                ],
            ];
        } else {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'tribe_events_cat',
                    'terms' => 'student-recital',
                    'field' => 'slug',
                    'operator'  => 'NOT IN'
                ],
                [
                    'taxonomy' => 'category',
                    'terms' => $this->categories,
                    'field' => 'id',
                ],
            ];
        }

        $taxonomy_to_show = $this->featured_events ? 'category' : 'tribe_events_cat';
        $this->events = collect(get_posts($args))->map(function ($event) use ($taxonomy_to_show) {
            $categories = get_the_terms($event->ID, $taxonomy_to_show);
            $id = $event->ID;
            $primary_category_id = intval(get_post_meta($id, '_yoast_wpseo_primary_' . $taxonomy_to_show, true));
            $category = $categories
                ?
                (count($categories) > 1
                    &&
                    $primary_category_id ? collect($categories)->first(function ($cat) use ($primary_category_id) {
                        return $primary_category_id === $cat->term_id;
                    })
                    : $categories[0])
                : null;
            $start_date = get_field('_EventStartDate', $event->ID);
            $meta = array_map('reset', get_post_custom($id));

            return (object) [
                'id' => $id,
                'title' => $event->post_title,
                'category' => $category ? $category->name : null,
                'date' => $start_date ? (object) [
                    'month' => tribe_get_start_date($id, false, 'M'),
                    'day' => tribe_get_start_date($id, false, 'D'),
                    'date' => tribe_get_start_date($id, false, 'j'),
                ] : null,
                'image' => get_the_post_thumbnail_url($id),
                'link' => get_permalink($id),
                'featured' => isset($meta['_tribe_featured']) && $meta['_tribe_featured']
            ];
        });

        $this->events = $this->events->sort(function ($a, $b) {
            // strcmp($b->featured, $a->featured);
            if ($a->featured == $b->featured) {
                return 0;
            }

            return ($a->featured > $b->featured) ? -1 : 1;
        });

        $this->events_by_categories_min = 3;

        if ($this->featured_events && count($this->events) >= $this->events_by_categories_min) {
            $this->events_by_categories = [];
            $this->events_by_categories_collected = [];
            for ($i = 0; $i <= 2; $i++) {
                if (count($this->events_by_categories) < $this->events_by_categories_min) {
                    $this->events_by_categories($i);
                }
                //echo count($this->events_by_categories);
            }

            //print_r($this->events_by_categories);

            $this->events = collect(array_column(array_values($this->events_by_categories), 'event'));
        }
        //print_r($this->events);


        $this->add_class($this->bg_color);
    }

    function events_by_categories($cycle)
    {
        $this->events->map(function ($event) use ($cycle) {
            $cat_has_event = isset($this->events_by_categories[$event->category . $cycle]);
            $event_collected = in_array($event->id, $this->events_by_categories_collected);
            if (!$cat_has_event && !$event_collected) {
                $this->events_by_categories[$event->category . $cycle]['event'] = $event;
                $this->events_by_categories_collected[] = $event->id;
            }
        });
    }


    /* --------------------------------------------------------------- */
    public function events_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
            "cta" => new Component([
                "classes" => ["circle-cta", "flip", "yellow"],
                // Current placeholder for header cta buttons
                "link_text" => ifset(get_field("events_grid_link", "option")["title"] ?? false, "Learn More"),
                "url" => ifset(get_field("events_grid_link", "option")["url"] ?? false),
            ]),
        ]);

        // GET POSTS
        $args = [
            'post_type' => 'tribe_events',
            "numberposts" => $this->number,
            'meta_value' => get_date_from_gmt(date('Y-m-d H:i:s'), 'Y-m-d H:i:s'),
            'meta_compare' => '>',
            'order' => 'ASC',
            'orderby' => 'meta_value',
            'meta_key' => '_EventStartDate',
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'tribe_events_cat',
                    'terms' => $this->event_category,
                    'field' => 'id',
                ],
                [
                    'taxonomy' => 'category',
                    'terms' => $this->category,
                    'field' => 'id',
                ],
                [
                    'taxonomy' => 'post_tag',
                    'terms' => $this->event_tags,
                    'field' => 'id',
                ],
            ],
        ];
        $posts = $this->type === 'category' ? get_posts($args) : $this->events;

        //var_dump(get_date_from_gmt( date('Y-m-d H:i:s'), 'Y-m-d H:i:s' ));
        //var_dump(get_post_meta( $posts[0]->ID, '_EventStartDate', true ));
        //die;

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $this->card_grid->components[] = new Component([
                    "url" => get_the_permalink($id),
                    "image_src" => ifset(get_the_post_thumbnail_url($id, "medium_large") ?? false, fpo()->image),
                    "title" => $post->post_title,
                    // trimcontent, same thing as getting the excerpt, 30 (Number of lines)
                    "text" => get_the_excerpt($post->ID) ? get_the_excerpt($post->ID) : \App\trimContent($post->post_content),
                    "event_box" => [
                        "cost" => get_field("attendance", $id) == "paid" ? "$" . get_field("event_price", $id) : "Free",
                        "start_date_month" => tribe_get_start_date($id, false, 'M'),
                        "start_date_day" => tribe_get_start_date($id, false, 'D'),
                        "start_date" => tribe_get_start_date($id, false, 'j'),
                        "start_time" => tribe_get_start_date($id, false, 'g:ia'),
                    ],
                    "subtitle" => join(", ", array_column((wp_get_post_terms($id, "tribe_events_cat") ?: []), "slug")),
                    "caption" => tribe_get_venue($id),
                ]);
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }


    /* --------------------------------------------------------------- */
    public function facilities_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // GET POSTS
        $args = [
            'post_type' => 'facilities',
            'tax_query' => [
                [
                    'taxonomy' => 'category',
                    'terms' => $this->category
                ],
            ],
        ];
        $posts = $this->type === 'category' ? get_posts($args) : $this->facilities;

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $hero = get_field("sections", $id)[0]["image"]["sizes"]["medium_large"];
                $alternative = get_field("alternative_thumb", $id)["sizes"]["medium_large"];
                if (get_field("options", $id) === 'alternative' && $alternative) {
                    $thumb = $alternative;
                } else if ($hero) {
                    $thumb = $hero;
                } else {
                    $thumb = fpo()->hero_image;
                }
                $this->card_grid->components[] = new Component([
                    "url" => get_the_permalink($id),
                    "image_src" => $thumb,
                    // Top blue bar
                    "header_cta" => new Component([
                        "url" => get_the_permalink($id),
                        "classes" => ["top-cta"],
                        "link_text" => ifset($post->post_title ?? false, "Learn More"),
                    ]),
                ]);
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }


    /* --------------------------------------------------------------- */
    public function funds_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // GET POSTS
        $args = [
            'post_type' => 'funds',
            'tax_query' => [
                [
                    'taxonomy' => 'category',
                    'terms' => $this->category
                ],
            ],
        ];
        $posts = $this->type === 'category' ? get_posts($args) : $this->funds;

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $link = get_field("link", $id);
                $image = get_field("image", $id);
                $this->card_grid->components[] = new Component([
                    "url" => ifset($link["url"] ?? false),
                    "target" => ifset($link["target"] ?? false),
                    "image_src" => ifset($image["url"] ?? false, fpo()->image),
                    "header_title" => ifset($post->post_title ?? false),
                    "cta" => new Component([
                        "url" => ifset($link["url"] ?? false),
                        "target" => ifset($link["target"] ?? false),
                        "link_text" => ifset($link["title"] ?? false, "Give Now"),
                    ]),
                ]);
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }


    /* --------------------------------------------------------------- */
    public function info_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // BLOCK CLASSES AND STYLES
        // columns setup: cards-2, cards-3
        $this->classes[] = $this->style;

        if ($this->number_grid) {
            $this->classes[] = "number-grid";
        }

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        foreach ($this->info_columns as $info) {
            $this->card_grid->components[] = new Component([
                "header_title" => $info["header_title"],
                "title" => $info["title"],
                "text" => $info["content"],
            ]);
        }
    }


    /* --------------------------------------------------------------- */
    public function instruments_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // GET POSTS
        $args = [
            'post_type' => 'instruments',
            'tax_query' => [
                [
                    'taxonomy' => 'category',
                    'terms' => $this->category
                ],
            ],
        ];
        $posts = $this->type === 'category' ? get_posts($args) : $this->instruments;

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $this->card_grid->components[] = new Component([
                    "url" => get_the_permalink($id),
                    "image_src" => ifset(get_field("sections", $id)[0]["image"]["sizes"]["medium_large"] ?? false, fpo()->hero_image),
                    "caption" => $post->post_title,
                ]);
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }


    /* --------------------------------------------------------------- */
    public function image_links_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // BLOCK CLASSES AND STYLES
        // columns setup: cards-2, cards-3
        $this->add_class($this->style);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        foreach ($this->links as $link) {
            $this->card_grid->components[] = new Component([
                "url" => ifset($link["link"]["url"] ?? false),
                "target" =>  ifset($link["link"]["target"] ?? false),
                "image_src" => ifset($link["image"]["sizes"]["medium_large"] ?? false, fpo()->image),
                "title" => ifset($link["title"] ?? false, "Learn More"),
                "text" => ifset($link["text"] ?? false),
                "cta" => new Component([
                    "url" => ifset($link["link"]["url"] ?? false),
                    "target" =>  ifset($link["link"]["target"] ?? false),
                    "classes" => ["circle-cta", "blue"],
                    "link_text" => ifset($link["link"]["title"] ?? false, "Learn More"),
                ]),
            ]);
        }
    }


    /* --------------------------------------------------------------- */
    public function links_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // BLOCK CLASSES AND STYLES
        // columns setup: cards-2, cards-3
        $this->add_class($this->style);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        foreach ($this->links as $link) {
            $this->card_grid->components[] = new Component([
                "url" => ifset($link["link"]["url"] ?? false),
                "target" => ifset($link["link"]["target"] ?? false),
                "title" => ifset($link["link"]["title"] ?? false),
                "text" => ifset($link["text"] ?? false),
                "cta" => new Component([
                    "url" => ifset($link["link"]["url"] ?? false),
                    "target" => ifset($link["link"]["target"] ?? false),
                    "classes" => ["circle-cta", "grey"],
                ]),
            ]);
        }
    }


    /* --------------------------------------------------------------- */
    public function news_grid()
    {

        // SECTION HEADER COMPONENT
        if (get_the_ID() == 481) {
            $this->section_header = new Component([
                "header" => $this->header,
            ]);
            $this->classes[] = "cards-margin-bottom-6rem";
        } else {
            $this->section_header = new Component([
                "header" => $this->header,
                "cta" => new Component([
                    "classes" => ["circle-cta", "yellow", "flip"],
                    "url" => ifset($this->link["url"] ?? false, get_field("news_grid_link", "option")["url"]),
                    "link_text" => ifset($this->link["title"] ?? false, get_field("news_grid_link", "option")["title"]),
                ]),
            ]);
        }
        // GET POSTS

        // Test if on News Page and is Latest News module
        $newsPageLatest = $this->type === "latest" && get_the_ID() === 481;

        $stickyCount = count(get_option('sticky_posts'));

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // hides tags that have boolean set to true to be hidden from main blog
        $hiddenTagsArgs = [
            'meta_key' => 'hide_from_main_blog',
            'meta_value' => '1',
            'meta_compare' => '=',
            'fields' => 'ids',
        ];
        $this->hiddenTags = get_terms($hiddenTagsArgs);

        if ($newsPageLatest) {

            $this->classes[] = "news-page-latest";
            $args =
                [
                    'posts_per_page' => $this->number - $stickyCount,
                    'paged' => $paged,
                    'post_type' => 'post',
                    'max_num_pages' => 100,
                    'tag__not_in' => $this->hiddenTags,
                ];
        } elseif ($this->type === 'latest') {
            $args =
                [
                    'posts_per_page' => $this->number - $stickyCount,
                    'tag__not_in' => $this->hiddenTags,
                ];
        } elseif ($this->type === 'category') {
            $args =
                [
                    'posts_per_page' => $this->number,
                    'tax_query' => [
                        [
                            'taxonomy' => 'category',
                            'terms' => $this->category
                        ],
                    ],
                    'tag__not_in' => $this->hiddenTags,
                ];
        } elseif ($this->type === 'tag') {
            $args =
                [
                    'posts_per_page' => $this->number,
                    'tax_query' => [
                        [
                            'taxonomy' => 'post_tag',
                            'terms' => $this->tag
                        ],
                    ],
                ];
        };

        if (!$newsPageLatest) {
            if ($this->type === "category" || $this->type === "tag" || $this->type === "latest") {
                $query = new WP_Query($args);
                $posts = $query->posts;
                $this->args = $args;
                $this->paged = $paged;
            } else {
                $posts = $this->news;
            }
        }

        if (!$this->news_hero && $this->type === "selection") {
            if (count($this->news) === 2) {
                $this->classes[] = "news-featured";
            }
        }

        // News Page - Exclude Hero and Featured posts from Latest posts
        global $excludeNewsPosts;
        if (!$newsPageLatest) {
            if (isset($posts)) {
                foreach ($posts as $post) {
                    $excludeNewsPosts[] = $post->ID;
                }
            }
        }

        if ($newsPageLatest) {
            $args['post__not_in'] = $excludeNewsPosts;
            $query = new WP_Query($args);
            $posts = $query->posts;
            $this->args = $args;
            $this->paged = $paged;
        }
        //var_dump($stickyCount);
        // BLOCK CLASSES AND STYLES
        // Grid layout changes based on the number posts
        if ($newsPageLatest) {
            $this->add_classes(["grid", "cards-3"]);
        } else {
            if ($this->news_hero === true) {
                $this->add_classes(["grid", "news-hero"]);
            } elseif ($posts && sizeof($posts) == 1) {
                $this->add_classes(["grid", "cards-1"]);
            } elseif ($posts && sizeof($posts) == 2) {
                $this->add_classes(["grid", "cards-2"]);
            } else {
                $this->add_classes(["grid", "cards-3"]);
            }
        }



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
            $this->card_grid->type = $this->type;
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }


    /* --------------------------------------------------------------- */
    public function people_grid()
    {

        // GET POSTS
        $args = [
            'post_type' => "people",
            'posts_per_page' => -1,
            'meta_key'          => 'last_name',
            'orderby'           => 'meta_value',
            'order' => 'ASC',
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'category',
                    'terms' => $this->category
                ],
                [
                    'taxonomy' => 'people_category',
                    'terms' => $this->people_category
                ],
                [
                    'taxonomy' => 'area_ensemble',
                    'terms' => $this->areas_of_study
                ],
                [
                    'taxonomy' => 'degree_program',
                    'terms' => $this->degree_program
                ],
                [
                    'taxonomy' => 'department',
                    'terms' => $this->department
                ],
            ],
        ];
        $posts = ($this->type === 'category' ? get_posts($args) : $this->type === 'manual') ? $this->manual_people : $this->people;

        // BLOCK CLASSES AND STYLES
        // Grid layout changes based on the number posts
        if ($this->style == "grid") {
            if (sizeof($posts) == 1) {
                $this->add_classes(["grid", "feature"]);
            } elseif (sizeof($posts) == 2) {
                $this->add_classes(["grid", "cards-2"]);
            } elseif (sizeof($posts) == 3) {
                $this->add_classes(["grid", "cards-3"]);
            } else {
                $this->add_classes(["grid", "cards-3", "cards-lots"]);
            }
        } elseif ($this->style == "team-list") {
            $this->add_classes(["team", "feature", "team-list"]);
        } elseif ($this->style == "team-slider") {
            $this->add_classes(["team", "feature", "slider"]);
        }

        // this sets the parent department, grabs the term data for use below
        $department = isset($this->link_to_department[0]) ? get_term_by('id', $this->link_to_department[0], 'department') : '';
        $slider_cta_text = !empty($department) ? "See All " . $department->name . " Faculty" :  "See All Faculty";
        $slider_cta_text = ($this->cta_override) ? $this->cta_override :  $slider_cta_text;
        $slider_cta_url = !empty($department) ? "/about/faculty/?department=" . $department->slug : "/about/faculty/";
        $slider_cta_url = ($this->link_override) ? $this->link_override :  $slider_cta_url;

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
            "is_slider" => $this->has_class("slider") ?? false,
            "slider_cta_text" => $slider_cta_text,
            "slider_cta_url" => $slider_cta_url,
        ]);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            if ($this->type === 'manual') {
                foreach ($posts as $person) {
                    // Content is different for feature card vs grid card
                    $card = new Component([
                        "url" => $person['url'],
                        "image_src" => ifset($person['image']["sizes"]["medium_large"] ?? false, fpo()->image),
                        "title" => $person['title'],
                        "text" =>  $person['text'],
                    ]);
                    
                    $this->card_grid->components[] = $card;
                }
            } else {
                foreach ($posts as $post) {
                    $id = $post->ID;
                    // Content is different for feature card vs grid card
                    $card = new Component([
                        "url" => get_the_permalink($post->ID),
                        "image_src" => ifset(get_field("portrait", $id)["sizes"]["medium_large"] ?? false, fpo()->image),
                        "title" => $post->post_title,
                    ]);
                    if ($this->has_class("feature")) {
                        // Full width feature card
                        $card->add_properties([
                            "cta" => new Component([
                                "url" => get_the_permalink($post->ID),
                                "classes" => ["circle-cta", "blue"],
                                "link_text" => "Learn More"
                            ]),
                            "subtitle" => get_field("title", $id),
                            "description" => get_field("description", $id),
                            "text" =>  \App\trimContent(get_field("bio", $id), 60),
                            "external_links" => ifset(get_field("external_links", $id) ?? false),
                        ]);
                    } else {
                        // Grid card based on post size
                        $card->add_properties([
                            "text" =>  get_field("title", $id),
                        ]);
                    }
                    $this->card_grid->components[] = $card;
                }
            }

        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }


    /* --------------------------------------------------------------- */
    public function resources_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // BLOCK CLASSES AND STYLES
        $this->add_class($this->collections_grid ? "collection" : null);

        // GET POSTS
        $args = [
            'post_type' => 'resources',
            'tax_query' => [
                [
                    'taxonomy' => 'category',
                    'terms' => $this->category
                ],
            ],
        ];
        $posts = $this->type === 'category' ? get_posts($args) : $this->resources;

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $hero = get_field("sections", $id)[0]["image"]["sizes"]["medium_large"];
                $alternative = get_field("alternative_thumb", $id)["sizes"]["medium_large"];
                if (get_field("options", $id) === 'alternative' && $alternative) {
                    $thumb = $alternative;
                } else if ($hero) {
                    $thumb = $hero;
                } else {
                    $thumb = fpo()->hero_image;
                }
                // Shared properties for both resource and collection card
                $card = new Component([
                    "url" => get_the_permalink($id),
                    "image_src" => $thumb,
                ]);
                if ($this->has_class("collection")) {
                    // Collection Card
                    $card->add_properties([
                        "image_caption" => $post->post_title,
                        "cta" => new Component([
                            "url" => get_the_permalink($id),
                            "classes" => ["circle-cta", "blue"],
                        ]),
                    ]);
                } else {
                    // Resource card
                    $card->add_properties([
                        "header_cta" => new Component([
                            "url" => get_the_permalink($id),
                            "classes" => ["top-cta"],
                            "link_text" => ifset($post->post_title ?? false, "Learn More"),
                        ]),
                    ]);
                }
                $this->card_grid->components[] = $card;
            }
        } else {
            // HIDE WRAPPER
            // If no posts are found
            $this->show_wrapper = false;
        }
    }


    /* --------------------------------------------------------------- */
    public function statistics_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        foreach ($this->statistics as $stat) {
            $this->card_grid->components[] = new Component([
                "text" => $stat["unit"],
                "stat" => $stat["value"],
            ]);
        }
    }


    /* --------------------------------------------------------------- */
    public function requirements_grid()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // CARD GRID COMPONENT
        $this->card_grid = new Component();
        foreach ($this->requirements as $requirement) {

            $tags = [];
            foreach ($requirement["links"] as $link) {
                $tag = (object)[
                    "url" => ifset($link["link"]["url"] ?? false),
                    "target" => ifset($link["link"]["target"] ?? false),
                    "link_text" => str_replace($requirement["title"] . " ", "", ifset($link["link"]["title"] ?? false, "")),
                ];
                $tags[] = $tag;
            }

            $this->card_grid->components[] = new Component([
                "url" => ifset($requirement["link"]["url"] ?? false),
                "title" => $requirement["title"],
                "text" => $requirement["text"],
                "tags" => $tags,
            ]);
        }
    }


    /* --------------------------------------------------------------- */
    // FAQs accordion
    public function faqs_accordion()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // BLOCK CLASSES AND STYLES
        $this->add_class($this->background);

        // ACCORDION COMPONENT
        $index = 0;
        $this->accordion_group = new Component();
        foreach ($this->faqs as $faq) {
            $this->accordion_group->components[] = new Component([
                "trigger_id" => "trigger-block-" . $this->position . "-index-" . $index,
                "collapse_id" => "collapse-block-" . $this->position . "-index-" . $index,
                "index_first" => ($index == 0),
                "title" => $faq["question"],
                "text" => $faq["answer"],
            ]);
            $index++;
        }
    }


    /* --------------------------------------------------------------- */
    // Courses accordion
    public function courses_accordion()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
            "subheader" => $this->subheader,
            "cta" => new Component([
                "classes" => ["circle-cta", "yellow", "flip"],
                "url" => ifset($this->link["url"] ?? false, get_field("courses_accordion_link", "option")["url"]),
                "link_text" => ifset($this->link["title"] ?? false, get_field("courses_accordion_link", "option")["title"]),
            ]),
        ]);

        // BLOCK CLASSES AND STYLES
        $this->add_class("white-bg");

        // GET POSTS
        $args = [
            'post_type' => "courses",
            'order' => 'ASC',
            'orderby' => 'meta_value',
            'posts_per_page' => -1,
            'meta_key' => 'course_id',
            'tax_query' => [
                "relation" => "AND",
                [
                    'taxonomy' => 'course_type',
                    'terms' => $this->course_type
                ],
                [
                    'taxonomy' => 'course_category',
                    'terms' => $this->course_category
                ],
            ],
        ];
        $posts = $this->type === 'category' ? get_posts($args) : $this->courses;

        // ACCORDION COMPONENT
        $index = 0;
        $this->accordion_group = new Component();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->ID;
                $this->accordion_group->components[] = new Component([
                    "trigger_id" => "trigger-block-" . $this->position . "-index-" . $index,
                    "collapse_id" => "collapse-block-" . $this->position . "-index-" . $index,
                    "index_first" => ($index == 0),
                    "title" => $post->post_title,
                    "text" => $post->post_content,
                    "notes" => get_field("notes", $id),
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
    // Video
    public function video()
    {

        // BLOCK CLASSES AND STYLES
        $this->add_class($this->background);

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // PROPERTIES
        // Format oembed url
        $this->url = get_sub_field("video", false, false);

        // HIDE WRAPPER
        // If url not found
        $this->show_wrapper = !empty($this->url);
    }


    /* --------------------------------------------------------------- */
    public function image_masonry()
    {
        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        $this->url = [];
        while (have_rows('images')) {
            the_row();
            $this->url[] = get_sub_field('video', false, false);
        }
    }


    /* --------------------------------------------------------------- */
    public function virtual_tour()
    {

        // BLOCK CLASSES AND STYLES
        $this->add_class("background-image");
        $this->styles["background-image"] = "url(" . get_field("virtual_tour_image", "option")["url"] . ")";

        // HIDE WRAPPER
        // If url not found
        $this->show_wrapper = !empty($this->url);
    }


    /* --------------------------------------------------------------- */
    public function application_journey()
    {

        // SECTION HEADER COMPONENT
        $this->section_header = new Component([
            "header" => $this->header,
        ]);

        // CARD GRID COMPONENT
        $this->steps = [];
        foreach ($this->application_journey as $step) {
            $this->steps[] = (object)[
                "title" => $step["title"],
                "text" => $step["text"],
                "icon_src" => $step["application_journey_icon"],
                "cta" => new Component([
                    "classes" => ["circle-cta", "yellow", "small"],
                    "url" => ifset($step["link"]["url"] ?? false),
                    "target" => ifset($step["link"]["target"] ?? false),
                    "link_text" => ifset($step["link"]["title"] ?? false, "Learn More"),
                ]),
            ];
        }
    }


    /* --------------------------------------------------------------- */
    // public function custom_code() {

    // }
}
