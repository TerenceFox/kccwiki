<?php
/**
 * The Template for displaying forums.
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
            <?php get_template_part('includes/breadcrumb');  //display breadcrumb section ?>

            <div class="post-list">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post regular-pos'); ?> >
                        <div class="inner">
                            <h1 class="post-title"><?php the_title(); ?></h1>
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; else: _e('No forums available !', 'helper'); endif; ?>
            </div>   
        </div>

        <?php get_sidebar('forum'); ?>
    </div>  
</div>

<?php get_footer(); ?>