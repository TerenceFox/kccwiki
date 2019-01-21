<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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

            <header class="entry-header">
                <h1 class="post-title">
                <?php 
                    echo warrior_archive_title(); // get archive title
                ?>
                </h1>
            </header>

            <div class="post-list">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post regular-pos'); ?> >
                        <div class="inner">
                            
                             <?php
                            // Get featured image
                            if ( has_post_thumbnail() ) {
                                echo '<div class="thumbnail"><a href="'. get_permalink() .'" title="'. get_the_title() .'">';
                                the_post_thumbnail('rectangular-thumb');
                                echo '</a></div>';
                            }
                            ?>

                            <div class="<?php if ( has_post_thumbnail() ) { echo 'entry-content';} else { echo 'entry-content no-thumbnail';}?>">
                                <header class="entry-header">

                                    <span class="category">
                                    <?php
                                    if( get_post_type() == 'knowledge_base' ) : 
                                        $taxonomy   = 'kb_category'; 
                                        $terms      = get_the_terms( $post->ID, $taxonomy );

                                        if ( !empty($terms) ) :
                                    ?>
                                            <?php foreach ( $terms as $term ) : ?>
                                                <a href="<?php echo get_term_link($term->slug, $taxonomy); ?>"><?php echo $term->name; ?></a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php the_category(' '); ?>
                                    <?php endif; ?>
                                    </span>
                                    <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        
                                    <?php
                                    // display post meta
                                    if( $helper_option['general_display_post_meta'] ) {
                                        echo warrior_post_meta();
                                    }
                                    ?>
                                </header>
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; endif; ?>
            </div>

            <?php  get_template_part('includes/pagination');  //display pagination section ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>