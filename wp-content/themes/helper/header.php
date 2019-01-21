<?php
/**
 * The template for displaying header part.
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>
<?php global $helper_option; ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php echo ( $helper_option['appearance_favicon'] ? esc_url( $helper_option['appearance_favicon']['url'] ) : get_template_directory_uri().'/images/favicon.png' ); ?>" />
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<!-- Start: Header -->
<header id="masthead" class="site-header">
	<div class="header-main">
		<div class="container">
			<!-- Start : display site logo -->
			<?php if( $helper_option['appearance_logo_type'] == '1' ) : ?>
				<div id="logo" class="site-title"><h2><a href="<?php echo get_home_url(); ?>"><?php echo bloginfo('name'); ?></a></h2></div>
			<?php else: ?>
				<div id="logo" class="site-title"><a href="<?php echo get_home_url(); ?>" rel="home"><img src="<?php echo ( $helper_option['appearance_logo_image'] ? esc_url( $helper_option['appearance_logo_image']['url'] ) : get_template_directory_uri().'/images/logo.png' ); ?>" alt="<?php get_bloginfo('name'); ?>" /></a></div>
			<?php endif; ?>
			<!-- End : display site logo -->
			
			<?php if ( $helper_option['phone_number'] ) : ?>
				<div class="little-banner">
					<div class="thumbnail">
						<i class="typcn typcn-phone"></i>
					</div>
					<div class="detail">
						<span><?php _e('Call us now', 'helper') ?></span> <br />
						<label><?php echo esc_attr( $helper_option['phone_number'] ); ?></label>
					</div>
				</div>
			<?php endif; ?>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="menu-header default">
		<div class="container">
			<nav id="main-menu-fixed" class="site-navigation primary-navigation" role="navigation">
				<div class="mobile-menu"></div>
				<?php
				// Display Main menu section
				if ( has_nav_menu( 'main-menu' ) ) {
					wp_nav_menu( array ( 'theme_location' => 'main-menu', 'container' => null, 'menu_class' => 'main-menu', 'depth' => 5 ) );
				}
				?>
				<div class="clearfix"></div>
			</nav>
		</div>
	</div>

	<?php if( !is_page_template('page-home.php') ) : ?>
	<section id="slider-section" class="homepage-widget">
		<?php
			// display search form
			get_template_part('includes/advanced-search');
		?>
	</section>
	<?php endif; ?>
</header>
<!-- End: Header -->