<?php
/**
 * Function to register widget areas
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_register_sidebars' ) ) {
	function warrior_register_sidebars() {

		// Sidebar Widget
		if ( function_exists('register_sidebar') ) {
			register_sidebar(array(
				'name' => __('Right Sidebar', 'helper'),
				'id' => 'warrior-right-sidebar',
				'description' => __('Widgets will be displayed in right sidebar.', 'helper'),
				'class' => '',
				'before_widget' => '<div id="widget-%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title"><span>',
				'after_title' => '</span></h4>',
			));
		}

		// Forum Sidebar Widget
		if ( function_exists('register_sidebar') ) {
			register_sidebar(array(
				'name' => __('Forum Sidebar', 'helper'),
				'id' => 'warrior-forum-sidebar',
				'description' => __('Widgets will be displayed in right sidebar.', 'helper'),
				'class' => '',
				'before_widget' => '<div id="widget-%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title"><span>',
				'after_title' => '</span></h4>',
			));
		}

		// Footer Widget
		if ( function_exists('register_sidebar') ) {
			register_sidebar(array(
				'name' => __('Footer', 'helper'),
				'id' => 'warrior-footer',
				'description' => __('Widgets will be displayed in footer area.', 'helper'),
				'class' => '',
				'before_widget' => '<div id="widget-%1$s" class="column widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title"><span>',
				'after_title' => '</span></h4>',
			));
		}
	}
}

/**
 * Function to remove default widgets after theme switch
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_removed_default_widgets' ) ) {
	function warrior_removed_default_widgets(){
		global $wp_registered_sidebars;
		$widgets = get_option('sidebars_widgets');
		foreach ($wp_registered_sidebars as $sidebar=>$value) {
			unset($widgets[$sidebar]);
		}
		update_option('sidebars_widgets', $widgets);
	}
}

if ( is_admin() && $pagenow == 'themes.php' && isset($_GET['activated'] ) )
	add_action( 'admin_init', 'warrior_removed_default_widgets' );

// Load Custom Widgets
include(get_template_directory() . '/includes/widgets/warrior-social-network.php');
include(get_template_directory() . '/includes/widgets/warrior-popular-posts.php');
include(get_template_directory() . '/includes/widgets/warrior-blog-with-thumbnail.php');
include(get_template_directory() . '/includes/widgets/warrior-knowledge-base-with-thumbnail.php');
include(get_template_directory() . '/includes/widgets/warrior-support-policy.php');
include(get_template_directory() . '/includes/widgets/warrior-advanced-search.php');
include(get_template_directory() . '/includes/widgets/warrior-category-posts.php');
include(get_template_directory() . '/includes/widgets/warrior-child-page.php');
?>