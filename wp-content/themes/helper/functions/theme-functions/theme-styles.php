<?php
/**
 * Function to load JS & CSS files
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

if ( ! function_exists( 'warrior_enqueue_scripts' ) ) {
	function warrior_enqueue_scripts() {
		global $pagenow;
		global $helper_option;

		// Only load these scripts on frontend
		if( !is_admin() && $pagenow != 'wp-login.php' ) {

			// Load all Javascript files
			wp_enqueue_script('jquery');

			if ( is_singular() ) {
				wp_enqueue_script( 'comment-reply' );
			}

			wp_enqueue_script('flexslider', get_template_directory_uri() .'/js/jquery.flexslider-min.js', '', '2.2.2', true);
			wp_enqueue_script('mobilemenu', get_template_directory_uri() .'/js/jquery.mobilemenu.js', '', '1.1', true);
			wp_enqueue_script('superfish', get_template_directory_uri() .'/js/superfish.js', '', null, true);
			wp_enqueue_script('waypoints', get_template_directory_uri() .'/js/jquery.waypoints.min.js', '', '3.1.1', true);

			// Scripts for homepage
			wp_enqueue_script('animatenumber', get_template_directory_uri() .'/js/jquery.animateNumber.min.js', '', null, true);

			// Backstretch
			if ( $helper_option['general_display_backstretch'] ) {
				wp_enqueue_script('backstretch', get_template_directory_uri() .'/js/jquery.backstretch.min.js', '', null, true);
			}

			wp_enqueue_script('livesearch', get_template_directory_uri() .'/js/jquery.liveSearch.js', '', '2.0', true);
			wp_enqueue_script('slicknav', get_template_directory_uri() .'/js/jquery.slicknav.min.js', '', '1.0.2', true);
			wp_enqueue_script('functions', get_template_directory_uri() .'/js/functions.js', '', null, true);

			// Localize script
			wp_localize_script('functions', '_warrior', array( 
				'bg_header' => esc_url( get_header_image() ),
				'backstretch_status' => $helper_option['general_display_backstretch'],
				'sticky_text' => __('Sticky', 'helper'),
			));

			// Load all CSS files
			wp_enqueue_style('reset', get_stylesheet_directory_uri() .'/css/reset.css', array(), false, 'all');
			wp_enqueue_style('style', get_stylesheet_directory_uri() .'/style.css', array(), false, 'all');
			wp_enqueue_style('animate', get_stylesheet_directory_uri() .'/css/animate.min.css', array(), false, 'all');
			wp_enqueue_style('slicknav', get_stylesheet_directory_uri() .'/css/slicknav.css', array(), false, 'all');
			wp_enqueue_style('typicons', get_stylesheet_directory_uri() .'/css/typicons.min.css', array(), false, 'all');
			wp_enqueue_style('odometer-theme-min', get_stylesheet_directory_uri() .'/css/odometer-theme-minimal.css', array(), false, 'all');

			// Load CSS file for bbPress
			if( function_exists('is_bbpress') ) {
				if( is_bbpress() ) {
					wp_enqueue_style('bbpress-custom', get_stylesheet_directory_uri() .'/css/bbpress-custom.css', array(), '1.0.0', 'all');
				}
			}
			
			wp_enqueue_style('flexslider', get_stylesheet_directory_uri() .'/css/flexslider.css', array(), false, 'all');
			wp_enqueue_style('responsive', get_stylesheet_directory_uri() .'/css/responsive.css', array(), false, 'all');
			wp_enqueue_style('print', get_stylesheet_directory_uri() .'/css/print.css', array(), false, 'all');

			// Load custom CSS file
			wp_enqueue_style('custom', get_template_directory_uri() .'/custom.css', array(), null, 'screen');
		}
	}
}
add_action( 'wp_enqueue_scripts', 'warrior_enqueue_scripts' );

/**
 * Function to generate the several styles from theme options
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_add_styles_theme_options' ) ) {
	function warrior_add_styles_theme_options() {
		global $helper_option;
		?>
		<style type="text/css">
			nav#main-menu > ul > li.current-menu-item > a,
			nav#main-menu > ul > li.current-menu-ancestor > a,
			nav#main-menu > ul > li.current-menu-parent > a,
			nav#main-menu > ul > li.current_page_item > a,
			nav#main-menu > ul > li.current_page_ancestor > a,
			nav#main-menu > ul > li.current_page_parent > a {
				color: <?php echo esc_attr( $helper_option['main_menu_link_color']['hover'] ); ?>;
			}

			.primary-navigation .menu-item-has-children:hover > a:before {
    			border-color: transparent transparent <?php echo esc_attr( $helper_option['dropdown_menu_background_color']['background-color'] ); ?> transparent;
			}

			.simple-tab-nav .current a {
			  	color: <?php echo esc_attr( $helper_option['browse_topics_tab_link']['hover'] ); ?> !important;
			}

			.simple-tab-nav li.current:before {
				border-color: transparent transparent #fff transparent !important;
			}

			.simple-tab-nav li.current:after {
				border-color: transparent transparent <?php echo esc_attr( $helper_option['browse_topics_tab_content_border']['border-color'] ); ?> transparent !important;
			}
		</style>
		<?php
	}
}
add_action( 'wp_enqueue_scripts', 'warrior_add_styles_theme_options' );

/**
 * Function to load JS & CSS files on init
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_init_styles' ) ) {
	function warrior_init_styles () {
		add_editor_style( 'css/editor-style.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'warrior_init_styles' );


/**
 * Function to load JS & CSS files on wp-admin
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_enqueue_scripts_admin' ) ) {
	function warrior_enqueue_scripts_admin() {
		global $pagenow;

		if( $pagenow == 'widgets.php' ) {
			wp_enqueue_style( 'widgets', get_template_directory_uri() .'/css/widgets.css', array(), null, 'screen' );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'warrior_enqueue_scripts_admin' );

if ( ! function_exists( 'helper_vc_fonts' ) ) {
	function helper_vc_fonts( $fonts_list ) {
	    $poppins->font_family = 'Poppins';
	    $poppins->font_types = '300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal';
	    $poppins->font_styles = 'regular';
	    $poppins->font_family_description = esc_html_e( 'Select font family', 'helper' );
	    $poppins->font_style_description = esc_html_e( 'Select font styling', 'helper' );
	    $fonts_list[] = $poppins;

	    return $fonts_list;
	}
}
add_filter('vc_google_fonts_get_fonts_filter', 'helper_vc_fonts');
?>