<?php
/**
 * The template for displaying Author pages
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>
<?php get_header(); ?>

<?php
$current_author = ( get_query_var('author_name') ) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
?>

<div id="primary" class="content-area site-main detail-page" role="main">
    <div class="container">
        <div id="leftcontent">
            <?php get_template_part('includes/breadcrumb');  //display breadcrumb section ?>

            <div class="author-info-box">
                <div class="thumbnail">
                    <?php echo get_avatar( $post->post_author, '100' ); ?>
                </div>

                <div class="author-detail">
                    <h4><?php echo $current_author->display_name; ?></h4>
                    <?php echo wpautop( get_the_author_meta('description', $post->post_author) ); ?>    
                    <br />
                </div>
                <div class="clearfix"></div>
            </div>
            
            <header class="entry-header">
                <h3 class="post-title"><?php echo sprintf(__('All Posts from %s', 'helper'), $current_author->display_name ); ?></h3>
            </header>

            <div class="post-list">
                <?php // the loop
                if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
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

            <?php get_template_part('includes/pagination');  //display pagination section ?>    
        </div>

        <?php get_sidebar(); ?>
    </div>  
</div>

<?php get_footer(); ?>