<?php
/**
 * List of files inclusion and functions
 * 
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
	
$themename = 'helper';
$version = wp_get_theme()->Version;

// Include theme functions
require_once( get_template_directory() . '/functions/theme-functions/theme-widgets.php' ); // Load widgets
require_once( get_template_directory() . '/functions/theme-functions/theme-support.php' ); // Load theme support
require_once( get_template_directory() . '/functions/theme-functions/theme-functions.php' ); // Load custom functions
require_once( get_template_directory() . '/functions/theme-functions/theme-composer.php' ); // Load custom Visual Composer element
require_once( get_template_directory() . '/functions/theme-functions/theme-styles.php' ); // Load JavaScript, CSS & comment list layout
require_once( get_template_directory() . '/functions/class-tgm-plugin-activation.php' ); // Load TGM-Plugin-Activation

/**
 * Loads the Options Panel
 * 
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( file_exists( get_template_directory() . '/functions/theme-functions/theme-options.php' ) ) {
	require_once( get_template_directory() . '/functions/theme-functions/theme-options.php' );
}

/**
 * After setup theme
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
function warrior_theme_init(){
	add_action( 'widgets_init', 'warrior_register_sidebars' );
}
add_action( 'after_setup_theme', 'warrior_theme_init' );

/**
 * Required & recommended plugins
 * *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
function warrior_required_plugins() {
	$plugins = array(
		array(
			'name'			=> 'Redux Framework',
			'slug'			=> 'redux-framework',
			'required'		=> true,
		),
		array(
			'name'			=> 'Advanced Custom Fields',
			'slug'			=> 'advanced-custom-fields',
			'required'		=> true,
		),
		array(
			'name'			=> 'Helper Plugin',
			'version' 		=> '1.0.1',
			'slug'			=> 'helper-plugin',
			'source'		=> 'http://plugins.themewarrior.com/helper/helper-plugin.1.0.1.zip',
			'external_url'	=> '',
			'required'		=> true,
		),
		array(
			'name'			=> 'WPBakery Visual Composer',
			'version' 		=> '5.0.1',
			'slug'			=> 'js_composer',
			'source'		=> 'http://plugins.themewarrior.com/visual-composer/js_composer.5.0.1.zip',
			'external_url'	=> '',
			'required'		=> true,
		),
		array(
			'name'			=> 'bbPress',
			'slug'			=> 'bbpress',
			'required'		=> false,
		),
		array(
			'name'			=> 'Contact Form 7',
			'slug'			=> 'contact-form-7',
			'required'		=> true,
		),
		array(
			'name'			=> 'WordPress SEO by Yoast',
			'slug'			=> 'wordpress-seo',
			'required'		=> false,
		),
	);

	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
add_action('tgmpa_register', 'warrior_required_plugins');

/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', 'warrior_prefix_vcSetAsTheme' );
function warrior_prefix_vcSetAsTheme() {
    vc_set_as_theme();
}