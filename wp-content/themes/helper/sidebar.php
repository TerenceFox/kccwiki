<?php
/**
 * The Sidebar containing the widget area
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>

<!-- Start : Sidebar Right Content -->
<?php if ( is_active_sidebar( 'warrior-right-sidebar' ) ) : ?>
<div id="rightcontent">
	<?php
		// Default Sidebar Widgets
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('warrior-right-sidebar') ) { 
			echo '<div class="block"><p class="no-widget">';
			_e('There\'s no widget assigned. You can start assigning widgets to "Right Sidebar" widget area from the <a href="'. admin_url('/widgets.php') .'">Widgets</a> page.', 'helper');
			echo '</p></div>';
		}
	?>
</div>
<?php endif; ?>
<!-- End : Sidebar Right Content -->