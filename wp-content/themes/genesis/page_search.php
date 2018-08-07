<?php
/*
 * Template Name: Search Page
 */
get_header();
get_search_form();
?>

<?php
// An array of arguments
$args = array(
    's' => $_GET['s']
);

// The Query
$the_query = new WP_Query($args);

// The Loop
if ($the_query->have_posts()) {
    
    while ($the_query->have_posts()) : $the_query->the_post();
    ?> <h1><?php the_title(); ?></h1>  <?php
    endwhile;

} else {
    // no posts found
}
/* Restore original Post Data */
wp_reset_postdata();
?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

