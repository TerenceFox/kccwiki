<?php
/**
 * Popular Posts Widgets
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'warrior_popular_posts_widget' );

// Register our widget
function warrior_popular_posts_widget() {
	register_widget( 'Warrior_popular_Posts' );
}

// Warrior Latest Video Widget
class Warrior_popular_Posts extends WP_Widget {

	//  Setting up the widget
	function Warrior_popular_Posts() {
		$widget_ops  = array( 'classname' => 'warrior_popular_posts', 'description' => __('Display popular articles from the knowlebase base post type.', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_popular_posts' );

		parent::__construct( 'warrior_popular_posts', __('Warrior Popular Knowledge Base', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $helper_option;
		
		extract( $args );

		$warrior_popular_posts_title 	= apply_filters( 'widget_title', empty( $instance['warrior_popular_posts_title'] ) ? __( 'Popular Articles', 'helper' ) : $instance['warrior_popular_posts_title'], $instance, $this->id_base );
		$warrior_popular_posts_count 	= !empty( $instance['warrior_popular_posts_count'] ) ? absint( $instance['warrior_popular_posts_count'] ) : 5;
		$warrior_popular_title_limiter 	= !empty( $instance['warrior_popular_title_limiter'] ) ? absint( $instance['warrior_popular_title_limiter'] ) : 10;

		if ( !$warrior_popular_posts_count )
 			$warrior_popular_posts_count = 5;
?>
        <?php echo $before_widget; ?>

        <div class="popular-articles">
		<?php echo $before_title . $warrior_popular_posts_title . $after_title; ?>
			<ul>
				<?php
					global $post;
					// Get the posts from database
					$args = array(
						'post_type' 			=> 'knowledge_base',
						'post_status' 			=> 'publish',
						'ignore_sticky_posts' 	=> 1,
						'meta_key' 				=> 'post_views_count',
						'orderby' 				=> 'meta_value_num',
						'meta_query' => array(
							array(
								'key'  => 'post_views_count'
							),
						),	
						'order'					=>	'desc',
						'posts_per_page' 		=> $warrior_popular_posts_count
					);

					$wp_query = new WP_Query();
					$wp_query->query( $args );
				       
				    if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();  
			    ?>
					<li>
						<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), $warrior_popular_title_limiter .' ...' ); ?></a></h3>
					</li>
				<?php endwhile; else: _e('not have popular posts !', 'helper'); endif; ?>
			</ul>	
		</div>

		<?php echo $after_widget; ?>
<?php
		wp_reset_postdata();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_popular_posts_title'] 		= strip_tags( $new_instance['warrior_popular_posts_title'] );
		$instance['warrior_popular_posts_count']  		= (int) $new_instance['warrior_popular_posts_count'];
		$instance['warrior_popular_title_limiter']  	= (int) $new_instance['warrior_popular_title_limiter'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_popular_posts_title' => __('Popular Articles', 'helper'), 'warrior_popular_posts_count' => '5', 'warrior_popular_title_limiter' => '10') );
	?>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_popular_posts_title' ); ?>"><?php _e('Widget Title:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_popular_posts_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_popular_posts_title' ); ?>" value="<?php echo esc_attr( $instance['warrior_popular_posts_title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_popular_posts_count' ); ?>"><?php _e('Number of posts to show:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_popular_posts_count' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_popular_posts_count' ); ?>" value="<?php echo esc_attr( $instance['warrior_popular_posts_count'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_popular_title_limiter' ); ?>"><?php _e('Post Title Limiter', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_popular_title_limiter' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_popular_title_limiter' ); ?>" value="<?php echo esc_attr( $instance['warrior_popular_title_limiter'] ); ?>" />
            <p><small><?php _e('The post title will be trim after reaching the number of characters defined.', 'helper'); ?></small></p>
        </p>
	<?php
	}
}
?>