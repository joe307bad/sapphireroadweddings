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
require_once( dirname( __FILE__ ) . '/lib/init.php' );

require_once( dirname( __FILE__ ) . '/lib/attachments.php' );

function wpsites_query( $query ) {
    if ( $query->is_archive() && $query->is_main_query() && !is_admin() ) {
        $query->set( 'posts_per_page', 100 );
    }
}
add_action( 'pre_get_posts', 'wpsites_query' );
apply_filters( 'taxonomy-images-get-terms', '' );
