<?php
/**
 * Warrior Category Posts
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

// Widgets
add_action( 'widgets_init', 'warrior_category_posts_widget' );

// Register our widget
function warrior_category_posts_widget() {
	register_widget( 'Warrior_Category_Posts' );
}

// Warrior Category Posts Widget
class Warrior_Category_Posts extends WP_Widget {

	//  Setting up the widget
	function Warrior_category_posts() {
		$widget_ops  = array( 'classname' => 'warrior_category_posts', 'description' => esc_html__('Warrior Category Posts Widget', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_category_posts' );

		parent::__construct( 'warrior_category_posts', esc_html__('Warrior Category Posts', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		// Only display on knowledge base detail page
		global $post;
		
		extract( $args );

		$warrior_category_posts_total     = absint($instance['warrior_category_posts_total']);
		$warrior_category_posts_order     = esc_attr($instance['warrior_category_posts_order']);
		$curr_id = get_the_ID();
		if(is_singular('knowledge_base')):
			$terms = get_the_terms( $post->ID , 'kb_category' );
			// Loop over each item since it's an array
			if ( $terms != null ){
				foreach( $terms as $term ) {
					// Print the name method from $term which is an OBJECT
					$get_curr_cat_slug = $term->slug ;
					$get_curr_cat_name = $term->name ;
					// Get rid of the other data stored in the object, since it's not needed
					unset($term);
				}
			}

			if ($warrior_category_posts_order == '1') {
				$args_recent_posts = array(
					'post_type' 			=> 'knowledge_base',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts' 	=> 1,
					'kb_category' 			=> $get_curr_cat_slug,
					'orderby'				=> 'date',
					'order'					=> 'ASC',
					'posts_per_page' 		=> absint( $warrior_category_posts_total )
				);
			} elseif ($warrior_category_posts_order == '2') {
				$args_recent_posts = array(
					'post_type' 			=> 'knowledge_base',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts' 	=> 1,
					'kb_category' 			=> $get_curr_cat_slug,
					'orderby'				=> 'date',
					'order'					=> 'DESC',
					'posts_per_page' 		=> absint( $warrior_category_posts_total )
				);
			} elseif ($warrior_category_posts_order == '3') {
				$args_recent_posts = array(
					'post_type' 			=> 'knowledge_base',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts' 	=> 1,
					'kb_category' 			=> $get_curr_cat_slug,
					'orderby'				=> 'title',
					'order'					=> 'ASC',
					'posts_per_page' 		=> absint( $warrior_category_posts_total )
				);
			} elseif ($warrior_category_posts_order == '4') {
				$args_recent_posts = array(
					'post_type' 			=> 'knowledge_base',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts' 	=> 1,
					'kb_category' 			=> $get_curr_cat_slug,
					'orderby'        		=> 'rand',
					'posts_per_page' 		=> absint( $warrior_category_posts_total )
				);
			} else {
				$args_recent_posts = array(
					'post_type' 			=> 'knowledge_base',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts' 	=> 1,
					'kb_category' 			=> $get_curr_cat_slug,
					'order'					=> 'DESC',
					'posts_per_page' 		=> absint( $warrior_category_posts_total )
				);
			}


			$warior_latest_recent_posts = new WP_Query();
			$warior_latest_recent_posts->query( $args_recent_posts );
			
			if ( $warior_latest_recent_posts->have_posts() ) :
				echo $before_widget; 
			?>
				<?php echo $before_title . esc_attr( $get_curr_cat_name) . $after_title; ?>
				<?php echo "<ul>"; ?>
				<?php while ( $warior_latest_recent_posts->have_posts() ) : $warior_latest_recent_posts->the_post(); ?>
				<?php
				$post_id = get_the_ID();
				if ($curr_id == $post_id) {
					$classes = 'current';
				} else {
					$classes = '';
				}
				?>
				<li class="<?php echo $classes; ?>">
					<h3 class="post-title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
				</li>
				<?php endwhile; 
				echo "</ul>";
				echo $after_widget;
			endif; 
			wp_reset_postdata();
		endif;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_category_posts_total']    = strip_tags( $new_instance['warrior_category_posts_total'] );
		$instance['warrior_category_posts_order']    = strip_tags( $new_instance['warrior_category_posts_order'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_category_posts_total' => '10', 'warrior_category_posts_order' => '0'));

		//Access the WordPress Categories via an Array
		$categories_array = array();  
		$categories_obj = get_categories('hierarchical=true&orderby=name');
		foreach ( $categories_obj as $category_obj ) {
			$categories_array[] = array('id' => $category_obj->cat_ID, 'name' => $category_obj->cat_name);
		}
	?>
		<p><?php esc_html_e( 'This widget will only be displayed on Knowledge Base detail page.', 'helper' ); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'warrior_category_posts_total' ); ?>"><?php esc_html_e('Number of Category Posts to be Displayed:', 'helper'); ?></label>
			<input id="<?php echo $this->get_field_id( 'warrior_category_posts_total' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_category_posts_total' ); ?>" value="<?php echo $instance['warrior_category_posts_total']; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'warrior_category_posts_order' ); ?>"><?php esc_html_e('Post Order By:', 'helper'); ?></label>
            <?php $layout = $instance['warrior_category_posts_order']; ?>
            <select class='widefat' id="<?php echo $this->get_field_id('warrior_category_posts_order'); ?>" name="<?php echo $this->get_field_name('warrior_category_posts_order'); ?>" type="text">
			    <option value='0'<?php echo ($layout =='0')?'selected':''; ?>> <?php esc_html_e('Select post order by', 'helper'); ?> </option>
			    <option value='1'<?php echo ($layout =='1')?'selected':''; ?>> <?php esc_html_e('Date (Ascending)', 'helper'); ?> </option>
			    <option value='2'<?php echo ($layout =='2')?'selected':''; ?>> <?php esc_html_e('Date (Descending)', 'helper'); ?> </option> 
			    <option value='3'<?php echo ($layout =='3')?'selected':''; ?>> <?php esc_html_e('Title', 'helper'); ?> </option> 
			    <option value='4'<?php echo ($layout =='4')?'selected':''; ?>> <?php esc_html_e('Random', 'helper'); ?> </option> 
			</select>
        </p>
	<?php
	}
}
?>