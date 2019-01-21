<?php
/**
 * Template Name: Knowledge Base Landing Page
 * 
 * The Template for displaying knowledge base latest posts.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?> 
<?php get_header(); ?>

<div id="primary" class="content-area site-main detail-page page-area">
    <div class="container">
        <div id="leftcontent">
            <?php 
            if( $helper_option['general_display_breadcrumb'] ) {
                get_template_part('includes/breadcrumb');  //display breadcrumb section
            }
            ?>
            
            <div class="post-list">
                <?php while( $wp_query->have_posts() ) : the_post(); ?>
                    <header class="entry-header">
                        <h1 class="post-title"><?php the_title(); ?></h1>
                    </header>
                <?php endwhile; wp_reset_postdata(); ?>

                <?php
                // Query knowledge base posts
                if ( get_query_var('paged') ) {
                    $paged = get_query_var('paged');
                } elseif ( get_query_var('page') ) {
                    $paged = get_query_var('page');
                } else {
                    $paged = 1;
                }

                $args = array(
                    'post_type'     => 'knowledge_base',
                    'post_status'   => 'publish', 
                    'paged'         => $paged
                );

                $wp_query = new WP_Query();
                $wp_query->query($args);
                ?>

                <?php 
                if ( $wp_query->have_posts() ) :
                while( $wp_query->have_posts() ) : the_post(); 
                ?>
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
                                        $terms = get_the_terms( get_the_ID() , 'kb_category' );
                                        if( !empty($terms) ) {
                                            foreach ( $terms as $term ) :
                                              echo $term->name;
                                            endforeach;
                                        }
                                    ?>
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

            <?php wp_reset_postdata(); ?>
            <?php get_template_part('includes/pagination');  //display pagination section ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>