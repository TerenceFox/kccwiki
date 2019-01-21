<?php
/**
 * Social Network Widgets
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

// Widgets
add_action( 'widgets_init', 'warrior_social_network_widget' );

// Register our widget
function warrior_social_network_widget() {
	register_widget( 'warrior_social_network' );
}

// Warrior Abou the Couple Widget
class warrior_social_network extends WP_Widget {

	//  Setting up the widget
	function warrior_social_network() {
		$widget_ops  = array( 'classname' => 'social_network', 'description' => __('Display social network profile urls.', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_social_network' );

		parent::__construct( 'warrior_social_network', __('Warrior Social Network', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $helper_option;
		extract( $args );
		$warrior_social_network_title = apply_filters( 'widget_title', empty( $instance['warrior_social_network_title'] ) ? __( 'Social', 'helper' ) : $instance['warrior_social_network_title'], $instance, $this->id_base );
		echo $before_widget;
?>
		<div class="block">
			<?php echo $before_title . $warrior_social_network_title . $after_title; ?>
			<div class="social-links">
				<ul>
					<?php if( !empty( $helper_option['url_facebook'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_facebook'] ); ?>" target="_blank"><i class="typcn typcn-social-facebook"></i> <span><?php _e('Facebook', 'helper'); ?></span></a></li>
					<?php endif; ?>

					<?php if( !empty( $helper_option['url_twitter'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_twitter'] ); ?>" target="_blank"><i class="typcn typcn-social-twitter"></i> <span><?php _e('Twitter', 'helper'); ?></span></a></li>
					<?php endif; ?>

					<?php if( !empty( $helper_option['url_gplus'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_gplus'] ); ?>" target="_blank"><i class="typcn typcn-social-google-plus"></i> <span><?php _e('Google+', 'helper'); ?></span></a></li>
					<?php endif; ?>

					<?php if( !empty( $helper_option['url_instagram'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_instagram'] ); ?>" target="_blank"><i class="typcn typcn-social-instagram"></i> <span><?php _e('Instagram', 'helper'); ?></span></a></li>
					<?php endif; ?>

					<?php if( !empty( $helper_option['url_pinterest'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_pinterest'] ); ?>" target="_blank"><i class="typcn typcn-social-pinterest"></i> <span><?php _e('Pinterest', 'helper'); ?></span></a></li>
					<?php endif; ?>

					<?php if( !empty( $helper_option['url_youtube'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_youtube'] ); ?>" target="_blank"><i class="typcn typcn-social-youtube"></i> <span><?php _e('Youtube', 'helper'); ?></span></a></li>
					<?php endif; ?>

					<?php if( !empty( $helper_option['url_vimeo'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_vimeo'] ); ?>" target="_blank"><i class="typcn typcn-social-vimeo"></i> <span><?php _e('Vimeo', 'helper'); ?></span></a></li>
					<?php endif; ?>

					<?php if( !empty( $helper_option['url_github'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_github'] ); ?>" target="_blank"><i class="typcn typcn-social-github"></i> <span><?php _e('Github', 'helper'); ?></span></a></li>
					<?php endif; ?>

					<?php if( !empty( $helper_option['url_linkedin'] ) ) : ?>
						<li><a href="<?php echo esc_url( $helper_option['url_linkedin'] ); ?>" target="_blank"><i class="typcn typcn-social-linkedin"></i> <span><?php _e('LinkedIn', 'helper'); ?></span></a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
<?php
	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_social_network_title']	= esc_attr( $new_instance['warrior_social_network_title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_social_network_title' => __('Social', 'helper') ) );
	?>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_social_network_title' ); ?>"><?php _e('Widget Title:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_social_network_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_social_network_title' ); ?>" value="<?php echo esc_attr( $instance['warrior_social_network_title'] ); ?>" />
        </p>
		<p><?php printf( __('The data taken from <a href="%s" target="_blank">Theme Options</a>.', 'helper'), admin_url('admin.php?page=warriorpanel&tab=4') ); ?></p>
	<?php
	}
}
?>