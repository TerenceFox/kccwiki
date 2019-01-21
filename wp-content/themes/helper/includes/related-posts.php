<?php
/**
 * Template for displaying related posts.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>
<?php global $helper_option; ?>

<!-- Start : Related posts section -->
<div class="widget related-article-widget">
	<h4 class="widget-title"><?php _e('Related Articles', 'helper'); ?></h4>
	<ul>
	<?php
		global $post;
		if ( 'knowledge_base' == get_post_type() ) {
			$taxonomy = 'kb_category';
			$cats = wp_get_post_terms($post->ID, $taxonomy);
		}else{
			$taxonomy = 'category';
			$cats = wp_get_post_terms($post->ID, $taxonomy);
		}
		if ($cats) {
			$cats_ids = array();
			foreach($cats as $individual_cats) $cats_ids[] = $individual_cats->term_id;
			$args = array(
				'tax_query' => array(
			        array(
			            'taxonomy'  => $taxonomy,
			            'terms'     => $cats_ids,
			            'operator'  => 'IN'
			        )
			    ),
			    'post__not_in'          => array( $post->ID ),
			    'orderby' 				=> 'rand',
			    'posts_per_page'        => 4,
			    'ignore_sticky_posts'   => 1
			);
			$query = new wp_query( $args );

			if( $query->have_posts() ) {
	        	while ( $query->have_posts() ) {
	        		$query->the_post();
	        		echo '<li>';
	        			echo '<div class="thumbnail">';
	        				// Featured image
			                if ( has_post_thumbnail() ) {
			                    echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'">';
			                    the_post_thumbnail( 'thumbnail' );
			                    echo '</a>';
			                } else {
			                	echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'">';
			                	echo '<img src="http://placehold.it/150x150/333333/ffffff?text='. __('No Thumbnail', 'helper').'">';
			                	echo '</a>';
			                }
                        echo '</div>';
                        echo '<div class="detail">';
						echo '<h3><a href="'. get_the_permalink() .'">'. get_the_title() .'</a></h3>';
						echo '<p>'.wp_trim_words( get_the_excerpt(), 30, '...').'</p>';
                        echo '</div>';
					echo '</li>';
	         	}
	     	}
		}
	    wp_reset_postdata();
	?>
	</ul>
	<div class="clearfix"></div>
</div>
<!-- End : Related posts section -->