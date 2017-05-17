<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Templates
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

// This file handles single entries, but only exists for the sake of child theme forward compatibility.

get_header();

do_action('genesis_before_content_sidebar_wrap');

?>
<div id="rental-container">
    <?php while (have_posts()) : the_post();
        $post = get_post(); ?>
        <a href="<?php echo get_permalink( $post->ID ); ?>" class='rental'>
            <div class="thumbnail-container">
                <img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($post), 'post-thumbnail') ?>"/>
            </div>
            <h3><?php echo $post->post_title; ?></h3>
        </a>
    <?php endwhile; ?>
</div>
<?php
do_action('genesis_after_content_sidebar_wrap');
get_footer();
?>

