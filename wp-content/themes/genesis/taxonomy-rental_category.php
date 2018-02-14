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
<img class="aligncenter wp-image-198 size-full" src="http://sapphireroadweddings.com/wp-content/uploads/2016/04/inventory.jpg" width="1600" height="600" />
<img class="aligncenter wp-image-314" src="http://sapphireroadweddings.com/wp-content/uploads/2016/02/n_inventory-300x47.jpg" alt="n_inventory" width="598" height="93" srcset="http://sapphireroadweddings.com/wp-content/uploads/2016/02/n_inventory-300x47.jpg 300w, http://sapphireroadweddings.com/wp-content/uploads/2016/02/n_inventory-768x119.jpg 768w, http://sapphireroadweddings.com/wp-content/uploads/2016/02/n_inventory-1024x159.jpg 1024w" sizes="(max-width: 598px) 100vw, 598px">
<h1 style="text-align: center; color: #555; font-family: 'Lato', sans-serif; font-size:30px;"><?php single_term_title(); ?></h1>
<div><?php the_archive_description(); ?></div>
<div id="rental-container">
    <?php while (have_posts()) : the_post();
        $post = get_post();
        $attachments = get_post_meta($post->ID, 'attachments', true);
        $firstAttachmentId = $attachments !== null ? json_decode($attachments)->my_attachments[0]->id : 0;
        ?>
        <a href="<?php echo get_permalink( $post->ID ); ?>" class='rental hvr-grow'>
            <div class="thumbnail-container">
                <img src="<?php echo wp_get_attachment_url($firstAttachmentId); ?>"/>
            </div>
            <h3><?php echo $post->post_title; ?></h3>
        </a>
    <?php endwhile; ?>
</div>
<?php
do_action('genesis_after_content_sidebar_wrap');
get_footer();
?>


