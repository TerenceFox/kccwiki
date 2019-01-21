<?php
/**
* List of theme support functions
*/
// Check if the function exist
if ( function_exists( 'add_theme_support' ) ){

	// Add post thumbnail feature
	add_theme_support( 'post-thumbnails' );
	add_image_size('blog-image', 260, 185, true); // blog thumbnail image
	add_image_size('partner-image', 200, 60, true); // partner thumbnail image
	add_image_size('related-image', 60, 60, true); // related post thumbnail image
	add_image_size('post-detail-image', 820, 400, true); // post detail image
	add_image_size('archive-thumb', 205, 205, true); // archive thumbnail image
	add_image_size('rectangular-thumb', 300, 220, true); // archive thumbnail image
	
	// Add WordPress navigation menus
	add_theme_support('nav-menus');
	register_nav_menus( array(
		'main-menu' => __( 'Main Menu', 'helper' ),
	) );

	// Add Title Tag Support
	add_theme_support( 'title-tag' );

	register_nav_menus( array(
		'footer-menu' => __( 'Footer Menu', 'helper' ),
	) );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Add custom background feature 
	add_theme_support( 'custom-background' );

	// Add custom header feature 
	add_theme_support( 'custom-header', array(
		'default-image'			 => '%s/images/header/header-5.jpg',
		'admin-head-callback'    => 'warrior_admin_header_style',
		'admin-preview-callback' => 'warrior_admin_header_image',
		'header-text'			 => false
	) );

	// Default custom headers
	register_default_headers( array(
		'wall' => array(
			'url'           => '%s/images/header/header-1.jpg',
			'thumbnail_url' => '%s/images/header/thumb-header-1.jpg',
		),
		'nature' => array(
			'url'           => '%s/images/header/header-2.jpg',
			'thumbnail_url' => '%s/images/header/thumb-header-2.jpg',
		),
		'house' => array(
			'url'           => '%s/images/header/header-3.jpg',
			'thumbnail_url' => '%s/images/header/thumb-header-3.jpg',
		),
		'shelf' => array(
			'url'           => '%s/images/header/header-4.jpg',
			'thumbnail_url' => '%s/images/header/thumb-header-4.jpg',
		),
		'beach' => array(
			'url'           => '%s/images/header/header-5.jpg',
			'thumbnail_url' => '%s/images/header/thumb-header-5.jpg',
		),
		'building' => array(
			'url'           => '%s/images/header/header-6.jpg',
			'thumbnail_url' => '%s/images/header/thumb-header-6.jpg',
		),
	) );
}

// Theme Localization
load_theme_textdomain('helper', get_template_directory().'/lang');

// Set maximum image width displayed in a single post or page
if ( ! isset( $content_width ) ) {
	$content_width = 825;
}
?>