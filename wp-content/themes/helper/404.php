<?php
/**
 * The Template for displaying 404 not found pages.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?> 
<?php get_header(); ?>

<div id="primary" class="content-area site-main detail-page" role="main">
    <div class="container">
        <div id="leftcontent">
            <?php 
            if( $helper_option['general_display_breadcrumb'] ) {
                get_template_part('includes/breadcrumb');  //display breadcrumb section
            }
            ?>

            <div class="post-list">
                <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post regular-pos'); ?> >
                    <div class="inner">
                        <h1 class="post-title"><?php _e( 'Page Not Found', 'helper' ); ?></h1>
                        <div class="entry-content">
                            <p><?php _e('The page you\'re looking for is not available. The page may have been deleted or the URL has changed. You can try searching the page with the search form.', 'helper');?></p>
                        </div>
                    </div>
                </article>
            </div>   
        </div>

        <?php get_sidebar(); ?> 
    </div>
</div>

<?php get_footer(); ?>