<?php
/**
 * The Template for displaying pages.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?> 
<?php get_header(); ?>

<div id="primary" class="content-area site-main detail-page page-area" role="main">
    <div class="container">
        <div id="leftcontent">
            <?php 
            if( $helper_option['general_display_breadcrumb'] ) {
                get_template_part('includes/breadcrumb');  //display breadcrumb section
            }
            ?>
            
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post regular-pos'); ?> >
                    <div class="inner">
                        <header class="entry-header">
                            <h1 class="post-title"><?php the_title(); ?></h1>
                        </header>
                        
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>