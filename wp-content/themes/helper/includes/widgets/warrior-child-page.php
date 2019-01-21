<?php
/**
 * Warrior Child Page Widget.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'warrior_child_page_widget' );

// Register our widget
function warrior_child_page_widget() {
	register_widget( 'Warrior_Child_Page' );
}

// Warrior Child Page Widget
class Warrior_Child_Page extends WP_Widget {

	//  Setting up the widget
	function Warrior_child_page() {
		$widget_ops  = array( 'classname' => 'warrior_child_page', 'description' => esc_html__('Warrior Child Page Widget', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_child_page' );

		parent::__construct( 'warrior_child_page', esc_html__('Warrior Child Page', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $shortname,$post;
		
		extract( $args );

		$parent = get_the_title($post->post_parent);
		$terms = get_the_terms( $post->post_parent , 'kb_category' );

		echo $before_widget;

		if($post->post_parent){
		foreach ( $terms as $term ) {
		echo $before_title . '<a class="parentpage-list" href="' .  get_term_link($term->name, 'kb_category') . '">' . $term->name . '</a>' . $after_title;
		}
		echo '<ul>';
		echo '<li><a href="' . get_permalink($post->post_parent) . '">' . $parent . '</a>';
		echo '<ul class="children">';
		$children = wp_list_pages('title_li=&child_of='. $post->post_parent .'&post_type=knowledge_base&echo=1');
		if ($children) {
			echo $children;
			echo '</ul>';
		}
		echo '</li></ul>';
		}else{
		echo $before_title . '<a class="parentpage-list" href="' . get_permalink($post->post_parent) . '">' . $parent . '</a>' . $after_title;
		echo '<ul>';
		$children = wp_list_pages('title_li=&child_of='. get_the_ID() .'&post_type=knowledge_base&echo=1');
		if ($children) {
		  echo $children;
		}
		echo '</ul>';

		}

		// if  (is_page() && $post->post_parent ) { 
		//   echo 'Parent : <a class="parentpage-list" href="' . get_permalink($post->post_parent) . '">' . $parent . '</a>';
		// } 
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_child_page_title']    = strip_tags( $new_instance['warrior_child_page_title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_child_page_title' => esc_html__(' Page Map', 'helper'), 'warrior_child_page_total' => '6', 'warrior_child_page_category' => array('')) );

	}
}
?>