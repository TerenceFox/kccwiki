<?php
/**
 * Template for displaying author bio.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>

<?php global $helper_option; ?>

<!-- Start : Author bio section -->
<div class="post-meta">
	<div class="author">
		<?php echo get_avatar( $post->post_author, '100' ); ?>
		<h4><?php the_author(); ?></h4>

		<?php if( is_single() ) :?>
		<div class="author-desc">
			<?php echo wpautop( wp_trim_words( get_the_author_meta('description', $post->post_author), 10, '...') ); ?>
			<p class="view-author"><a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>"><?php _e('+ Read more', 'helper'); ?></a></p>
		</div>
		<?php endif; ?>
	</div>

	<div class="category-tags">
		<div class="article-categories">
        <?php
		if( get_post_type() == 'knowledge_base' ) : 
			$taxonomy 	= 'kb_category'; 
	    	$terms 		= get_the_terms( $post->ID, $taxonomy );

			if ( !empty($terms) ) :
		?>
				<h4><?php _e('Category', 'helper'); ?></h4>
		        <?php foreach ( $terms as $term ) : ?>
		        	<i class="typcn typcn-folder"></i><a href="<?php echo esc_url( get_term_link($term->slug, $taxonomy) ); ?>"><?php echo $term->name; ?></a>
		        <?php endforeach; ?>
	        <?php endif; ?>
		<?php else: ?>
			<h4><?php _e('Category', 'helper'); ?></h4>
			<i class="typcn typcn-folder"></i><?php the_category(', ') ?>
		<?php endif; ?>
		</div>

		<?php if( $helper_option['general_display_tags_posts'] && is_single() ) : ?>
			<div class="article-tags">
		        <?php
				if( get_post_type() == 'knowledge_base' ) : 
					$taxonomy 	= 'kb_tag'; 
			    	$terms 		= get_the_terms( $post->ID, $taxonomy );

					if ( !empty($terms) ) :
				?>
						<h4><?php _e('Tags', 'helper'); ?></h4>
				        <?php foreach ( $terms as $term ) : ?>
				        	<a href="<?php echo esc_url( get_term_link($term->slug, $taxonomy) ); ?>"><?php echo '#'. $term->name; ?></a>
				        <?php endforeach; ?>
			        <?php endif; ?>
				<?php else: ?>
					<?php if ( has_tag() ) : ?>
						<h4><?php _e('Tags', 'helper'); ?></h4>
						<i class="typcn typcn-bookmark"></i><?php the_tags(', ') ?>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if( is_single() ) : ?>
		<div class="post-view">
	        <i class="typcn typcn-eye-outline"></i><?php printf( warrior_get_post_views($post->ID) ); ?>
		</div>
	<?php endif; ?>

	
	<?php if( $helper_option['general_display_print_this_post'] && is_single() ) : ?>
		<div class="print">
			<div class="print-article">
				<a href="#" onclick="window.print();return false;" class="button"><i class="typcn typcn-printer"></i><?php _e('Print', 'helper'); ?></a>
			</div>
		</div>
	<?php endif; ?>
</div>
<!-- End : Author bio section -->