<?php
/**
 * This file adds the Home Page to the Simply Charming Theme.
 *
 */

add_action( 'genesis_meta', 'charming_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function charming_home_genesis_meta() {

	if ( is_active_sidebar( 'home-section-3' ) || is_active_sidebar( 'home-section-4' ) ) {

		//* Enqueue charming script
		add_action( 'wp_enqueue_scripts', 'charming_enqueue_charming_script' );
		function charming_enqueue_charming_script() {

			if ( ! wp_is_mobile() ) {

				wp_enqueue_script( 'charming-script', get_bloginfo( 'stylesheet_directory' ) . '/js/charming.js', array( 'jquery' ), '1.0.0' );

			}

		
		}

		//* Add charming-home body class
		add_filter( 'body_class', 'charming_body_class' );
		function charming_body_class( $classes ) {
		
   			$classes[] = 'charming-home';
  			return $classes;
  			
		}

		//* Force full width content layout
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

		//* Remove primary navigation
		remove_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_nav' );

		//* Remove breadcrumbs
		remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs');

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Add homepage widgets
		add_action( 'genesis_loop', 'charming_homepage_widgets' );

	}
}

//* Add markup for homepage widgets
function charming_homepage_widgets() {

	genesis_widget_area( 'home-section-3', array(
		'before' => '<div class="home-section-3 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

	genesis_widget_area( 'home-section-4', array(
		'before' => '<div class="home-section-4 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

genesis();
