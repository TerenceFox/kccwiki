<?php
/**
 * The Template for displaying all single posts.
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

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post regular-post post-detail'); ?> >
				<div class="inner">
					<header class="entry-header">
					<?php if ( is_single() ) : ?>
						<span class="category"><?php the_category(' '); ?></span>
						<h1 class="post-title">
							<?php the_title(); ?>
						</h1>
					<?php else: ?>
						<span class="category"><?php the_category(' '); ?></span>
						<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php endif; ?>
						<div class="single-post-meta post-meta">
							<ul>
								<li>
									<div class="author">
										<?php echo get_avatar( $post->post_author, '100' ); ?>

										<div class="meta-detail author-desc">
											<?php the_author_posts_link(); ?>
										</div>
									</div>
								</li>

								<li>
									<div class="post-date">
										<div class="meta-icon">
											<i class="typcn typcn-calendar"></i>
										</div>
										<div class="meta-detail">
											<span><?php echo date_i18n( 'M d, Y', strtotime( get_the_date('Y-m-d'), false ) ); ?> // <i><?php _e('Modified', 'helper'); ?> <?php echo human_time_diff( get_the_modified_date('U'), current_time('timestamp') ) . __(' ago', 'helper'); ?></i></span>
										</div>
									</div>
								</li>
								<li>
								<?php
								// display like button
								if( $helper_option['general_display_like_this_post'] ) {
									if( function_exists('zilla_likes') ) zilla_likes(); 
								}
								?>
								</li>
							</ul>
						</div>
					</header>

					<?php if ( has_post_thumbnail() ) : // check if the post has featured image ?>
					<div class="thumbnail">
					<?php
					// Get featured image
					the_post_thumbnail('post-detail-image');
					?>
					</div>
					<?php endif; ?>

					<div class="entry-content">
						<?php the_content(); ?>

						<?php if ( has_tag() ) : ?>
						<div class="article-tags">
							<?php the_tags('<span><i class="typcn typcn-tag"></i>'. __('Tags: ', 'helper').'</span>', ', ', '</span><span>'); ?>
						</div>
						<?php endif; ?>

						<?php wp_link_pages( array( 'before' => '<p class="page-links"><span class="page-links-title">' . __( 'Pages:', 'helper' ) . '</span>', 'after' => '</p>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>

						

						<?php
						// display share buttons
						if( $helper_option['general_display_share_post'] ) {
					        get_template_part('includes/share-buttons');
					    }
						?>
					</div>
				</div>

				<?php warrior_set_post_views( get_the_ID() ); //get post view count ?>
			</article>

			<?php
			// display related post section 
			if( $helper_option['general_display_related_posts'] ) {
		        get_template_part('includes/related-posts');
		    }
			?>
			<?php endwhile; endif; ?>

			<?php
			// display comments section
		    if( $helper_option['general_display_comments_posts'] ) {
		        comments_template( '', true );
		    }
		   	?>
		</div>

        <?php get_sidebar(); ?> 
        <div class="clearfix"></div>
    </div>
</div>

<?php get_footer(); ?>