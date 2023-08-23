<?php

namespace App;

/**
 * Theme customizer
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    // Add postMessage support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->selective_refresh->add_partial('blogname', [
        'selector' => '.brand',
        'render_callback' => function () {
            bloginfo('name');
        }
    ]);
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
    wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});


/**
* Add masks to builder UI
*/
add_action('admin_head', function() {

    $mask_a = get_field("a", "option")["left_svg"]["url"];
    $mask_b = get_field("b", "option")["left_svg"]["url"];
    $mask_c = get_field("c", "option")["left_svg"]["url"];
    $mask_d = get_field("d", "option")["left_svg"]["url"];

    ?>

    <style>

    /* Paragraph */
    .mce-menu-item:nth-child(1) .mce-text {
        color: #717171;
    }

    /* Heading 1 */
    .mce-menu-item:nth-child(2) .mce-text {
        font-weight: 600 !important;
    }

    /* Heading 2 */
    .mce-menu-item:nth-child(3) .mce-text {
        font-weight: 600 !important;
    }

    /* Heading 3 */
    .mce-menu-item:nth-child(4) .mce-text {
        /* color: #3183be; */
        font-weight: 300 !important;
    }

    /* Heading 5 */
    .mce-menu-item:nth-child(6) .mce-text {
        color: #717171;
        font-weight: 600 !important;
    }

    /* Heading 6 */
    .mce-menu-item:nth-child(7) .mce-text {
        font-weight: 400 !important;
    }

    input[value="a"], input[value="b"], input[value="c"], input[value="d"] {
        width: 100px;
        height: 100px;
        border-radius: 0;
        border: none !important;
        box-shadow: none !important;
        outline: none !important;
        background-size: 80%;
        background-repeat: no-repeat;
        background-position: center center;
        focus: none;
        opacity: 0.3;
        -webkit-appearance: none;
    }

    input[value="a"]:checked, input[value="b"]:checked, input[value="c"]:checked, input[value="d"]:checked {
        opacity: 1;
    }

    input[value="a"]:checked:before, input[value="b"]:checked:before, input[value="c"]:checked:before, input[value="d"]:checked:before {
        display: none;
    }

    input[value="a"] {
        background-image:url(<?php echo $mask_a ?>);
    }

    input[value="b"] {
        background-image:url(<?php echo $mask_b ?>);
    }

    input[value="c"] {
        background-image:url(<?php echo $mask_c ?>);
    }

    input[value="d"] {
        background-image:url(<?php echo $mask_d ?>);
    }

    .acf-flexible-content .layout .acf-fc-layout-handle {
        background: #EBEBEB;
        color: black;
    }
    .acf-repeater .acf-fields {
        border-bottom: 2px solid #B3B3B3;
    }

    /* admin editor */
    #acf-group_page_content {
    max-width: 100vw;
    }
    .is-sidebar-opened #acf-group_page_content {
        max-width: calc(100vw - 280px);
    }
    </style>

<?php
});