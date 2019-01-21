<?php
/**
 * Recent Posts by Category Widgets
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'warrior_recent_posts_by_category_widget' );

// Register our widget
function warrior_recent_posts_by_category_widget() {
	register_widget( 'Warrior_Recent_Posts_by_category' );
}

// Warrior Latest Video Widget
class Warrior_Recent_Posts_by_category extends WP_Widget {

	//  Setting up the widget
	function Warrior_Recent_Posts_by_category() {
		$widget_ops  = array( 'classname' => 'warrior_recent_posts_by_category', 'description' => __('Display knowledge base category with its latest posts.', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_recent_posts_by_category' );

		parent::__construct( 'warrior_recent_posts_by_category', __('HOME TOP: Category List', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $helper_option;
		
		extract( $args );

		$warrior_recent_posts_by_category_title = apply_filters( 'widget_title', empty( $instance['warrior_recent_posts_by_category_title'] ) ? __( 'Article Categories', 'helper' ) : $instance['warrior_recent_posts_by_category_title'], $instance, $this->id_base );
		$warrior_recent_posts_by_category_count = !empty( $instance['warrior_recent_posts_by_category_count'] ) ? absint( $instance['warrior_recent_posts_by_category_count'] ) : 5;
		$warrior_recent_posts_by_category_words_count = !empty( $instance['warrior_recent_posts_by_category_words_count'] ) ? absint( $instance['warrior_recent_posts_by_category_words_count'] ) : 30;
		$warrior_recent_posts_by_category_name = esc_attr($instance['warrior_recent_posts_by_category_name']);

		if ( !$warrior_recent_posts_by_category_count )
 			$warrior_recent_posts_by_category_count = 5;

			echo $before_widget;
?>		
		<div class="homepage-categories">
			<div class="container">
				<?php echo $before_title . $warrior_recent_posts_by_category_title . $after_title; ?>
		        
		        <div class="row row-2">
			        <?php
			        if( !empty($instance['warrior_recent_posts_by_category_name']) ) :	
					    foreach( (array)$instance['warrior_recent_posts_by_category_name'] as $catsid ) : 
					      	if( $catsid ) :
					      		$args = array(
									'type'                     => 'knowledge_base',
									'hide_empty'               => 0,
									'hierarchical'             => 0,
									'taxonomy'                 => 'kb_category',
									'pad_counts'               => false,
									'terms'					   => $catsid

								); 
					    		$mycats = get_categories( $args );	
							    $taxonomy = 'kb_category'; 
							    $term = get_term( $catsid, $taxonomy );	
							    $term_name = $term->name;
							    $term_slug = $term->slug;
							    $term_count = $term->count;
		            ?>
	            	<div class="column">	
						<div class="block article-list">
							<h4 class="widget-title"> <i class="typcn typcn-folder"></i> 
								<?php echo $term_name; ?><span class="article-count"><?php echo $term_count; ?> <?php _e('articles', 'helper'); ?></span>
							</h4>

							<?php
							// Load the posts
							$args = array(
								'post_type' => 'knowledge_base',
								'taxonomy' => 'kb_category',
								'term' => $term_slug,
								'post_status' => 'publish', 
								'showposts' => $warrior_recent_posts_by_category_count,
							);
							$wp_query = new WP_Query();
							$wp_query->query($args);
							?>

							<ul>
		                    <?php if( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
								<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
										<?php the_title(); ?>
									</a></h3>
								</li>
							<?php endwhile; else: ?>
								<?php _e('There\'s no post in this category.', 'helper'); ?>
							<?php endif; ?>
							</ul>
							<?php wp_reset_postdata(); ?>
						</div>
					</div>	
	                <?php endif; ?>
					
					<?php endforeach; ?>
					<?php else: echo _e('No category selected!', 'helper') ?>
					<?php endif; ?>
	            </div>
            </div>
		</div>	
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_recent_posts_by_category_title'] 		= strip_tags( $new_instance['warrior_recent_posts_by_category_title'] );
		$instance['warrior_recent_posts_by_category_count']  		= (int) $new_instance['warrior_recent_posts_by_category_count'];
		$instance['warrior_recent_posts_by_category_words_count']  	= (int) $new_instance['warrior_recent_posts_by_category_words_count'];
		$instance['warrior_recent_posts_by_category_name']			= esc_attr( $new_instance['warrior_recent_posts_by_category_name'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_recent_posts_by_category_title' => __('Article Categories', 'helper'), 'warrior_recent_posts_by_category_name' => '', 'warrior_recent_posts_by_category_count' => '5', 'warrior_recent_posts_by_category_words_count' => '30') );
		//Access the WordPress Categories via an Array
		$category_array = array();  
		$category_object = get_categories('taxonomy=kb_category&order=ASC&hide_empty=0');
		$category_array = array_cat_list_id(0, $category_object, $category_array, 0);
	?>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_recent_posts_by_category_title' ); ?>"><?php _e('Widget Title:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_recent_posts_by_category_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_recent_posts_by_category_title' ); ?>" value="<?php echo esc_attr( $instance['warrior_recent_posts_by_category_title'] ); ?>" />
        </p>
        <script type="text/javascript">
			jQuery(function($) {
			 	if ($('input#default_select_all_category').prop('checked') == true){ 
			       	$("select.kb_category_option").each(function(){
			            $("option#kb_category_option").attr("selected","selected");
			    	});
			    }
			    $('input#default_select_all_category').removeProp('checked');
			});
        </script>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_recent_posts_by_category_name' ); ?>"><?php _e('Category Name:', 'helper'); ?></label>
	    	<input type="checkbox" id="default_select_all_category" name="select_kb_category" value="1" style="visibility: hidden;" checked><br>
			<?php 
				printf ('<select multiple="multiple" name="%s[]" id="%s" class="widefat kb_category_option" size="15" style="margin-bottom:10px">',
	                $this->get_field_name('warrior_recent_posts_by_category_name'),
	                $this->get_field_id('warrior_recent_posts_by_category_name')
	            );
            foreach ($category_array as $id=>$category) {
                printf(
                    '<option value="%s" %s style="margin-bottom:2px;" id="kb_category_option">'.$category.'</option>',
                    $id,
                    is_array(esc_attr( $instance['warrior_recent_posts_by_category_name'] )) && in_array( $id, esc_attr( $instance['warrior_recent_posts_by_category_name'] )) ? 'selected="selected"' : '',
                    $id
                );
            }
            echo '</select>';
			?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_recent_posts_by_category_count' ); ?>"><?php _e('Number of posts to show:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_recent_posts_by_category_count' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_recent_posts_by_category_count' ); ?>" value="<?php echo esc_attr( $instance['warrior_recent_posts_by_category_count'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_recent_posts_by_category_words_count' ); ?>"><?php _e('Post Title Limiter', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_recent_posts_by_category_words_count' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_recent_posts_by_category_words_count' ); ?>" value="<?php echo esc_attr( $instance['warrior_recent_posts_by_category_words_count'] ); ?>" />
            <p><small><?php _e('The post title will be trim after reaching the number of characters defined.', 'helper'); ?></small></p>
        </p>

	<?php
	}
}
?>