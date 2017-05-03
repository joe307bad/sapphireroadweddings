<?php

//* Charming Theme Setting Defaults
add_filter( 'genesis_theme_settings_defaults', 'charming_theme_defaults' );
function charming_theme_defaults( $defaults ) {

	$defaults['blog_cat_num']              = 5;
	$defaults['content_archive']           = 'full';
	$defaults['content_archive_limit']     = 500;
	$defaults['content_archive_thumbnail'] = 0;
	$defaults['posts_nav']                 = 'numeric';

	return $defaults;

}

//* Charming Theme Setup
add_action( 'after_switch_theme', 'charming_theme_setting_defaults' );
function charming_theme_setting_defaults() {

	_genesis_update_settings( array(
		'blog_cat_num'              => 5,	
		'content_archive'           => 'full',
		'content_archive_limit'     => 500,
		'content_archive_thumbnail' => 'featured-blog',
		'posts_nav'                 => 'numeric',
		'site_layout'               => 'content_sidebar',
	) );

	update_option( 'posts_per_page', 5 );

}

//* Simple Social Icon Defaults
add_filter( 'simple_social_default_styles', 'charming_social_default_styles' );
function charming_social_default_styles( $defaults ) {

	$args = array(
		'alignment'              => 'aligncenter',
		'background_color'       => '#ffffff',
		'background_color_hover' => '#ffffff',
		'border_radius'          => 0,
		'icon_color'             => '#ef5488',
		'icon_color_hover'       => '#555555',
		'size'                   => 40,
		);
		
	$args = wp_parse_args( $args, $defaults );
	
	return $args;
	
}

//* Set Genesis Responsive Slider defaults
add_filter( 'genesis_responsive_slider_settings_defaults', 'charming_responsive_slider_defaults' );
function charming_responsive_slider_defaults( $defaults ) {

	$args = array(
		'posts_num'                       => '5',
		'slideshow_height'                => '300',
		'slideshow_title_show'            => 1,
		'slideshow_width'                 => '820',
	);

	$args = wp_parse_args( $args, $defaults );
	
	return $args;
}