<?php
/**
 * Warrior Advanced Search
 *
 * This file contains Advanced Search widget
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'warrior_advanced_search' );

// Register our widget
function warrior_advanced_search() {
	register_widget( 'Warrior_Advanced_Search' );
}

// Warrior Latest Posts Widget
class Warrior_Advanced_Search extends WP_Widget {

	//  Setting up the widget
	function Warrior_Advanced_Search() {
		$widget_ops  = array( 'classname' => 'warrior_advanced_search', 'description' => __('Live search widget.', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_advanced_search' );

		parent::__construct( 'warrior_advanced_search', __('Live Search', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		
		$warrior_advanced_search_title = apply_filters( 'widget_title', empty( $instance['warrior_advanced_search_title'] ) ? '' : $instance['warrior_advanced_search_title'], $instance, $this->id_base );
			echo $before_widget;
?>
		<div id="search-widget">
			<div class="container">
				<div class="row search-widget">
					<form role="search" method="get" id="warrior-advanced-search" action="<?php echo esc_url(home_url( '/' )); ?>" >
						<div class="input-holder input-term">
							<input type="text" name="s" id="s" placeholder="<?php _e('Enter a search term...', 'helper'); ?>" class="input s" value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off"/>
							<i class="live-search-reset typcn typcn-backspace-outline"></i>
						</div>
						<button type="submit" class="button blue large searchbutton">
						<?php _e('Search', 'helper'); ?>
						</button>
					</form>
				</div>
			</div>
		</div>

			<script type="text/javascript">
			jQuery(document).ready(function() {
				'use strict';
				jQuery('#warrior-advanced-search #s').liveSearch({
					url: '<?php echo esc_url(home_url( "/?ajax=1&s=" )); ?>',
					loadingClass: 'loading',
				});
			});	
			</script>
<?php
			echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_advanced_search_title'] 	= strip_tags( $new_instance['warrior_advanced_search_title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_advanced_search_title' => '' ) );
?>
		<p><?php _e('This widget does not have any option.', 'helper'); ?></p>
<?php
	}
}
?>