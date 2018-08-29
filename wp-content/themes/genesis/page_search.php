<head>

<?php 

function isNotEmpty($input)
{
    $strTemp = $input;
    $strTemp = trim($strTemp);
    
    if($strTemp != '') //Also tried this "if(strlen($strTemp) > 0)"
    {
        return true;
    }
    
    return false;
}
if(isNotEmpty($_GET['s'])){?>
<title><?php echo $_GET['s'];?> - Search Rentals - Sapphire Road Weddings</title>
<?php
}else {
?> <title>Search Rentals- Sapphire Road Weddings</title> <?php 
}
?>

</head>
<?php
/*
 * Template Name: Search Page
 */
get_header();

?>

<form class="search-form" itemprop="potentialAction" itemscope=""
	itemtype="https://schema.org/SearchAction" method="get"
	action="<?php get_site_url(); ?>/rentals/search/" role="search">
	<meta itemprop="target" content="http://srw.local:8080/?s={s}">
	<input itemprop="query-input" type="search" name="s"
		placeholder="Search Rentals" value="<?php echo  $_GET['s']; ?>"> <input type="submit" value="Search">
</form>

<?php
// An array of arguments
$args = array(
    's' => $_GET['s']
);

// The Query
$the_query = new WP_Query($args);
?>

<div id="rental-container" class="rental-container-archive"
	style="margin-top: 50px;">
<?php
// The Loop
if ($the_query->have_posts() && isNotEmpty($_GET['s'])) {
    
    while ($the_query->have_posts()) :
        $the_query->the_post();
        $post = get_post();
        $attachments = get_post_meta($post->ID, 'attachments', true);
        $firstAttachmentId = $attachments !== null ? json_decode($attachments)->my_attachments[0]->id : 0;
        $image = wp_get_attachment_image($firstAttachmentId, 'small');
        ?>
        <div class="single-rental-container">
		<a style="display: block; margin-bottom: 20px;"
			class="single-rental hvr-reveal"
			href="<?php echo get_permalink($post->ID); ?>">
                <?php echo $image ?>
                <h3><?php echo $post->post_title; ?></h3>
		</a>
	</div>
    <?php
    
endwhile
    ;
} else {
    // no posts found
}
?>
</div>
<?php
/* Restore original Post Data */
wp_reset_postdata();
?>
<?php get_footer(); ?>

