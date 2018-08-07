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
                'name' => __('Collective'),
                'singular_name' => __('Collective'),
                'add_new' => __('Add to Collective'),
                'add_new_item' => __('Add to Collective'),
                'view_item' => __('View Rental'),
                'edit_item' => __('Edit Rental'),
                'search_items' => __('Search in Collective'),
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

function ln_custom_fonts_init( $init ) {
    $custom_fonts = 'Andale Mono=andale mono,times;'
    .'Arial=arial,helvetica,sans-serif;'.
    'Arial Black=arial black,avant garde;'.
        'Book Antiqua=book antiqua,palatino;'.
    'Comic Sans MS=comic sans ms,sans-serif;'.
    'Courier New=courier new,courier;'.
    'Georgia=georgia,palatino;'.
    'Helvetica=helvetica;'.
    'Impact=impact,chicago;'.
    'Symbol=symbol;'.
    'Tahoma=tahoma,arial,helvetica,sans-serif;'.
    'Terminal=terminal,monaco;'.
    'Times New Roman=times new roman,times;'.
    'Trebuchet MS=trebuchet ms,geneva;'.
    'Verdana=verdana,geneva;'.
    'Webdings=webdings;'.
    'Wingdings=wingdings,zapf dingbats';
    $init['font_formats'] = $custom_fonts;
    return $init;
}
add_filter( 'tiny_mce_before_init', 'ln_custom_fonts_init' );

function admin_style() {
    wp_enqueue_style('admin-styles', get_template_directory_uri().'/css/app/fonts.css');
}
add_action('admin_enqueue_scripts', 'admin_style');

// Customize mce editor font sizes
if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
    function wpex_mce_text_sizes( $initArray ){
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px 50px 100px 109px";
        return $initArray;
    }
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );

/**
 * Add custom styles to the mce formats dropdown
 *
 * @url https://codex.wordpress.org/TinyMCE_Custom_Styles
 *
 */
function myprefix_add_format_styles( $init_array ) {
    $style_formats = array(
        // Each array child is a format with it's own settings - add as many as you want
        array(
            'title'    => __( 'Bodoni', 'text-domain' ), // Title for dropdown
            'selector' => 'span', // Element to target in editor
            'classes'  => 'bodoni-font' // Class name used for CSS
        ),
        array(
            'title'    => __( 'Storybook', 'text-domain' ), // Title for dropdown
            'inline'   => 'span', // Wrap a span around the selected content
            'classes'  => 'storybook-font' // Class name used for CSS
        ),
    );
    $init_array['style_formats'] = json_encode( $style_formats );
    return $init_array;
}
add_filter( 'tiny_mce_before_init', 'myprefix_add_format_styles' );

add_image_size("small", 200, 180);

function wpb_change_search_url() {
    if ( is_search() && ! empty( $_GET['s'] ) ) {
        wp_redirect( home_url( "/rentals/search/?s=" ) . urlencode( get_query_var( 's' ) ) );
        exit();
    }
}
add_action( 'template_redirect', 'wpb_change_search_url' );

add_action('wp', function() {
    $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
    
    
//     if ( ! empty( $_GET['s'] ) ) {
//         wp_redirect( home_url( "/rentals/search/?s=" ) . urlencode( get_query_var( 's' ) ) );
//         exit();
//     }
    
    if ( $url_path === 'rentals/search' ) {
        // load the file if exists
        $load = locate_template('page_search.php', true);
        if ($load) {
            exit(); // just exit if template was found and loaded
        }
    }
});
