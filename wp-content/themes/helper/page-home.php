<?php
/**
 * Template Name: Homepage
 * 
 * The homepage template file.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?> 
<?php get_header(); ?>

<div id="primary" class="content-area site-main" role="main">
    <div class="container">
    <?php // The loop
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                the_content();
            }
        }
    ?>
    </div>
</div>

<?php get_footer(); ?>