<?php
/**
 * Function Visual Composer Extend (Advanced Search)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if( !function_exists('warrior_vc_advanced_search_shortcode') ) {
	function warrior_vc_advanced_search_shortcode( $atts ) {
		global $helper_option;
	    extract( shortcode_atts( array(
	    	'type'			 			=> 'in_container',
	        'advanced_search_title' 	=> '',
	        'el_class' 					=> '',
	        'css' 						=> ''
	    ), $atts ) );

	    // wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_script( 'wpb_composer_front_js' );
		// wp_enqueue_style('js_composer_custom_css');

	    $css_class = apply_filters( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', vc_shortcode_custom_css_class( $css, ' ' ) );
	    $warrior_set_content = '<section id="advanced-search-section" class="homepage-widget '.$type.' ' .$el_class.''.esc_attr( $css_class ). '">';
			$warrior_set_content .= '<div id="search-widget">';
				$warrior_set_content .= '<div class="container">';
					$warrior_set_content .= '<div class="row search-widget">';
						$warrior_set_content .= '<form role="search" method="get" id="warrior-advanced-search" action="'.esc_url(home_url( '/' )).'" >';
							$warrior_set_content .= '<div class="input-holder input-term">';
								$warrior_set_content .= '<input type="text" name="s" id="s" placeholder="'.esc_html__('Enter a search term...', 'helper').'" class="input s" value="'.esc_attr( get_search_query() ).'" autocomplete="off"/>';
								$warrior_set_content .= '<i class="live-search-reset typcn typcn-backspace-outline"></i>';
							$warrior_set_content .= '</div>';
							$warrior_set_content .= '<button type="submit" class="button blue large searchbutton">';
							$warrior_set_content .= esc_html__('Search', 'helper');
							$warrior_set_content .= '</button>';
						$warrior_set_content .= '</form>';
					$warrior_set_content .= '</div>';
				$warrior_set_content .= '</div>';
			$warrior_set_content .= '</div>';

			$warrior_set_content .= '<div class="scroller-animation"><div class="bounce"></div></div>';
			$warrior_set_content .= '<div class="bg-opacity"></div>';
		$warrior_set_content .= '</section>';
?>
			<script type="text/javascript">
			jQuery( window ).load(function() {
				'use strict';
				jQuery('#warrior-advanced-search #s').liveSearch({
					url: '<?php echo home_url("/?ajax=1&s="); ?>',
					loadingClass: 'loading',
				});
			});	
			</script>
<?php
		return $warrior_set_content;
	}
}	
add_shortcode( 'warrior_vc_advanced_search', 'warrior_vc_advanced_search_shortcode' );

/**
 * Function Visual Composer Extend (Advanced Search)
 *
 * @package WordPress
 * @subpackage helper
 * @since helper 1.5.0
 */
add_action( 'vc_before_init', 'warrior_vc_advanced_search_set_param' );
if( !function_exists('warrior_vc_advanced_search_set_param') ) {
	function warrior_vc_advanced_search_set_param() {
		vc_map( array(
			"name" 				=> __("Advanced Search", "helper"), // add a name
			"base" 				=> "warrior_vc_advanced_search", // bind with our shortcode
			"description" 		=> __( "Display advanced search on homepage.", "helper" ),
			"category" 			=> __("Warrior Widgets", "helper"), // set this category shortcode
			"icon" 				=> get_template_directory_uri() . "/images/warrior-icon.png", // Simply pass url to your icon here
			"content_element" 	=> true, // set this parameter when element will has a content
			"is_container" 		=> true, // set this param when you need to add a content element in this element

			// Here starts the definition of array with parameters of our component
			"params" => array(
			    array(
			        "type" 			=> "textfield",
			        "heading" 		=> __( "Title", "helper" ),
			        "value" 		=> __( "Advanced Search", "helper" ),
			        "param_name" 	=> "advanced_search_title"
			    ),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> __( 'Extra class name', 'helper' ),
					'param_name' 	=> 'el_class',
					'description' 	=> __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'helper' )
				),
				array(
					'type' 			=> 'css_editor',
					'heading' 		=> __( 'CSS', 'helper' ),
					'param_name' 	=> 'css',
					'group' 		=> __( 'Design options', 'helper' )
				)
			)
		) );
	}
}

// END Visual Composer Element Functions
?>