<?php
/**
 * Template for displaying breadcrumbs section
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>
<?php global $helper_option; ?>

<!-- Start : Breadcrumbs section -->
<?php		
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb('<div class="breadcrumb">','</div>'); // display breadcrumb functions by wordpress seo plugin
	} elseif ( function_exists( 'warrior_the_breadcrumb' ) ) {
?>		
	<div class="breadcrumb">
		<ul>
			<?php echo warrior_the_breadcrumb(); // display breadcrumb functions ?>
		</ul>
	</div>
<?php
	}	
?>
<!-- End : Breadcrumbs section -->