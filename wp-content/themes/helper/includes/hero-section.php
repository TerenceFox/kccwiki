<?php
/**
 * Template for displaying feature Section.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>

<?php global $helper_option; ?>

<!-- Start: Hero Section -->
<section id="slider-section" class="homepage-section slider-section-parallax" style="background-image: url('<?php echo esc_url( get_header_image() ); ?>')">
	
	<div class="bg-opacity"></div>
	
			<!-- Start : Header -->
			<header id="main-header">
				<div class="container">
					<div id="logo">
						<?php if( $helper_option['logo_type'] == '1' ) : ?>
							<h2 class="site-title"><a href="<?php echo get_home_url(); ?>"><?php echo bloginfo('name'); ?></a></h2>
						<?php else: ?>
							<a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo ( $helper_option['logo_image'] ? esc_url( $helper_option['logo_image']['url'] ) : get_template_directory_uri().'/images/logo.png' ); ?>" alt="<?php get_bloginfo('name'); ?>" /></a>
						<?php endif; ?>
					</div>

					<nav id="main-menu" class="menu-navigation">
						<?php
							// Main menu
							if ( has_nav_menu( 'main-menu' ) ) {
								wp_nav_menu( array ( 'theme_location' => 'main-menu', 'container' => null, 'menu_class' => 'main-menu', 'depth' => 5 ) );
							}
						?>
					</nav>

					<div class="clearfix"></div>
				</div>
			</header>
			<!-- End : Header -->

	<div class="container">
		<!-- Start Home feature section -->
		<h1 class="site-title"><?php echo esc_attr( $helper_option['featured_title'] ); ?>
			<span class="subtitle"><?php echo esc_attr( $helper_option['featured_description'] ); ?></span>
		</h1>

		<?php the_widget('Warrior_Advanced_Search', 'warrior_advanced_search_title='); ?>
	</div>
</section>
<!-- End: Hero Section -->