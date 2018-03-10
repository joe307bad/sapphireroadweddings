<?php
/**
 * Genesis Framework
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit
 * this file under any circumstances. Please do all modifications
 * in the form of a child theme.
 *
 * @package  Genesis
 * @author   StudioPress
 * @license  GPL-2.0+
 * @link     http://my.studiopress.com/themes/genesis/
 */

/**
 * Calls the init.php file, but only if the child theme hasn't called it first.
 *
 * This method allows the child theme to load
 * the framework so it can use the framework
 * components immediately.
 */
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

remove_filter('template_redirect', 'redirect_canonical');

require_once(dirname(__FILE__) . '/lib/init.php');

require_once(dirname(__FILE__) . '/lib/attachments.php');

function wpsites_query($query)
{
    if ($query->is_archive() && $query->is_main_query() && !is_admin()) {
        $query->set('posts_per_page', 100);
    }
}

add_action('pre_get_posts', 'wpsites_query');
apply_filters('taxonomy-images-get-terms', '');

add_action('init', 'register_rental_pt', 0);
function register_rental_pt()
{
    register_post_type(
        'rentals',
        array(
            'rewrite' => array('slug' => 'rentals/%rental_category%', 'with_front' => false),
            'has_archive' => 'rentals',
            'menu_position' => 21,
            'show_in_menu' => true,
            'labels' => array(
                'name' => __('Rentals'),
                'singular_name' => __('Rental'),
                'add_new' => __('Add New Rental'),
                'add_new_item' => __('Add New Rental'),
                'view_item' => __('View Rental'),
                'edit_item' => __('Edit Rental'),
                'search_items' => __('Search for a Rental'),
                'not_found' => __('Rental not found'),
                'not_found_in_trash' => __('Rental not found'),
            ),
            'capability_type' => 'post',
            'show_ui' => true,
            'publicly_queryable' => true,
            'public' => true,
            'exclude_from_search' => false,
            'query_var' => true,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
            // your other args...
        )
    );
}

add_action( 'init', 'rental_taxonomies', 0 );
function rental_taxonomies() {

    $labels = array(
        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'fire' ),
        'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'fire' ),
        'menu_name'                  => __( 'Categories', 'fire' ),
        'all_items'                  => __( 'All Items', 'fire' ),
        'parent_item'                => __( 'Parent Item', 'fire' ),
        'parent_item_colon'          => __( 'Parent Item:', 'fire' ),
        'new_item_name'              => __( 'New Item Name', 'fire' ),
        'add_new_item'               => __( 'Add New Item', 'fire' ),
        'edit_item'                  => __( 'Edit Item', 'fire' ),
        'update_item'                => __( 'Update Item', 'fire' ),
        'view_item'                  => __( 'View Item', 'fire' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'fire' ),
        'add_or_remove_items'        => __( 'Add or remove items', 'fire' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'fire' ),
        'popular_items'              => __( 'Popular Items', 'fire' ),
        'search_items'               => __( 'Search Items', 'fire' ),
        'not_found'                  => __( 'Not Found', 'fire' ),
        'no_terms'                   => __( 'No items', 'fire' ),
        'items_list'                 => __( 'Items list', 'fire' ),
        'items_list_navigation'      => __( 'Items list navigation', 'fire' ),
    );
    $args = array(
        'rewrite' => array( 'slug' => 'rentals', 'with_front' => false ),
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'rental_category', array( 'rentals' ), $args );

}

add_filter('post_type_link', 'wpa_rental_permalinks', 1, 2);
function wpa_rental_permalinks($post_link, $post)
{
    if (is_object($post) && $post->post_type == 'rentals') {
        $terms = wp_get_object_terms($post->ID, 'rental_category');
        if ($terms) {
            return str_replace('%rental_category%', $terms[0]->slug, $post_link);
        }
    }
    return $post_link;
}
