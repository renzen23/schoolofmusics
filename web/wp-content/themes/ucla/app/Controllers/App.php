<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class App extends Controller
{
    public function siteName()
    {
        return get_bloginfo('name');
    }

    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            if (is_tag()) {
                $tag_title = str_replace("Tag: ", "#", get_the_archive_title());
                return $tag_title;
            } else {
                return get_the_archive_title();
            }
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }

    public function headerLogo()
    {
        return get_field('logo', 'option');
    }

    public function headerLogoInverse() 
    {
        return get_field('logo_inverse', 'option');
    }

    public function footerLogo()
    {
        return get_field('footer_logo', 'option');
    }

    public function address()
    {
        return get_field('address', 'option');
    }

    public function copyright()
    {
        return get_field('copyright', 'option');
    }

    public function externalLinks()
    {
        return get_field('external_links', 'option');
    }

    public function degreeHeader()
    {
        return get_field('degree_header', 'option');
    }

    public function degreeCategories()
    {
        return get_field('degree_categories', 'option');
    }

}
