<?php
/**
 * The Sidebar containing the forum widget area
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

?>
<!-- Start : Forum Sidebar Right Content -->
<?php if ( is_active_sidebar( 'warrior-forum-sidebar' ) ) : ?>
<div id="rightcontent">
	<?php
		// Default Sidebar Widgets
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('warrior-forum-sidebar') ) { 
			echo '<div class="block"><p class="no-widget">';
			_e('There\'s no widget assigned. You can start assigning widgets to "Forum Sidebar" widget area from the <a href="'. admin_url('/widgets.php') .'">Widgets</a> page.', 'helper');
			echo '</p></div>';
		}
	?>
</div>
<?php endif; ?>
<!-- End : Forum Sidebar Right Content -->