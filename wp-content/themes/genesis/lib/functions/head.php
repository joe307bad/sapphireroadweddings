<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Header
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

/**
 * Determine the meta description based on contextual criteria.
 *
 * @since 2.4.0
 *
 * @return string Meta description.
 */
function genesis_get_seo_meta_description() {

	$description = '';
	$post_id = null;

	// If we're on the root page.
	if ( genesis_is_root_page() ) {
		$description = genesis_get_seo_option( 'home_description' ) ? genesis_get_seo_option( 'home_description' ) : get_bloginfo( 'description' );
	}

	// When the page is set as the Posts Page in WordPress core, use the $post_id of the page when loading SEO values.
	if ( is_home() && get_option( 'page_for_posts' ) && get_queried_object_id() ) {
		$post_id = get_option( 'page_for_posts' );
	}

	// If we're on a single post / page / attachment.
	if ( null !== $post_id || is_singular() ) {
		if ( genesis_get_custom_field( '_genesis_description', $post_id ) ) {
			// Description is set via custom field.
			$description = genesis_get_custom_field( '_genesis_description', $post_id );
		} elseif ( genesis_get_custom_field( '_aioseop_description', $post_id ) ) {
			// All-in-One SEO Pack (latest, vestigial).
			$description = genesis_get_custom_field( '_aioseop_description', $post_id );
		} elseif ( genesis_get_custom_field( '_headspace_description', $post_id ) ) {
			// Headspace2 (vestigial).
			$description = genesis_get_custom_field( '_headspace_description', $post_id );
		} elseif ( genesis_get_custom_field( 'thesis_description', $post_id ) ) {
			// Thesis (vestigial).
			$description = genesis_get_custom_field( 'thesis_description', $post_id );
		} elseif ( genesis_get_custom_field( 'description', $post_id ) ) {
			// All-in-One SEO Pack (old, vestigial).
			$description = genesis_get_custom_field( 'description', $post_id );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term             = get_queried_object();
		$term_description = get_term_meta( $term->term_id, 'description', true );
		$description      = ! empty( $term_description ) ? $term_description : '';
	} elseif ( is_post_type_archive() && genesis_has_post_type_archive_support() ) {
		$cpt_description = genesis_get_cpt_option( 'description' );
		$description     = $cpt_description ? $cpt_description : '';
	} elseif ( is_author() ) {
		$description = get_the_author_meta( 'meta_description', (int) get_query_var( 'author' ) );
	}

	/**
	 * Filter the content for the description meta tag.
	 *
	 * @since 2.4.0
	 *
	 * @param string $description Meta description string.
	 */
	$description = apply_filters( 'genesis_get_seo_meta_description', $description );

	return trim( $description );
}

/**
 * Determine the meta keywords based on contextual criteria.
 *
 * @since 2.4.0
 *
 * @return string Content for keywords meta tag.
 */
function genesis_get_seo_meta_keywords() {

	$keywords = '';
	$post_id = null;

	// If we're on the root page.
	if ( genesis_is_root_page() ) {
		$keywords = genesis_get_seo_option( 'home_keywords' );
	}

	// When the page is set as the Posts Page in WordPress core, use the $post_id of the page when loading SEO values.
	if ( is_home() && get_option( 'page_for_posts' ) && get_queried_object_id() ) {
		$post_id = get_option( 'page_for_posts' );
	}

	if ( null !== $post_id || is_singular() ) {
		if ( genesis_get_custom_field( '_genesis_keywords', $post_id ) ) {
			// Keywords are set via custom field.
			$keywords = genesis_get_custom_field( '_genesis_keywords', $post_id );
		} elseif ( genesis_get_custom_field( '_aioseop_keywords', $post_id ) ) {
			// All-in-One SEO Pack (latest, vestigial).
			$keywords = genesis_get_custom_field( '_aioseop_keywords', $post_id );
		} elseif ( genesis_get_custom_field( 'thesis_keywords', $post_id ) ) {
			// Thesis (vestigial).
			$keywords = genesis_get_custom_field( 'thesis_keywords', $post_id );
		} elseif ( genesis_get_custom_field( 'keywords', $post_id ) ) {
			// All-in-One SEO Pack (old, vestigial).
			$keywords = genesis_get_custom_field( 'keywords', $post_id );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term     = get_queried_object();
		$keywords = get_term_meta( $term->term_id, 'keywords', true );
	} elseif ( is_post_type_archive() && genesis_has_post_type_archive_support() ) {
		$keywords = genesis_get_cpt_option( 'keywords' ) ? genesis_get_cpt_option( 'keywords' ) : '';
	} elseif ( is_author() ) {
		$keywords = get_the_author_meta( 'meta_keywords', (int) get_query_var( 'author' ) );
	}

	/**
	 * Filter the content for the keywords meta tag.
	 *
	 * @since 2.4.0
	 *
	 * @param string $keywords Meta keywords string.
	 */
	$keywords = apply_filters( 'genesis_get_seo_meta_keywords', $keywords );

	return trim( $keywords );
}

/**
 * Determine the `noindex`, `nofollow`, `noodp`, `noydir`, `noarchive` robots meta code for the current context.
 *
 * @since 2.4.0
 *
 * @global WP_Query $wp_query Query object.
 *
 * @return string String for `content` attribute of `robots` meta tag.
 */
function genesis_get_robots_meta_content() {

	global $wp_query;
	$post_id = null;

	// Defaults.
	$directives = array(
		'noindex'   => '',
		'nofollow'  => '',
		'noarchive' => genesis_get_seo_option( 'noarchive' ) ? 'noarchive' : '',
		'noodp'     => genesis_get_seo_option( 'noodp' ) ? 'noodp' : '',
		'noydir'    => genesis_get_seo_option( 'noydir' ) ? 'noydir' : '',
	);

	// Check root page SEO settings, set noindex, nofollow and noarchive.
	if ( genesis_is_root_page() ) {
		$directives['noindex']   = genesis_get_seo_option( 'home_noindex' ) ? 'noindex' : $directives['noindex'];
		$directives['nofollow']  = genesis_get_seo_option( 'home_nofollow' ) ? 'nofollow' : $directives['nofollow'];
		$directives['noarchive'] = genesis_get_seo_option( 'home_noarchive' ) ? 'noarchive' : $directives['noarchive'];
	}

	// When the page is set as the Posts Page in WordPress core, use the $post_id of the page when loading SEO values.
	if ( is_home() && get_option( 'page_for_posts' ) && get_queried_object_id() ) {
		$post_id = get_option( 'page_for_posts' );
	}

	if ( null !== $post_id || is_singular() ) {
		$directives['noindex']   = genesis_get_custom_field( '_genesis_noindex', $post_id ) ? 'noindex' : $directives['noindex'];
		$directives['nofollow']  = genesis_get_custom_field( '_genesis_nofollow', $post_id ) ? 'nofollow' : $directives['nofollow'];
		$directives['noarchive'] = genesis_get_custom_field( '_genesis_noarchive', $post_id ) ? 'noarchive' : $directives['noarchive'];
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = $wp_query->get_queried_object();

		$directives['noindex']   = get_term_meta( $term->term_id, 'noindex', true ) ? 'noindex' : $directives['noindex'];
		$directives['nofollow']  = get_term_meta( $term->term_id, 'nofollow', true ) ? 'nofollow' : $directives['nofollow'];
		$directives['noarchive'] = get_term_meta( $term->term_id, 'noarchive', true ) ? 'noarchive' : $directives['noarchive'];

		if ( is_category() ) {
			$directives['noindex']   = genesis_get_seo_option( 'noindex_cat_archive' ) ? 'noindex' : $directives['noindex'];
			$directives['noarchive'] = genesis_get_seo_option( 'noarchive_cat_archive' ) ? 'noarchive' : $directives['noarchive'];
		} elseif ( is_tag() ) {
			$directives['noindex']   = genesis_get_seo_option( 'noindex_tag_archive' ) ? 'noindex' : $directives['noindex'];
			$directives['noarchive'] = genesis_get_seo_option( 'noarchive_tag_archive' ) ? 'noarchive' : $directives['noarchive'];
		}
	} elseif ( is_post_type_archive() && genesis_has_post_type_archive_support() ) {
		$directives['noindex']   = genesis_get_cpt_option( 'noindex' ) ? 'noindex' : $directives['noindex'];
		$directives['nofollow']  = genesis_get_cpt_option( 'nofollow' ) ? 'nofollow' : $directives['nofollow'];
		$directives['noarchive'] = genesis_get_cpt_option( 'noarchive' ) ? 'noarchive' : $directives['noarchive'];
	} elseif ( is_author() ) {
		$directives['noindex']   = get_the_author_meta( 'noindex', (int) get_query_var( 'author' ) ) ? 'noindex' : $directives['noindex'];
		$directives['nofollow']  = get_the_author_meta( 'nofollow', (int) get_query_var( 'author' ) ) ? 'nofollow' : $directives['nofollow'];
		$directives['noarchive'] = get_the_author_meta( 'noarchive', (int) get_query_var( 'author' ) ) ? 'noarchive' : $directives['noarchive'];

		$directives['noindex']   = genesis_get_seo_option( 'noindex_author_archive' ) ? 'noindex' : $directives['noindex'];
		$directives['noarchive'] = genesis_get_seo_option( 'noarchive_author_archive' ) ? 'noarchive' : $directives['noarchive'];
	} elseif ( is_date() ) {
		$directives['noindex']   = genesis_get_seo_option( 'noindex_date_archive' ) ? 'noindex' : $directives['noindex'];
		$directives['noarchive'] = genesis_get_seo_option( 'noarchive_date_archive' ) ? 'noarchive' : $directives['noarchive'];
	} elseif ( is_search() ) {
		$directives['noindex']   = genesis_get_seo_option( 'noindex_search_archive' ) ? 'noindex' : $directives['noindex'];
		$directives['noarchive'] = genesis_get_seo_option( 'noarchive_search_archive' ) ? 'noarchive' : $directives['noarchive'];
	}

	/**
	 * Filter the array of directives for the robots meta tag.
	 *
	 * @since 2.4.0
	 *
	 * @param array $directives May contain keys for `noindex`, `nofollow`, `noodp`, `noydir`, `noarchive`.
	 */
	$directives = apply_filters( 'genesis_get_robots_meta_content', $directives );

	// Strip empty array items.
	$directives = array_filter( $directives );

	return implode( ',', $directives );
}

add_action( 'wp_head', 'genesis_load_favicon' );
/**
 * Return favicon URL.
 *
 * Falls back to Genesis theme favicon.
 *
 * URL to favicon is filtered via `genesis_favicon_url` before being echoed.
 *
 * @since 2.4.0
 *
 * @return string Path to favicon.
 */
function genesis_get_favicon_url() {

	/**
	 * Filter to allow child theme to short-circuit this function.
	 *
	 * @since 1.1.2
	 *
	 * @param bool $favicon `false`.
	 */
	$pre = apply_filters( 'genesis_pre_load_favicon', false );

	if ( $pre !== false ) {
		$favicon = $pre;
	} elseif ( file_exists( CHILD_DIR . '/images/favicon.ico' ) ) {
		$favicon = CHILD_URL . '/images/favicon.ico';
	} elseif ( file_exists( CHILD_DIR . '/images/favicon.gif' ) ) {
		$favicon = CHILD_URL . '/images/favicon.gif';
	} elseif ( file_exists( CHILD_DIR . '/images/favicon.png' ) ) {
		$favicon = CHILD_URL . '/images/favicon.png';
	} elseif ( file_exists( CHILD_DIR . '/images/favicon.jpg' ) ) {
		$favicon = CHILD_URL . '/images/favicon.jpg';
	} else {
		$favicon = GENESIS_IMAGES_URL . '/favicon.ico';
	}

	/**
	 * Filter the favicon URL.
	 *
	 * @since 0.2.0
	 *
	 * @param string $favicon Favicon URL.
	 */
	$favicon = apply_filters( 'genesis_favicon_url', $favicon );

	return trim( $favicon );
}
