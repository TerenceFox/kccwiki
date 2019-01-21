<?php
/**
 * The template for displaying search results
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>

<?php
$get_ajax = isset($_GET['ajax']) ? $_GET['ajax'] : '';
$get_keyword = esc_attr( $_GET['s'] );

if( $get_ajax == 1 ) :
?>
<!-- Start : display list post on live search suggestion -->
    <div class="livesearch">
        <ul>
        <?php
        $search = array(
            'post_type' => array( 'post', 'knowledge_base' ),
            's'         => $get_keyword
        );
        query_posts($search);
        ?>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; else: ?>
            <li>
                <a href="#"><?php _e('Sorry, can\'t provide suggestion.', 'helper'); ?></a>
            </li>
        <?php endif; ?>
        </ul>   
    </div>
<!-- End : display list post on live search suggestion -->    
<?php elseif( empty($get_keyword) ) :
        global $wp_query;
        $wp_query->is_404 = true;
        $wp_query->is_single = false;
        $wp_query->is_page = false;

        include( get_query_template( '404' ) );
        exit(); //redirect if keyword search is empty
else: ?>

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
                <h1 class="post-title"><?php echo sprintf( __( 'Search Results for: "%s"', 'helper' ), esc_attr( get_search_query() ) ); ?></h1>
            </header>

            <div class="post-list">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post regular-pos'); ?> >
                        <div class="inner">
                            <div class="thumbnail">
                            <?php
                            // Get featured image
                            if ( has_post_thumbnail() ) {
                                echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'">';
                                the_post_thumbnail('rectangular-thumb');
                                echo '</a>';
                            } else {
                                echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'">';
                                echo '<img src="http://placehold.it/205x205/333333/?text='. __('No Thumbnail', 'helper').'" alt=""/>';
                                echo '</a>';
                            }
                            ?>
                            </div>

                            <div class="entry-content">
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

<?php endif; ?>