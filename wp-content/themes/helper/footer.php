<?php
/**
 * The template for displaying footer section.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>
<?php global $helper_option; ?>

<!-- Start: Footer -->
<footer id="colophone" class="site-footer" role="contentinfo">
	<?php if ( $helper_option['enable_foooter_widgets'] ) : ?>
		<section id="footer-widgets" class="homepage-widget">
			<div class="container">
				<div class="footer-widgets row row-3-column">
				<?php
					// Default Footer Widgets
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) { 
						echo '<div class="block"><p class="no-widget">';
						_e('There\'s no widget assigned. You can start assigning widgets to "Footer" widget area from the <a href="'. admin_url('/widgets.php') .'">Widgets</a> page.', 'helper');
						echo '</p></div>';
					}
				?>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<section id="footer-socials">
		<div class="container">
		<?php the_widget( 'warrior_social_network' ); ?> 
		</div>
	</section>
	<section id="footer-bottom">
		<div class="container">
			<div class="terms-copy">
				<?php printf( __( '&copy; %1$s %2$s  %3$s', 'helper' ), __('Copyright', 'helper'), date_i18n('Y', strtotime( get_the_date() ) ), get_bloginfo('name') ); ?> - <?php printf( __( 'Designed by %1$s', 'helper' ), '<a href="http://www.themewarrior.com" target="_blank">ThemeWarrior</a>' ); ?>
			</div>
			<nav id="footer-menu" class="site-navigation footer-navigation">
			<?php
				// Footer menu
				if ( has_nav_menu( 'footer-menu' ) ) {
					wp_nav_menu( array ( 'theme_location' => 'footer-menu', 'container' => null, 'menu_class' => 'footer-menu', 'depth' => 1 ) );
				}
			?>
			</nav>

			<div class="clearfix"></div>
		</div>
	</section>
</footer>
<!-- End: Footer -->

<?php
if( $helper_option['general_display_back_to_top'] ) {
    echo '<a id="scroll-top" href="#top"><span class="typcn typcn-arrow-up"></span></a>';  // display back to top section
}

// Load custom CSS from theme options
if( isset( $helper_option['custom_css'] ) ) {
    echo '<style type="text/css">';
    echo esc_attr( $helper_option['custom_css'] );
    echo '</style>';
}
?>

<?php wp_footer(); ?>
</body>
</html>