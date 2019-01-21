<?php
/**
 * Warrior Support Policy Widgets
 *
 * @package WordPress
 * @subpackage Hospitalplus
 * @since Hospitalplus 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'warrior_support_policy_widget' );

// Register our widget
function warrior_support_policy_widget() {
	register_widget( 'Warrior_Support_Policy_Posts' );
}

// Warrior Support policy Widget
class Warrior_Support_Policy_Posts extends WP_Widget {

	//  Setting up the widget
	function Warrior_Support_Policy_Posts() {
		$widget_ops  = array( 'classname' => 'warrior_support_policy', 'description' => __('Display Support Policy.', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_support_policy' );

		parent::__construct( 'warrior_support_policy', __('Warrior Support Policy', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $hospitalplus_option;
		
		extract( $args );

		$warrior_support_policy_title 				= apply_filters( 'widget_title', empty( $instance['warrior_support_policy_title'] ) ? __( 'Support Policy', 'helper' ) : $instance['warrior_support_policy_title'], $instance, $this->id_base );
		$warrior_support_policy_text_description 	= esc_attr( $instance['warrior_support_policy_text_description'] );
		$warrior_support_policy_contact 			= esc_attr( $instance['warrior_support_policy_contact'] );
?>
        <?php echo $before_widget; ?>

        <div class="support-widget">
			<h4 class="widget-title"><?php echo $warrior_support_policy_title; ?></h4>
			<p><?php echo $warrior_support_policy_text_description; ?></p>
			<?php echo wpautop( $warrior_support_policy_contact ); ?>
		</div>
		
		<?php echo $after_widget; ?>
<?php
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_support_policy_title'] 				= esc_attr( $new_instance['warrior_support_policy_title'] );
		$instance['warrior_support_policy_text_description'] 	= esc_attr( $new_instance['warrior_support_policy_text_description'] );
		$instance['warrior_support_policy_contact'] 			= esc_attr( $new_instance['warrior_support_policy_contact'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_support_policy_title' => __('Support Policy', 'helper'), 'warrior_support_policy_text_description' => '', 'warrior_support_policy_contact' => '' ) );
		$warrior_support_policy_contact = format_to_edit($instance['warrior_support_policy_contact']);
	?>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_support_policy_title' ); ?>"><?php _e('Widget Title:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_support_policy_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_support_policy_title' ); ?>" value="<?php echo esc_attr( $instance['warrior_support_policy_title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_support_policy_text_description' ); ?>"><?php _e('Description:', 'helper'); ?></label>
            <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('warrior_support_policy_text_description'); ?>" name="<?php echo $this->get_field_name('warrior_support_policy_text_description'); ?>"><?php echo esc_attr( $instance['warrior_support_policy_text_description'] ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_support_policy_contact' ); ?>"><?php _e('Contact Info:', 'helper'); ?></label>
            <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('warrior_support_policy_contact'); ?>" name="<?php echo $this->get_field_name('warrior_support_policy_contact'); ?>"><?php echo esc_attr($warrior_support_policy_contact); ?></textarea>
        </p>
	<?php
	}
}
?>