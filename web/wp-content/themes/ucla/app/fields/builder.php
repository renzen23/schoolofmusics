<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$w50 = array(
    'width' => '50%'
);
$w33 = array(
    'width' => '33.333%'
);
$w66 = array(
    'width' => '66.666%'
);


/**
 * Background
 */
$fields['background'] = new FieldsBuilder('background');
$fields['background']
    ->addRadio('background_style')
    ->addChoices(['background_image' => 'Background Image'], ['background_color' => 'Background Color'])
    ->addRadio('background_color')
    ->addChoices(['white-bg' => 'White'], ['yellow-bg' => 'Yellow'], ['blue-bg' => 'Blue'])
    ->conditional('background_style', '==', 'background_color')
    ->addImage('background_image')
    ->conditional('background_style', '==', 'background_image');

/**
 * Image Mask
 */
$fields['image_mask'] = new FieldsBuilder('image_mask');
$fields['image_mask']
    ->addImage('image')
    ->addRadio('mask')
    ->addChoices(['maskA' => 'Mask A'], ['maskB' => 'Mask B'], ['maskC' => 'Mask C'], ['maskD' => 'Mask D'])
    ->addRadio('image_position')
    ->addChoices('Left', 'Right')
    ->addText('image_caption')
    ->addRadio('background_color')
    ->addChoices(['white-bg' => 'White'], ['yellow-bg' => 'Yellow'], ['blue-bg' => 'Blue']);

/**
 * External Links
 */
$fields['external_links'] = new FieldsBuilder('external_links');
$fields['external_links']
    ->addRepeater('external_links', ['button_label' => 'Add Link', "min" => 1, "layout" => "block"])
    ->addText('url', ['label' => 'URL', "placeholder" => "https://", "wrapper" => $w50])
    ->addSelect('external_link_icon', ["wrapper" =>  $w50]);

/**
 * Buttons
 */
$fields['buttons'] = new FieldsBuilder('buttons');
$fields['buttons']
    ->addRepeater('buttons', ['button_label' => 'Add Button', "layout" => "block", "min" => 1])
    ->addLink('link', ["wrapper" => $w50])
    ->addRadio('color', ["wrapper" => $w50, 'layout' => 'horizontal'])
    ->addChoices(['none' => 'None'], ['blue' => 'Blue'], ['yellow' => 'Yellow'])
    ->endRepeater();

/**
 * Events Listings
 */
$fields['events_listings'] = new FieldsBuilder('events_listings');
$fields['events_listings']
    ->addText('title');

/**
 * Ensembles Listings
 */
$fields['ensembles_listings'] = new FieldsBuilder('ensembles_listings');
$fields['ensembles_listings']
    ->addText('title');

/**
 * Faculty Directory
 */
$fields['faculty_listings'] = new FieldsBuilder('faculty_listings');
$fields['faculty_listings']
    ->addText('title');

/**
 * Research Directory
 */
$fields['research_listings'] = new FieldsBuilder('research_listings');
$fields['research_listings']
    ->addText('title');

/**
 * Global Settings
 */
$fields['global_settings'] = new FieldsBuilder('global_settings');
$fields['global_settings']

    ->addTab('Header/Footer', ["placement" => "left"])
    ->addRadio("header_button", ["layout" => "horizontal"])
    ->addChoices(["hide" => "Hide"], ["show" => "Show"])
    ->addLink("header_button_link", ['instructions' => ''])
    ->addImage('logo', ["wrapper" => $w50])
    ->addImage('logo_inverse', ["wrapper" => $w50])
    ->addImage('footer_logo', ["wrapper" => $w50])
    ->addTextArea('address', ['new_lines' => 'br'])
    ->addFields($fields['external_links'])
    ->addText('copyright', [
        'instructions' => 'Copyright symbol and Year automatically prepended', 'media_upload' => 0
    ])

    ->addTab('General', ["placement" => "left"])
    ->addLink("news_grid_link", ['instructions' => 'Default News Grid link (if not set in module)'])
    ->addLink("courses_accordion_link", ['instructions' => 'Default Courses Accordion link (if not set in module)'])
    ->addText("degrees_header_text", ["default_value" => "Explore Other Degrees"])

    ->addTab('Events', ['placement' => 'left'])
    ->addLink("events_grid_link")
    ->addText('events_subscription_link')
    ->addText("events_page_header_series_text", ["default_value" => "Other Events in This Series"])
    ->addText("events_page_header_not_series_text", ["default_value" => "Explore Other Events"])
    ->addRepeater('rsvp_info_defaults', ['label' => 'RSVP Info Defaults', 'layout' => 'block', 'min' => 1, 'instructions' => 'Default entries for RSVP dropdown (can be overwritten in event page)'])
    ->addText('rsvp_info_select_title', ['label' => 'RSVP Info Select Title'])
    ->addWysiwyg('rsvp_info_default', ['label' => 'RSVP Info Default', 'media_upload' => 0])
    ->endRepeater()
    ->addRepeater('ticket_info_defaults', ['layout' => 'block', 'min' => 1, 'instructions' => 'Default entries for Tickets dropdown (can be overwritten in event page)'])
    ->addText('ticket_info_select_title')
    ->addWysiwyg('ticket_info_default', ['media_upload' => 0])
    ->endRepeater()
    ->addWysiwyg('left_column_default', ['media_upload' => 0])
    ->addWysiwyg('right_column_default', ['media_upload' => 0])

    ->addTab('Degrees', ["placement" => "left"])
    ->addRepeater('degrees', ['button_label' => 'Add Degree Category', "layout" => "block", "min" => 1])
    ->addPostObject('page', [
        'post_type' => 'page',
    ])
    ->addText("degrees_title_override")
    ->addTextArea("description")
    ->addRelationship('links', [
        'post_type' => 'page',
        'filters' => array('search', 'taxonomy')
    ])
    ->endRepeater()

    ->addTab('Depts & Programs', ["placement" => "left"])
    ->addRepeater('departments', ['button_label' => 'Add Department', "layout" => "block", "min" => 1])
    ->addPostObject('page', [
        'post_type' => 'page',
    ])
    ->addTextArea('description', ['new_lines' => 'br'])
    ->endRepeater()
    ->addRepeater('programs', ['button_label' => 'Add Program', "layout" => "block", "min" => 1])
    ->addPostObject('page', [
        'post_type' => 'page',
    ])
    ->addTextArea('description', ['new_lines' => 'br'])
    ->endRepeater()

    ->addTab('External Link Icons', ["placement" => "left"])
    ->addRepeater('external_link_icons', ["layout" => "block", "min" => 1])
    ->addText('name', ["wrapper" => $w50])
    ->addText('icon', ['instructions' => 'FontAwesome tag, e.g. "facebook-f"', "wrapper" => $w50])
    ->endRepeater()

    ->addTab('Application Journey Icons', ["placement" => "left"])
    ->addRepeater('application_journey_icons', ["layout" => "block", "min" => 1])
    ->addText('name', ["wrapper" => $w50])
    ->addImage('icon', ["wrapper" => $w50])
    ->endRepeater()

    ->addTab('Image Masks', ["placement" => "left"])
    ->addGroup("a", ["layout" => "block", "min" => 1])
    ->addImage("left_svg", ["wrapper" => $w50, "instructions" => "Pointing right. 1340x640"])
    ->addImage("right_svg", ["wrapper" => $w50, "instructions" => "Pointing left. 1340x640"])
    ->endGroup()
    ->addGroup("b", ["layout" => "block", "min" => 1])
    ->addImage("left_svg", ["wrapper" => $w50, "instructions" => "Pointing right. 1340x640"])
    ->addImage("right_svg", ["wrapper" => $w50, "instructions" => "Pointing left. 1340x640"])
    ->endGroup()
    ->addGroup("c", ["layout" => "block", "min" => 1])
    ->addImage("left_svg", ["wrapper" => $w50, "instructions" => "Pointing right. 1340x640"])
    ->addImage("right_svg", ["wrapper" => $w50, "instructions" => "Pointing left. 1340x640"])
    ->endGroup()
    ->addGroup("d", ["layout" => "block", "min" => 1])
    ->addImage("left_svg", ["wrapper" => $w50, "instructions" => "Pointing right. 1340x640"])
    ->addImage("right_svg", ["wrapper" => $w50, "instructions" => "Pointing left. 1340x640"])
    ->endGroup()

    ->addTab('Hero Image Masks', ["placement" => "left"])
    ->addGroup("original", ["layout" => "block", "min" => 1])
    ->addImage("image", ["instructions" => "1550x640px"])
    ->addImage("mobile_svg", ["wrapper" => $w50, "instructions" => "1340x640", "instructions" => "SVG changes shape on mobile."])
    ->addImage("desktop_svg", ["wrapper" => $w50, "instructions" => "1340x640", "instructions" => "SVG shape on desktop."])
    ->addText("mobile_offset", ["wrapper" => $w50, "default_value" => "0", "instructions" => "Image slides over slightly on mobile."])
    ->addText("desktop_offset", ["wrapper" => $w50, "default_value" => "0", "instructions" => "Image position on desktop."])
    ->endGroup()
    ->addGroup("ethnomusicology", ["layout" => "block", "min" => 1])
    ->addImage("image", ["instructions" => "1550x640px"])
    ->addImage("mobile_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addImage("desktop_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addText("mobile_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->addText("desktop_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->endGroup()
    ->addGroup("musicology", ["layout" => "block", "min" => 1])
    ->addImage("image", ["instructions" => "1550x640px"])
    ->addImage("mobile_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addImage("desktop_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addText("mobile_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->addText("desktop_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->endGroup()
    ->addGroup("music", ["layout" => "block", "min" => 1])
    ->addImage("image", ["instructions" => "1550x640px"])
    ->addImage("mobile_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addImage("desktop_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addText("mobile_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->addText("desktop_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->endGroup()
    ->addGroup("music_industry", ["layout" => "block", "min" => 1])
    ->addImage("image", ["instructions" => "1550x640px"])
    ->addImage("mobile_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addImage("desktop_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addText("mobile_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->addText("desktop_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->endGroup()
    ->addGroup("global_jazz", ["layout" => "block", "min" => 1])
    ->addImage("image", ["instructions" => "1550x640px"])
    ->addImage("mobile_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addImage("desktop_svg", ["wrapper" => $w50, "instructions" => "1340x640"])
    ->addText("mobile_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->addText("desktop_offset", ["wrapper" => $w50, "default_value" => "0"])
    ->endGroup()


    ->addTab('Other', ["placement" => "left"])
    ->addImage("hero_fpo",  ["wrapper" => $w50, "instructions" => "1920x650", "label" => "Hero FPO"])
    ->addImage("image_fpo",  ["wrapper" => $w50, "instructions" => "1200x820", "label" => "Image FPO"])
    ->addImage("hero_image_mask_fpo", ["wrapper" => $w50, "instructions" => "1550x640", "label" => "Hero Image Mask FPO"])
    ->addImage("image_mask_fpo",  ["wrapper" => $w50, "instructions" => "1340x640", "label" => "Image Mask FPO"])
    ->addImage("virtual_tour_image")

    ->addTab('Ribbons', ["placement" => "left"])
    ->addRepeater('ribbons', ['layout' => 'row'])
    ->addRelationship("display_on", ["return_format" => "id", "filters" => ["post_type", "search"]])
    ->addRadio("display", ["layout" => "vertical"])
    ->addChoices(["show" => "Show"], ["hide" => "Hide"])
    ->addText("title")
    ->addTextArea("description")
    ->addLink("ribbon_link")


    ->setLocation('options_page', '==', 'global-options');


/**
 * News Post fields
 */
$fields['news'] = new FieldsBuilder('news');
$fields['news']
    ->addRelationship('authors', [
        'post_type' => 'people',
        'filters' => array('search', 'taxonomy')
    ])

    ->setLocation('post_type', '==', 'post');

/**
 * Event post fields
 */
$fields['event'] = new FieldsBuilder('event');
$fields['event']

    ->addRadio("attendance", ["layout" => "horizontal"])
    ->addChoices(["free" => "Free"], ["paid" => "Paid"])
    ->addText("event_price", ["prepend" => "$"])
    ->conditional("attendance", "==", "paid")
    ->addCheckbox("social_share_links", ["layout" => "horizontal"])
    ->addChoices(
        ["facebook" => "Facebook"],
        ["twitter" => "Twitter"],
        ["linkedin" => "LinkedIn"]
    )
    ->addFields($fields['external_links'])
    ->addTrueFalse("show_call_to_action", ['ui' => 1, "instructions" => "Show call to action box with rsvp/donate/ticket information?"])
    ->addGroup("call_to_action")
    ->conditional("show_call_to_action", "==", 1)
    ->addSelect("rsvp", ['label' => 'RSVP', 'return_format' => 'array', 'wrapper' => $w50, 'instructions' => 'Select from options that are set in Global Settings'])
    ->addTrueFalse("rsvp_override", ['label' => 'RSVP Override', 'wrapper' => $w50, 'ui' => 1, 'instructions' => 'Ignore RSVP select dropdown and enter custom text'])
    ->addWysiwyg("content", ['label' => 'RSVP Info', 'media_upload' => 0])
    ->conditional('rsvp_override', '==', 1)
    ->addRepeater("links", ['button_label' => 'Add Link', "min" => 1, "layout" => "block"])
    ->addLink("link")
    ->endRepeater()
    ->endGroup()
    ->addTrueFalse("show_video", ['ui' => 1, "instructions" => "Show video or livestream?"])
    ->addGroup("video")
    ->conditional("show_video", "==", 1)
    ->addText('header', ['instructions' => 'e.g. Live Stream Available'])
    ->addSelect('source', ['layout' => 'horizontal'])
    ->addChoices(['other' => 'Vimeo, YouTube, or other'], ['livestream' => 'Livestream.com'])
    ->addOEmbed('video')
    ->conditional('source', '==', "other")
    ->addText('livestream', ['label' => 'Livestream.com URL', 'instructions' => 'e.g. https://livestream.com/accounts/12345678/events/1234567'])
    ->conditional('source', '==', "livestream")
    ->endGroup()
    ->addTrueFalse("show_info_grid", ['ui' => 1, "instructions" => "Show additional information about this event?"])
    ->addGroup("info_grid")
    ->conditional("show_info_grid", "==", 1)
    ->addText("header", ["instructions" => "e.g., Attending this Program?"])
    ->addSelect("tickets", ['wrapper' => $w50, 'return_format' => 'array', 'instructions' => 'Select from options that are set in Global Settings'])
    ->addTrueFalse("ticket_override", ['wrapper' => $w50, 'ui' => 1, 'instructions' => 'Ignore Tickets select dropdown and enter custom text'])
    ->addWysiwyg("tickets_info", ['media_upload' => 0])
    ->conditional('ticket_override', '==', 1)
    ->addWysiwyg("left_column_info", ['media_upload' => 0, 'wrapper' => $w50])
    ->addWysiwyg("right_column_info", ['media_upload' => 0, 'wrapper' => $w50])
    ->endGroup()
    ->addText('image_caption')
    ->addFile('preview_image', ['instructions' => 'Add an image to display in the thumbnail view. If not set, the featured image will be used.'])
    ->addTrueFalse('show_program', [
        'default_value' => 0,
        'ui' => 1,
    ])
    ->addGroup('program')
        ->addImage('image', [
            'return_format' => 'array', /* 'array' || 'id' || 'url' */
            'preview_size' => 'medium',
        ])
        ->addText('title')
        ->addWysiwyg('intro')
        ->addText('performers_title',['instructions' => 'Performers/Roster'])
        ->addRepeater('performers', ['button_label' => 'Add Performer', "layout" => "block", "min" => 1, "instructions" => ""])
            ->addImage('image')
            ->addText('name')
            ->addTextarea('intro', [
                'new_lines' => 'br',
                'rows' => '3'
            ])
            ->addText('bio_title',['instructions' => 'Defaults to See Bio'])
            ->addWysiwyg('bio')
        ->endRepeater()
        ->addWysiwyg('repertoire')
        ->addWysiwyg('about', ['label'=>'Donor Acknowledgement'])
        ->addWysiwyg('notes', ['label'=>'Program Notes'])
    ->endGroup()
    ->setLocation('post_type', '==', 'tribe_events');


/**
 * People Fields
 */
$fields['people'] = new FieldsBuilder('people');
$fields['people']
    ->addImage('portrait')
    ->addText('title')
    ->addText('last_name')
    ->addEmail('email')
    ->addWysiwyg('description', ['media_upload' => 0, 'instructions' => 'Optional'])
    ->addWysiwyg('bio', ['media_upload' => 0])
    ->addTextArea('quote', ['new_lines' => 'br'])
    ->addFields($fields['external_links'])
    ->addWysiwyg('video_header', ['instructions' => '
        <code>h1 strong</code>Bold<br>
        <code>h1</code>Normal<br>
        <code>h1 em</code>Light<br>
        ', 'media_upload' => 0])
    ->addOEmbed('video')

    ->setLocation('post_type', '==', 'people')
    ->setGroupConfig('hide_on_screen', [
        'the_content',
        'featured_image',
        'people_categories'
    ]);

/**
 * Courses Fields
 */
$fields['courses'] = new FieldsBuilder('courses');
$fields['courses']
    ->addTextArea('notes', ['new_lines' => 'br', "instructions" => "Smaller text on the side."])
    ->addText('course_id', ['label' => 'Course ID'])
    ->setLocation('post_type', '==', 'courses')
    ->setGroupConfig('hide_on_screen', [
        'featured_image'
    ]);

/**
 * Funds fields
 */
$fields['funds'] = new FieldsBuilder('funds');
$fields['funds']
    ->addLink('link', ['wrapper' => $w50])
    ->addImage('image', ['wrapper' => $w50])

    ->setLocation('post_type', '==', 'funds')
    ->setGroupConfig('hide_on_screen', [
        'the_content',
        'featured_image'
    ]);

/**
 * Press fields
 */
$fields['press'] = new FieldsBuilder('press');
$fields['press']
    ->addDatePicker('date', ["return_format" => "F j, Y", "display_format" => "F j, Y"])
    ->addText('publication', ['instructions' => 'e.g., The Los Angeles Times'])
    ->addLink('link')

    ->setLocation('post_type', '==', 'post')
    ->and('post_category', '==', 357);

/**
 * Publications fields
 */
$fields['publications'] = new FieldsBuilder('publications');
$fields['publications']
    ->addText('title')
    ->addText('year')
    ->addSelect('program')
    ->addChoices(['ethnomusicology' => 'Ethnomusicology'], ['musicology' => 'Musicology'], ['composition' => 'Composition'])
    ->addUrl('link')
    ->addWysiwyg('description')
    ->addRadio('author', ['layout' => 'horizontal'])
    ->addChoices(['alumni' => 'Alumni'], ['select' => 'Select Author'])
    ->addRelationship('authors', [
        'post_type' => 'people',
        'filters' => array('search', 'taxonomy')
    ])
    ->conditional('author', '==', "select")
    ->addText('alumni_author')
    ->conditional('author', '==', "alumni")

    ->setLocation('post_type', '==', 'publications')
    ->setGroupConfig('hide_on_screen', [
        'featured_image',
        'the_content'
    ]);

/**
 * Instruments fields
 */
$fields['instruments'] = new FieldsBuilder('instruments');
$fields['instruments']
    ->addRelationship('ensembles', [
        'post_type' => 'ensembles',
        'instructions' => 'Found in the following ensembles:'
    ])
    ->addRelationship('collections', [
        'post_type' => 'resources',
        'instructions' => 'Found in the following collections:'
    ])

    ->setLocation('post_type', '==', 'instruments')
    ->setGroupConfig('hide_on_screen', [
        'featured_image',
        'the_content'
    ]);

/**
 * Image Credit fields
 */
$fields['image_credit'] = new FieldsBuilder('image_credit');
$fields['image_credit']
    ->addText('author_name')
    ->addText('author_website')

    ->setLocation('attachment', '==', 'all');

/**
 * News Post - Featured image options
 */
$fields['hero_image'] = new FieldsBuilder('hero_image', [
    'position' => 'side'
]);
$fields['hero_image']
    ->addRadio('options')
    ->addChoices(['featured' => 'Use Featured Image as Hero'], ['alternate' => 'Use Alternate Hero Image'], ['hide' => 'Hide Hero Image'])
    ->addImage('alternate_hero')
    ->conditional('options', '==', 'alternate')

    ->addText('image_caption')

    ->setLocation('post_type', '==', 'post');

/**
 * Allow alternative post thumbnail to be used
 */
$fields['thumbnail'] = new FieldsBuilder('thumbnail', [
    'position' => 'side'
]);
$fields['thumbnail']
    ->addRadio('options')
    ->addChoices(['hero' => 'Use Hero Image'], ['alternative' => 'Use Alternative Image'])
    ->addImage('alternative_thumb')
    ->conditional('options', '==', 'alternative')

    ->setLocation('post_type', '==', 'ensembles')
    ->or('post_type', '==', 'resources')
    ->or('post_type', '==', 'facilities');

/**
 * Flexible Content
 */
$fields['page_content'] = new FieldsBuilder('page_content');
$fields['page_content']
    ->addFlexibleContent('sections')

    // Hero
    ->addLayout('hero')
    ->addWysiwyg('header', ['instructions' => '
            <code>h1 strong</code>Bold<br>
            <code>h1</code>Normal<br>
            <code>h1 em</code>Light<br>
            ', 'media_upload' => 0])
    ->addRadio('image_mask_svg')
    ->addSelect("style")
    ->addChoices(
        ["background-image" => "Background Image"],
        ['image-mask' => 'Image Mask']
    )
    ->addSelect("image_mask_style")
    ->addChoices(
        ["custom" => "Custom"],
        ["home-slider" => "Home Slider"],
        ["original" => "Original"],
        ["musicology" => "Musicology"],
        ["ethnomusicology" => "Ethnomusicology"],
        ["music" => "Music"],
        ["music-industry" => "Music Industry"],
        ["global-jazz" => "Global Jazz"]
    )
    ->conditional('style', '==', "image-mask")
    ->addRadio('mask', ["layout" => "horizontal", "instructions" => "Select an image mask."])
    ->addChoices(
        ['a' => 'Mask A'],
        ['b' => 'Mask B'],
        ['c' => 'Mask C'],
        ['d' => 'Mask D']
    )
    ->conditional('style', '==', "image-mask")
    ->and('image_mask_style', '==', "custom")
    ->addImage('image', [
        "wrapper" => $w50,
        'instructions' => '750x490 Background Image'
    ])
    ->conditional('style', '==', "background-image")
    ->or('style', '==', "image-mask")
    ->and('image_mask_style', '==', "custom")
    ->addText('image_caption', ["wrapper" => $w50, 'maxlength' => '40', "instructions" => "Max 40 Letters"])
    ->conditional('style', '==', "image-mask")
    ->and('image_mask_style', '==', "custom")
    ->addRadio('background', ["layout" => "horizontal"])
    ->addChoices(
        ['yellow-bg' => 'Yellow'],
        ['blue-bg' => 'Blue'],
        ['white-bg' => 'White']
    )
    ->conditional('style', '==', "image-mask")
    ->addSelect("link_style")
    ->addChoices(
        ["standard-link" => "Standard"],
        ["emphasized-cta" => "Emphasized"],
        ["emphasized-cta em-calendar-icon" => "Emphasized (Calendar Icon)"],
        ["emphasized-cta em-link-text" => "Emphasized (Text)"]
    )
    ->conditional("contact_info_box", "!=", 1)
    ->addText("emphasized_link_text", ["instructions" => "e.g. Prospective Student?"])
    ->conditional('link_style', '==', "emphasized-cta em-link-text")
    ->and("contact_info_box", "!=", 1)
    ->addLink('link', ["instructions" => "Add link text."])
    ->conditional("contact_info_box", "!=", 1)
    ->or("style", "==", "image-mask")
    ->addTrueFalse("contact_info_box", ['ui' => 1, "instructions" => "Show contact info inside the hero? Removes link."])
    ->conditional("style", "==", "background-image")
    ->addWysiwyg('contact_info', ['media_upload' => 0])
    ->conditional('style', '==', "background-image")
    ->and("contact_info_box", '==', 1)

    // Info Content
    ->addLayout('info_content')
    ->addTextArea('header', ['new_lines' => 'br', "instructions" => "Smaller font used after 30 letters for style Standard (Left)."])
    ->addSelect("style")
    ->addChoices(
        ['standard' => 'Standard (Left)'],
        ['standard right' => 'Standard (Right)'],
        ['image-block left' => 'Image (Left)'],
        ['image-block right' => 'Image (Right)'],
        ['image-mask left' => 'Image Mask (Left)'],
        ['image-mask right' => 'Image Mask (Right)'],
        ['background-image' => 'Background Image'],
        ['background-image inverted' => 'Background Image (Inverted Text)']
    )
    ->addRadio('mask', ["layout" => "horizontal", "instructions" => "Select an image mask."])
    ->addChoices(
        ['a' => 'Mask A'],
        ['b' => 'Mask B'],
        ['c' => 'Mask C'],
        ['d' => 'Mask D']
    )
    ->conditional('style', '==', "image-mask left")
    ->or('style', '==', "image-mask right")
    ->addImage('image', [
        "wrapper" => $w50,
        "instructions" => "1200x800 Image<br>1350x640 Image Mask<br>1920x650 Background Image"
    ])
    ->conditional('style', '!=', "standard")
    ->and('style', '!=', "standard right")
    ->addText('image_caption', ["wrapper" => $w50, 'maxlength' => '40', "instructions" => "Max 40 Letters"])
    ->conditional('style', '!=', "standard")
    ->and('style', '!=', "standard right")
    ->and('style', '!=', "background-image")
    ->and('style', '!=', "background-image inverted")
    ->addRadio('background', ["layout" => "horizontal"])
    ->addChoices(['white-bg' => 'White'], ['yellow-bg' => 'Yellow'], ['blue-bg' => 'Blue'])
    ->conditional('style', '==', "image-mask left")
    ->or('style', '==', "image-mask right")
    ->addRepeater('header_links', ['button_label' => 'Add Link', "layout" => "block", "min" => 1, "instructions" => "Blue links under header."])
    ->conditional('style', '==', "standard")
    ->or('style', '==', "standard right")
    ->or('style', '==', "background-image")
    ->or('style', '==', "background-image inverted")
    ->addLink('link')
    ->endRepeater()
    ->addWysiwyg('details', ['media_upload' => 0])
    ->conditional('style', '==', "standard")
    ->or('style', '==', "standard right")
    ->or('style', '==', "background-image")
    ->or('style', '==', "background-image inverted")
    ->addRepeater('details_links', ['button_label' => 'Add Link', "layout" => "block", "min" => 1, "instructions" => "Small yellow links under header."])
    ->conditional('style', '==', "standard")
    ->or('style', '==', "standard right")
    ->or('style', '==', "background-image")
    ->or('style', '==', "background-image inverted")
    ->addLink('link')
    ->endRepeater()
    ->addWysiwyg('body_text', ['media_upload' => 0])
    ->addLink("body_link", ["instructions" => "Single blue link under body text."])
    ->addFields($fields['external_links'])

    // Navigation carousel
    ->addLayout('navigation_carousel')
    ->addRadio('mask', ["layout" => "horizontal", "instructions" => "Select an image mask."])
    ->addChoices(
        ['a' => 'Mask A'],
        ['b' => 'Mask B'],
        ['c' => 'Mask C'],
        ['d' => 'Mask D']
    )
    ->addSelect("style")
    ->addChoices(
        ['image-mask left' => 'Image Mask (Left)'],
        ['image-mask right' => 'Image Mask (Right)']
    )
    ->addRepeater('categories', ['layout' => 'block', "min" => 1])
    ->addText('header')
    ->addRepeater('items', ["layout" => "block", "min" => 1])
    ->addTextArea('title', ["wrapper" => $w50])
    ->addTextArea('text', ["wrapper" => $w50, 'new_lines' => 'br'])
    ->addImage('image', ["wrapper" => $w50])
    ->addText('image_caption', ["wrapper" => $w50, 'maxlength' => '40', "instructions" => "Max 40 Letters"])
    ->addLink('link')
    ->endRepeater()

    // Quotes
    ->addLayout('quotes')
    ->addText('header')
    ->addRadio('mask', ["layout" => "horizontal", "instructions" => "Select an image mask."])
    ->addChoices(
        ['a' => 'Mask A'],
        ['b' => 'Mask B'],
        ['c' => 'Mask C'],
        ['d' => 'Mask D']
    )
    ->addSelect("style")
    ->addChoices(
        ['image-mask left' => 'Image Mask (Left)'],
        ['image-mask right' => 'Image Mask (Right)']
    )
    ->addRepeater('quotes', ['layout' => 'block', 'button_label' => 'Add Quote', "min" => 1])
    ->addText('author')
    ->addTextArea('quote', ['new_lines' => 'br'])
    ->addImage('image', ["wrapper" => $w50])
    ->addText('image_caption', ["wrapper" => $w50, 'maxlength' => '40', "instructions" => "Max 40 Letters"])
    ->endRepeater()

    // Call to Action
    ->addLayout('call_to_action')
    ->addSelect("style")
    ->addChoices(
        ["blue-bg flip-gradient" => "Blue Banner"],
        ["white-bg" => "Gray Box"]
    )
    ->addTextArea('header', ['new_lines' => 'br'])
    ->conditional("style", "==", "blue-bg flip-gradient")
    ->addWysiwyg("content", ['media_upload' => 0])
    ->conditional("style", "==", "white-bg")
    ->addRepeater("links", ["min" => 1, "layout" => "block"])
    ->addLink('link')
    ->endRepeater()

    // Contacts grid
    ->addLayout('contacts_grid')
    ->addText('header')
    ->addRepeater('contacts', ['layout' => 'block', "min" => 1])
    ->addText('reason_of_contact', ["wrapper" => $w50])
    ->addText('name', ["wrapper" => $w50])
    ->addText('phone', ["wrapper" => $w50])
    ->addText('email', ["wrapper" => $w50])

    // Degrees Grid
    ->addLayout('degrees_grid')
    ->addText('header', [
        'default_value' => 'Explore Other Degrees'
    ])
    ->addRadio('background')
    ->addChoices(['blue-bg' => 'Blue'], ['white-bg' => 'White'])

    // Ensembles grid
    ->addLayout('ensembles_grid')
    ->addText('header')
    ->addRadio('type', ['layout' => 'horizontal'])
    ->addChoices(['category' => 'Category'], ['selection' => 'Selection'])
    ->addTaxonomy('category', [
        'taxonomy' => 'category',
        'return_format' => 'id',
        "wrapper" => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('ensembles_category', [
        'taxonomy' => 'ensembles_category',
        'return_format' => 'id',
        "wrapper" => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addRelationship('ensembles', [
        'post_type' => 'ensembles',
        'filters' => array('search', 'taxonomy')
    ])
    ->conditional('type', '==', 'selection')

    ->addLayout('events_slider')
    ->addSelect('bg_color', [
        'choices' => [
            'bg-greylighter' => 'Light Grey',
            'bg-white' => 'White',
        ],
        'default_value' => 'bg-white'
    ])
    ->addText('title')
    ->addTrueFalse('featured_events', [
        'default_value' => 0,
        'ui' => 1,
    ])
    ->addTaxonomy('categories', [
        'taxonomy' => 'category',
        'return_format' => 'id',
        'multiple' => 1,
    ])->conditional('featured_events', '==', 1)
    ->addTaxonomy('event_category', [
        'taxonomy' => 'tribe_events_cat',
        'return_format' => 'id',
        'multiple' => 0,
    ])->conditional('featured_events', '==', 0)

    // Events grid
    ->addLayout('events_grid')
    ->addText('header')
    ->addRadio('type', ['layout' => 'horizontal'])
    ->addChoices(['category' => 'Category'], ['selection' => 'Selection'])
    ->addRange('number', ["default_value" => 3, "min" => 3, "max" => 12, "step" => 3, "instructions" => "Maximum # of events displayed"])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('event_category', [
        'taxonomy' => 'tribe_events_cat',
        'return_format' => 'id',
        'wrapper' => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('event_tags', [
        'taxonomy' => 'post_tag',
        'return_format' => 'id',
        'wrapper' => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('category', [
        'taxonomy' => 'category',
        'return_format' => 'id',
    ])
    ->conditional('type', '==', 'category')
    ->addRelationship('events', [
        'post_type' => 'tribe_events'
    ])
    ->conditional('type', '==', 'selection')

    // Facilities grid
    ->addLayout('facilities_grid')
    ->addText('header')
    ->addRadio('type', ['layout' => 'horizontal'])
    ->addChoices(['category' => 'Category'], ['selection' => 'Selection'])
    ->addTaxonomy('category', [
        'taxonomy' => 'category',
        'return_format' => 'id',
    ])
    ->conditional('type', '==', 'category')
    ->addRelationship('facilities', [
        'post_type' => 'facilities',
        'filters' => array('search', 'taxonomy')
    ])
    ->conditional('type', '==', 'selection')

    // Funds Grid
    ->addLayout('funds_grid')
    ->addText('header')
    ->addRadio('type', ['layout' => 'horizontal'])
    ->addChoices(['category' => 'Category'], ['selection' => 'Selection'])
    ->addTaxonomy('category', [
        'taxonomy' => 'category',
        'return_format' => 'id',
    ])
    ->conditional('type', '==', 'category')
    ->addRelationship('funds', [
        'post_type' => 'funds',
        'filters' => array('search', 'taxonomy')
    ])
    ->conditional('type', '==', 'selection')

    // Info Grid
    ->addLayout('info_grid')
    ->addText('header')
    ->addSelect("style", ['instructions' => 'How many columns on desktop?', "wrapper" => $w50])
    ->addChoices(["cards-2" => "2 Columns"], ["cards-3" => "3 Columns"])
    ->addTrueFalse("number_grid", ["wrapper" => $w50, "ui" => 1])
    ->addRepeater("info_columns", ["min" => 1, "layout" => "block"])
    ->addText("header_title", ["instructions" => "Number on number grid."])
    ->addText("title")
    ->addWysiwyg("content", ['media_upload' => 0])
    ->endRepeater()


    // Instruments Grid
    ->addLayout('instruments_grid')
    ->addText('header')
    ->addRadio('type', ['layout' => 'horizontal'])
    ->addChoices(['category' => 'Category'], ['selection' => 'Selection'])
    ->addTaxonomy('category', [
        'taxonomy' => 'category',
        'return_format' => 'id',
    ])
    ->conditional('type', '==', 'category')
    ->addRelationship('instruments', [
        'post_type' => 'instruments',
        'filters' => array('search', 'taxonomy')
    ])
    ->conditional('type', '==', 'selection')

    // Image Link grid
    ->addLayout('image_links_grid')
    ->addText('header')
    ->addSelect("style", ['instructions' => 'How many columns on desktop?'])
    ->addChoices(["cards-2" => "2 Columns"], ["cards-3" => "3 Columns"])
    ->addRepeater('links', ["layout" => "block", "min" => 1])
    ->addTextArea('title', ["wrapper" => $w50])
    ->addTextArea('text', ['new_lines' => 'br', "wrapper" => $w50])
    ->addImage('image', ["wrapper" => $w50])
    ->addLink('link', ["wrapper" => $w50])

    // Link grid
    ->addLayout('links_grid')
    ->addText('header')
    ->addSelect("style", ['instructions' => 'How many columns on desktop?'])
    ->addChoices(["cards-2" => "2 Columns"], ["cards-3" => "3 Columns"])
    ->addRepeater('links', ["layout" => "block", "min" => 1])
    //->addTextArea('title', ['new_lines' => 'br', "wrapper" => $w50])
    ->addLink('link', ["wrapper" => $w50])
    ->addTextArea('text', ['new_lines' => 'br', "wrapper" => $w50])

    // News grid
    ->addLayout('news_grid')
    ->addText('header')
    ->addLink('link')
    ->addRadio('type', ['layout' => 'horizontal', 'wrapper' => $w50])
    ->addChoices(['latest' => 'Latest'], ['category' => 'Category'], ['tag' => 'Tag'], ['selection' => 'Selection'])
    ->addTrueFalse('news_hero', ['ui' => 1, "instructions" => "Is this a News hero module?", 'wrapper' => $w50])
    ->addRange('number', ["default_value" => 3, "min" => 3, "max" => 12, "step" => 3])
    ->conditional('type', '==', 'latest')
    ->or('type', '==', 'category')
    ->or('type', '==', 'tag')
    ->addTaxonomy('category', [
        'taxonomy' => 'category',
        'return_format' => 'id'
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('tag', [
        'taxonomy' => 'post_tag',
        'return_format' => 'id'
    ])
    ->conditional('type', '==', 'tag')
    ->addRelationship('news', [
        'post_type' => 'post',
        'filters' => array('search', 'taxonomy')
    ])
    ->conditional('type', '==', 'selection')

    // People grid
    ->addLayout('people_grid')
    ->addText('header')
    ->addText('cta_override')
    ->addTaxonomy('link_to_department', [
        'taxonomy' => 'department',
        'return_format' => 'array',
    ])
    ->addText('link_override')
    ->addSelect("style")
    ->addChoices(["grid" => "Grid"], ["team-list" => "Team List"], ["team-slider" => "Team Slider"])
    ->addRadio('type', ['layout' => 'horizontal'])
    ->addChoices(['category' => 'Category'], ['selection' => 'Selection'], ['manual'=>'Manual'])
    ->addTaxonomy('category', [
        'taxonomy' => 'category',
        'return_format' => 'id',
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('people_category', [
        'taxonomy' => 'people_category',
        'return_format' => 'id',
        'wrapper' => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('areas_of_study', [
        'taxonomy' => 'area_ensemble',
        'return_format' => 'id',
        'wrapper' => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('degree_program', [
        'taxonomy' => 'degree_program',
        'return_format' => 'id',
        'wrapper' => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('department', [
        'taxonomy' => 'department',
        'return_format' => 'id',
        'wrapper' => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addRelationship('people', [
        'post_type' => 'people',
        'filters' => array('search', 'taxonomy')
    ])
    ->conditional('type', '==', 'selection')
    ->addRepeater('manual_people', ['button_label' => 'Add Person', "layout" => "block"])
    ->conditional('type', '==', 'manual')
        ->addText('title')
        ->addTextarea('text')
        ->addUrl('url')
        ->addImage('image')
    ->endRepeater()

    // Resources grid
    ->addLayout('resources_grid')
    ->addText('header')
    ->addTrueFalse('collections_grid', ['ui' => 1, "instructions" => "Is this a Collections grid?"])
    ->addRadio('type', ['layout' => 'horizontal'])
    ->addChoices(['category' => 'Category'], ['selection' => 'Selection'])
    ->addTaxonomy('category', [
        'taxonomy' => 'category',
        'return_format' => 'id',
    ])
    ->conditional('type', '==', 'category')
    ->addRelationship('resources', [
        'post_type' => 'resources',
        'filters' => array('search', 'taxonomy')
    ])
    ->conditional('type', '==', 'selection')

    // Requirements Grid
    ->addLayout('requirements_grid')
    ->addText('header')
    ->addRepeater('requirements', ["layout" => "block", "min" => 1])
    ->addTextArea('title', ['new_lines' => 'br', "wrapper" => $w50])
    ->addTextArea('text', ['new_lines' => 'br', "wrapper" => $w50])
    ->addRepeater('links', ["layout" => "block", "min" => 1])
    ->addLink("link")

    // Statistics Grid
    ->addLayout('statistics_grid')
    ->addText('header', ["default_value" => "UCLA Herb Alpert School of Music – At a Glance"])
    ->addRepeater('statistics', ["layout" => "block", "min" => 1, 'button_label' => 'Add Statistic'])
    ->addTextArea('unit', ["wrapper" => $w50, 'new_lines' => 'br'])
    ->addTextArea('value', ["wrapper" => $w50])
    ->endRepeater()


    // FAQs Accordion
    ->addLayout('faqs_accordion', ['label' => 'FAQs Accordion'])
    ->addText('header')
    ->addRadio('background', ["layout" => "horizontal"])
    ->addChoices(['white-bg' => 'White'], ['light-bg' => 'Light'])
    ->addRepeater('faqs', ["layout" => "block", 'button_label' => 'Add FAQ', 'label' => 'FAQs', "min" => 1])
    ->addTextArea('question', ['new_lines' => 'br'])
    ->addWysiwyg('answer', ['media_upload' => 0])
    ->endRepeater()

    // Courses Accordion
    ->addLayout('courses_accordion', ['label' => 'Courses Accordion'])
    ->addText('header')
    ->addText('subheader', ['instructions' => 'Optional'])
    ->addLink('link')
    ->addRadio('type', ['layout' => 'horizontal'])
    ->addChoices(['category' => 'Category'], ['selection' => 'Selection'])
    ->addTaxonomy('course_category', [
        'taxonomy' => 'course_category',
        'return_format' => 'id',
        'wrapper' => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addTaxonomy('course_type', [
        'taxonomy' => 'course_type',
        'return_format' => 'id',
        'wrapper' => $w50,
    ])
    ->conditional('type', '==', 'category')
    ->addRelationship('courses', [
        'post_type' => 'courses'
    ])
    ->conditional('type', '==', 'selection')


    // Video
    ->addLayout('video')
    ->addRadio('background', ["layout" => "horizontal", "instructions" => "Header style changes based on the background."])
    ->addChoices(['black-bg' => 'Black'], ['white-bg' => 'White'])
    ->addWysiwyg('video_header', ['instructions' => '
                <code>h1 strong</code>Bold<br>
                <code>h1</code>Normal<br>
                <code>h1 em</code>Light<br>
                ', 'media_upload' => 0])
    ->conditional("background", "==", "black-bg")
    ->addText("header")
    ->conditional("background", "==", "white-bg")
    ->addOEmbed('video')

    // Masonry Grid
    ->addLayout('image_masonry', ['label' => 'Masonry Grid'])
    ->addText('header')
    ->addRange('number', ["default_value" => 6, "min" => 6, "max" => 12, "step" => 2, "instructions" => "# of images to show initially and for each 'Load More'"])
    ->addRepeater('images', ['button_label' => 'Add Image', 'label' => 'Content', 'layout' => 'block'])
    ->addRadio('type', ["wrapper" =>  $w50, 'layout' => 'horizontal'])
    ->addChoices(['image' => 'Image'], ['video' => 'Video'])
    ->addRadio('brick_size', ['instructions' => 'Width x Height, use 1x1 or 2x2 for video', 'wrapper' =>  $w50, 'layout' => 'horizontal'])
    ->addChoices('1x1', '2x1', '1x2', '2x2')
    ->addImage('image')
    ->conditional('type', '==', 'image')
    ->addOEmbed('video')
    ->conditional('type', '==', 'video')
    ->addText('caption')
    ->endRepeater()

    // Virtual Tour
    ->addLayout('virtual_tour')
    ->addWysiwyg('header', [
        'media_upload' => 0,
        "default_value" => "<h3>Not able to visit us in Los Angeles?<br><strong>Explore the UCLA Virtual Tour</strong></h3>"
    ])
    ->addUrl('url', ["label" => "URL", "default_value" => "http://youvis.it/vpbnX7"])

    // Application Journey
    ->addLayout('application_journey')
    ->addText("header")
    ->addRepeater('application_journey', ['button_label' => 'Add Journey Step', "layout" => "block", "min" => 1])
    ->addText('title')
    ->addTextArea('text', ['new_lines' => 'br'])
    ->addSelect('application_journey_icon')
    ->addLink('link')
    ->endRepeater()

    // Custom code module
    ->addLayout('custom_code')
    ->addField('code', 'acf_code_field', ['placeholder' => ' '])

    // Contact form module
    ->addLayout('contact_form')
    ->addText('form_id', ['label' => 'Form ID'])

    // Events Directory
    ->addLayout('events_directory')
    ->addFields($fields['events_listings'])
    ->addText('livestream_event_url')

    // Ensembles Directory
    ->addLayout('ensembles_directory')
    ->addFields($fields['ensembles_listings'])

    // Faculty Directory
    ->addLayout('faculty_directory')
    ->addFields($fields['faculty_listings'])

    // Research Directory
    ->addLayout('research_directory')
    ->addFields($fields['research_listings'])

    // Ustream
    ->addLayout('ustream', ["placement" => "left"])
    ->addRepeater('location', ['button_label' => 'Add Location', "layout" => "block"])
    ->addText("name")
    ->addText("url")

    // Event Submission form
    ->addLayout('event_submission_form')

    ->setLocation('page_template', '!=', 'views/template-custom.blade.php')
    ->and('post_type', '==', 'page')
    ->or('post_type', '==', 'resources')
    ->or('post_type', '==', 'instruments')
    ->or('post_type', '==', 'facilities')
    ->or('post_type', '==', 'ensembles')
    ->or('post_type', '==', 'people')

    ->setGroupConfig('hide_on_screen', [
        'the_content',
        'featured_image'
    ]);


/**
 * Post tags fields
 */
$fields['post_tags'] = new FieldsBuilder('post_tags', [
    'style' => 'seamless',
    'menu_order' => 0,
    'label_placement' => 'top',
]);
$fields['post_tags']
    ->addTrueFalse('hide_from_main_blog', [
        'default_value' => 0,
        'ui' => 1,
    ])
    ->setLocation('taxonomy', '==', 'post_tag');

return $fields;
