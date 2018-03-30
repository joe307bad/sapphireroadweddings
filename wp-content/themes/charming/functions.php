<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Add Image upload to WordPress Theme Customizer
add_action( 'customize_register', 'charming_customizer' );
function charming_customizer(){

	require_once( get_stylesheet_directory() . '/lib/customize.php' );
	
}

//* Include Section Image CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'charming_enqueue_scripts_styles' );
function charming_enqueue_scripts_styles() {

	wp_enqueue_script( 'charming-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'charming-google-fonts', '//fonts.googleapis.com/css?family=Lato:400,300italic,300,700,400italic,700italic|Open+Sans:400,300italic,300,400italic,600,700,700italic|Roboto+Condensed:400,300,300italic,400italic,700,700italic', array() );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add new image sizes
add_image_size( 'featured-home-vertical', 365, 500, TRUE );
add_image_size( 'featured-home-horizontal', 365, 225, TRUE );
add_image_size( 'featured-blog', 700, 450, TRUE );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'charming_secondary_menu_args' );
function charming_secondary_menu_args( $args ){

	if( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;

}

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Add support for additional color styles
add_theme_support( 'genesis-style-selector', array(
	'charming-purple'      => __( 'Simply Charming Purple', 'charming' ),
	'charming-turquoise'   => __( 'Simply Charming Turquoise', 'charming' ),
	'charming-cobalt'      => __( 'Simply Charming Cobalt', 'charming' ),
	'charming-emerald'     => __( 'Simply Charming Emerald', 'charming' ),
	'charming-peach'       => __( 'Simply Charming Peach', 'charming' ),
	'charming-gold'        => __( 'Simply Charming Gold', 'charming' ),
) );

add_filter('body_class', 'string_body_class');
function string_body_class( $classes ) {
	if ( isset( $_GET['color'] ) ) :
		$classes[] = 'charming-' . sanitize_html_class( $_GET['color'] );
	endif;

	return $classes;
}

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 550,
	'height'          => 170,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'footer-widgets',
	'footer',
) );

//* Hooks previous and next post
add_action( 'genesis_entry_footer', 'single_post_nav', 9 );
function single_post_nav() {

	if ( is_singular('post' ) ) {

		$prev_post = get_adjacent_post(false, '', true);
		$next_post = get_adjacent_post(false, '', false);
		echo '<div class="prev-next-post-links">';
			previous_post_link( '<div class="previous-post-link" title="Previous Post: ' . $prev_post->post_title . '">%link</div>', '&laquo;' );
			next_post_link( '<div class="next-post-link" title="Next Post: ' . $next_post->post_title . '">%link</div>', '&raquo;' );
		echo '</div>';

	}

}

//* Hook after post widget after the entry content
add_action( 'genesis_after_entry', 'charming_after_entry', 5 );
function charming_after_entry() {

	if ( is_singular( 'post' ) )
		genesis_widget_area( 'after-entry', array(
			'before' => '<div class="after-entry widget-area">',
			'after'  => '</div>',
		) );

}

//* Hooks home top section widget area to home page
add_action( 'genesis_meta', 'charming_home_featured' );
//*Add widget support for homepage. If no widgets active, display the default loop.
function charming_home_featured() {
	if ( is_front_page() & is_active_sidebar( 'home-section-1' ) ) {
		add_action( 'genesis_after_header', 'charming_home_top' );
	}
}

function charming_home_top() {
	genesis_widget_area( 'home-section-1', array(
		'before' => '<div class="home-section-1 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );
}

//* Hooks home top section widget area to home page
add_action( 'genesis_meta', 'charming_home_featured_second' );
//*Add widget support for homepage. If no widgets active, display the default loop.
function charming_home_featured_second() {
	if ( is_front_page() & is_active_sidebar( 'home-section-2' ) ) {
		add_action( 'genesis_after_header', 'charming_home_top_second' );
	}
}

function charming_home_top_second() {
	genesis_widget_area( 'home-section-2', array(
		'before' => '<div class="home-section-2 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );
}


//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'charming_author_box_gravatar' );
function charming_author_box_gravatar( $size ) {

	return 176;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'charming_comments_gravatar' );
function charming_comments_gravatar( $args ) {

	$args['avatar_size'] = 120;

	return $args;

}

//* Customize the post info function 
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
if (!is_page()) {
    $post_info = '[post_date] | [post_comments] [post_edit]';
    return $post_info;
}}



//* Customize the post meta function 
add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
if (!is_page()) {
    $post_meta = '[post_categories] | [post_tags]';
    return $post_meta;
}}

//* Customize the credits 
add_filter('genesis_footer_creds_text', 'custom_footer_creds_text');
function custom_footer_creds_text() {
    echo '<div class="creds"><p>';
    echo 'Copyright &copy; ';
    echo date('Y');
    echo ' Sapphire Road Weddings & Events, LLC';
    echo '</p></div>';

}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-section-1',
	'name'        => __( 'Home Section 1', 'charming' ),
	'description' => __( 'This is the home section 1 section.', 'charming' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-section-2',
	'name'        => __( 'Home Section 2', 'charming' ),
	'description' => __( 'This is the home section 2 section.', 'charming' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-section-3',
	'name'        => __( 'Home Section 3', 'charming' ),
	'description' => __( 'This is the home section 3 section.', 'charming' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-section-4',
	'name'        => __( 'Home Section 4', 'charming' ),
	'description' => __( 'This is the home section 4 section.', 'charming' ),
) );
genesis_register_sidebar( array(
	'id'          => 'after-entry',
	'name'        => __( 'After Entry', 'charming' ),
	'description' => __( 'This is the after entry widget area.', 'charming' ),
) );
