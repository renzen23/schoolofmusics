<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('pollyfill', 'https://cdn.polyfill.io/v2/polyfill.min.js?features=default,Array.prototype.findIndex', [], null, true);
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    //add_theme_support('soil-google-analytics', 'UA-60848399-1', 'wp_head'); // script will load during wp_head

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'secondary_navigation' => __('Secondary Navigation', 'sage'),
        'footer_navigation' => __('Footer Navigation', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
}, 20);


// function admin_style() {
//     wp_enqueue_style('sage/admin_style', asset_path('styles/editor-style.css'), false, null);
// }
// add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\admin_style' );

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'sage'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'sage'),
        'id'            => 'sidebar-footer'
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });

    /**
     * Create a component
     */
    sage('blade')->compiler()->component('components.wrapper');
    sage('blade')->compiler()->component('components.cta');
    sage('blade')->compiler()->component('components.slick_arrows');
    sage('blade')->compiler()->component('components.image_mask_group');
    sage('blade')->compiler()->component('components.image_mask');
    sage('blade')->compiler()->component('components.card_grid');
    sage('blade')->compiler()->component('components.card');
    sage('blade')->compiler()->component('components.section_header');
    sage('blade')->compiler()->component('components.accordion');
    sage('blade')->compiler()->component('components.accordion_group');
    sage('blade')->compiler()->component('components.external_links');
    sage('blade')->compiler()->component('components.input_fields');
});

/**
 * Add ACF Option pages
 */
if( function_exists('acf_add_options_page') ) {

  acf_add_options_page([
    'page_title'  => 'Global Settings',
    'menu_title'  => 'Global Settings',
    'menu_slug'   => 'global-options',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ]);

}

/**
 * Add Child menu functions
 */

 class ArrayUtils
 {
     public static function objArraySearch($array, $index, $value)
     {
         foreach($array as $arrayInf) {
             if($arrayInf->{$index} == $value) {
                 return $arrayInf;
             }
         }
         return null;
     }
     public static function objArrayReturnAll($array, $index, $value)
     {
         $return = [];
         foreach($array as $arrayInf) {
             if($arrayInf->{$index} == $value) {
                 $return[] = $arrayInf;
             }
         }
         return $return;
     }
 }

 function ucla_get_menu_item_ID( $loc ) {
    global $post;

    $locs = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object( $locs[$loc] );
    $parent = $ancestor = $items = [];
    $currentOrParent = '';
    if($menu) {
        $items = wp_get_nav_menu_items($menu->term_id);
        //var_dump($items);

        foreach ($items as $item) :
            $thisParent = $item->menu_item_parent;
            $thisObjectID = $item->object_id;

            $allmenu[$thisParent][$thisObjectID]['ID']      = $item->object_id;
            $allmenu[$thisParent][$thisObjectID]['title']   = $item->title;
            $allmenu[$thisParent][$thisObjectID]['url']     = $item->url;
            $allmenu[$thisParent][$thisObjectID]['target']  = $item->target;
            $allmenu[$thisParent][$thisObjectID]['classes'] = $item->classes;

            $children = ArrayUtils::objArrayReturnAll($items,'menu_item_parent',$item->ID);
            if ($children) :
                foreach ($children as $child) :
                    $allmenu[$thisParent][$thisObjectID]['children'][$child->ID]['ID']    = $child->ID;
                    $allmenu[$thisParent][$thisObjectID]['children'][$child->ID]['title'] = $child->title;
                    $allmenu[$thisParent][$thisObjectID]['children'][$child->ID]['url']   = $child->url;
                    $allmenu[$thisParent][$thisObjectID]['children'][$child->ID]['target'] = $child->target;
                    $allmenu[$thisParent][$thisObjectID]['children'][$child->ID]['classes'] = $child->classes;
                endforeach;
            endif;
            //var_dump($children);
            //var_dump(get_ancestors( $item->object_id, 'nav_menu_item'));

        endforeach;


        $thisSearch = ArrayUtils::objArraySearch($items,'object_id',$post->ID);


        if ($thisSearch) :
            $thisMenuID = $thisSearch->ID;
            $thisMenuParent = $thisSearch->menu_item_parent;

            $ancestors = get_ancestors( $thisMenuID, 'nav_menu_item');

            // if (isset($ancestors)) :
            //     $thisancestor = end($ancestors);
            // endif;


            // if there are subitems show them, or else show the parent subitems
            $currentOrParent = (array_key_exists($thisMenuID,$allmenu)) ? $thisMenuID : $thisMenuParent;

            // find the parent object
            $parentSearch = ArrayUtils::objArraySearch($items,'ID',$currentOrParent);
            if ($parentSearch) :
                $parent['url'] = $parentSearch->url;
                $parent['title'] = $parentSearch->title;
                //$parent['ancestor'] = $thisancestor;
                $parent['ancestors'] = $ancestors;

                $thisancestors = [];
                if (isset($ancestors)) :
                    foreach (array_reverse($ancestors) as $key => $ancestor) :
                        $thisancestors[$key]['title'] = get_the_title($ancestor);
                        $thisancestors[$key]['url'] = get_the_permalink($ancestor);
                    endforeach;
                 endif ;
            endif;
        endif;
    }
    //var_dump($items);
    //var_dump($allmenu);

    //var_dump($allmenu[310][335]['children']);
    //print_r(ArrayUtils::objArraySearch($items,'ID','1142'));
    if ($currentOrParent)
        return array('ancestors'=>$thisancestors,'parent'=>$parent,'items'=>$allmenu[$currentOrParent]);
    return;
}
/**
* Initialize ACF Builder
*/
add_action('init', function () {

    // Register Classes/Controller
    collect(glob(config('theme.dir').'/app/classes/blocks/*.php'))->map(function ($field) {
        return require_once($field);
    });

    // Register Fields
    collect(glob(config('theme.dir').'/app/fields/*.php'))->map(function ($field) {
        return require_once($field);
    })->map(function ($fields) {
        $flexible_content = $fields['page_content']->build();
        // echo "<pre>";
        // print_r( $flexible_content['fields'][0]['layouts'] );
        // echo "</pre>";
        foreach( $flexible_content['fields'][0]['layouts'] as $flex ) {
            Builder\Config::createDynamic($flex['name'], array_column($flex['sub_fields'], 'name'));
        }
        // echo "<pre>";
        // print_r($names);
        // echo "</pre>";
        // collect($flexible_content['fields'])->map(function())
        // foreach($flexible_content['fields'] as $field) {
        //     echo "<pre>";
        //     print_r( $field );
        //     echo "</pre>";
        // }
        // echo "<pre>";
        // print_r( $fields['page_content']->build() );
        // echo "</pre>";
        foreach ($fields as $field) {

            if ($field instanceof FieldsBuilder) {
                acf_add_local_field_group($field->build());
            }
        }
    });

}, 12);


/**
*  Change Posts to News Posts
*/
add_action( 'admin_menu', function() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'News Posts';
    $submenu['edit.php'][5][0] = 'News Posts';
    $submenu['edit.php'][10][0] = 'Add News Post';
    $submenu['edit.php'][16][0] = 'News Tags';
} );

add_action( 'init', function() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'News Posts';
    $labels->singular_name = 'News Post';
    $labels->add_new = 'Add News Post';
    $labels->add_new_item = 'Add News Post';
    $labels->edit_item = 'Edit News Post';
    $labels->new_item = 'News Posts';
    $labels->view_item = 'View News Post';
    $labels->search_items = 'Search News Posts';
    $labels->not_found = 'No News Posts found';
    $labels->not_found_in_trash = 'No News Posts found in Trash';
    $labels->all_items = 'All News Posts';
    $labels->menu_name = 'News Posts';
    $labels->name_admin_bar = 'News Posts';
} );

/**
*  Tribe Events - enable global categories
*/
add_filter( 'tribe_events_register_event_type_args', function( $args ){
	$args['taxonomies'][] = 'category';
	return $args;
} );

/**
*  Contact Form 7 - disable wpautop
*/
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Pretty URL for Events to correctly disply programs
 */
add_action( 'init',  function() {
    add_rewrite_rule( 'event/([a-z0-9-]+)/program$', 'index.php?tribe_events=$matches[1]&program=1', 'top' );
});