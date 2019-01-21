<?php
/**
 * Recent Posts Widgets
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'warrior_latest_blog_widget' );

// Register our widget
function warrior_latest_blog_widget() {
	register_widget( 'Warrior_Latest_Blog' );
}

// Warrior Latest Video Widget
class Warrior_Latest_Blog extends WP_Widget {

	//  Setting up the widget
	function Warrior_Latest_Blog() {
		$widget_ops  = array( 'classname' => 'latest-blog', 'description' => __('Display latest blog posts with featured image.', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_latest_blog' );

		parent::__construct( 'warrior_latest_blog', __('Latest Blog Posts', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $noticia_option;
		
		extract( $args );

		$warrior_latest_blog_title   		= apply_filters( 'widget_title', empty( $instance['warrior_latest_blog_title'] ) ? __( 'Recent Posts', 'helper' ) : $instance['warrior_latest_blog_title'], $instance, $this->id_base );
		$warrior_latest_blog_count 			= !empty( $instance['warrior_latest_blog_count'] ) ? absint( $instance['warrior_latest_blog_count'] ) : 5;
		$warrior_latest_blog_title_length 	= !empty( $instance['warrior_latest_blog_title_length'] ) ? absint( $instance['warrior_latest_blog_title_length'] ) : 5;


		if ( !$warrior_latest_blog_count )
 			$warrior_latest_blog_count = 5;

		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $warrior_latest_blog_count
		);

		$wp_query = new WP_Query();
		$wp_query->query( $args );

		if ( $wp_query->have_posts() ) :
?>
        <?php echo $before_widget; ?>
		<?php echo $before_title . $warrior_latest_blog_title . $after_title; ?>

		<div class="post-wrapper">
			<?php while( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('regular'); ?>>
					<div class="thumbnail square">
					<?php
					// Featured image
					if ( has_post_thumbnail() ) {
						echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'">';
						the_post_thumbnail('thumbnail');
						echo '</a>';
					} else {
						echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'">';
						echo '<img src="http://placehold.it/120x120/555555//ffffff?text='. __('No Thumbnail', 'helper').'" />';
						echo '</a>';
					}
					?>
					</div>
					<div class="detail">
						<h3 class="post-title">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo wp_trim_words( get_the_title(), $warrior_latest_blog_title_length, '...' ); ?></a>
						</h3>
						<?php warrior_author_post_meta(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php echo $after_widget; ?>
<?php
		endif;
		wp_reset_postdata();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_latest_blog_title'] 			= strip_tags( $new_instance['warrior_latest_blog_title'] );
		$instance['warrior_latest_blog_count'] 			= (int) $new_instance['warrior_latest_blog_count'];
		$instance['warrior_latest_blog_title_length'] 	= (int) $new_instance['warrior_latest_blog_title_length'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_latest_blog_title' => __('Recent Posts', 'helper'), 'warrior_latest_blog_count' => '5', 'warrior_latest_blog_title_length' => '5', 'warrior_recent_excerpt_count' => '20') );
	?>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_latest_blog_title' ); ?>"><?php _e('Widget Title:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_latest_blog_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_latest_blog_title' ); ?>" value="<?php echo esc_attr( $instance['warrior_latest_blog_title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_latest_blog_count' ); ?>"><?php _e('Number of Posts:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_latest_blog_count' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_latest_blog_count' ); ?>" value="<?php echo esc_attr( $instance['warrior_latest_blog_count'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_latest_blog_title_length' ); ?>"><?php _e('Max Post Title Length:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_latest_blog_title_length' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_latest_blog_title_length' ); ?>" value="<?php echo esc_attr( $instance['warrior_latest_blog_title_length'] ); ?>" />
            <p><small><?php _e('The post title will be trim after reaching the number of characters defined.', 'helper'); ?></small></p>
        </p>
	<?php
	}
}
?>