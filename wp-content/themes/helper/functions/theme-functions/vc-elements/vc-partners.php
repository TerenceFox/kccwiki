<?php
/**
 * Function Visual Composer Extend (Partners)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if( !function_exists('warrior_vc_partners_shortcode') ) {
	function warrior_vc_partners_shortcode( $atts ) {
	    extract( shortcode_atts( array(
	    	'type'			 				=> 'in_container',
	        'partners_title' 				=> '',
	        'partners_showposts' 			=> '',
	        'el_class' 						=> '',
	        'css' 							=> ''
	    ), $atts ) );

	    // wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_script( 'wpb_composer_front_js' );
		// wp_enqueue_style('js_composer_custom_css');

	    $css_class = apply_filters( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', vc_shortcode_custom_css_class( $css, ' ' ) );

		$warrior_set_content  = '<section id="partner-widget" class="partner-widget homepage-widget '.$type.' ' .$el_class.''.esc_attr( $css_class ). '">';
            $warrior_set_content  .= '<div class="image-carousel flexslider">';
                $warrior_set_content  .= '<ul class="slides">';
?>
	<?php
		// Get the posts from database
		$args_partners = array(
			'post_type' 			=> 'partners',
	        'post_status' 			=> 'publish',
	        'posts_per_page' 		=> absint( $partners_showposts )
		);

		$wp_query = new WP_Query();
		$wp_query->query( $args_partners );
	    
	    // Load the posts
	    if ( $wp_query->have_posts() ) {
	    	while ( $wp_query->have_posts() ) {
	    		$wp_query->the_post();      
            
	            $warrior_set_content .= '<li>';
		            if ( has_post_thumbnail() ) {
		            	if( function_exists('get_field') ) {
							if( function_exists('get_field') ) {
								// Check if link field is not empty
								if( get_field('partner_links') ) {
									$warrior_set_content .= '<a href="'.get_field('partner_links', get_the_ID()).'" title="'. get_the_title() .'" target="_blank">' .get_the_post_thumbnail( get_the_ID(), 'partner-image' ). '</a>';
								} else {
									$warrior_set_content .= get_the_post_thumbnail( get_the_ID(), 'partner-image' );

								}
							} 
						}
		            } else {
		            	if( function_exists('get_field') && get_field('partner_links') ) {
							if( function_exists('get_field') ) {
								$warrior_set_content .= '<a href="'.get_field('partner_links', get_the_ID()).'" title="'. get_the_title() .'" target="_blank">';
							}
						}
		                $warrior_set_content .= get_the_title();
		                $warrior_set_content .= '</a>';
		            }
	            $warrior_set_content .= '</li>';
			}
		} else {
			echo _e('No partner found.', 'helper');
		}

		wp_reset_postdata();

				$warrior_set_content .= '</ul>';
            $warrior_set_content .= '</div>';
	    $warrior_set_content .= '</section>';

		return $warrior_set_content;
	}
}	
add_shortcode( 'warrior_vc_partners', 'warrior_vc_partners_shortcode' );

/**
 * Function Visual Composer Extend (Partners)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
add_action( 'vc_before_init', 'warrior_vc_partners_set_param' );
if( !function_exists('warrior_vc_partners_set_param') ) {
	function warrior_vc_partners_set_param() {
		vc_map( array(
			"name" 				=> esc_html__("Our Partners", "helper"), // add a name
			"base" 				=> "warrior_vc_partners", // bind with our shortcode
			"description" 		=> esc_html__( "Display partners.", "helper" ),
			"category" 			=> esc_html__("Heler Widgets", "helper"), // set this category shortcode
			"icon" 				=> get_template_directory_uri() . "/images/warrior-icon.png", // Simply pass url to your icon here
			"content_element" 	=> true, // set this parameter when element will has a content
			"is_container" 		=> true, // set this param when you need to add a content element in this element

			// Here starts the definition of array with parameters of our component
			"params" => array(
			    array(
			        "type" 			=> "textfield",
			        "holder" 		=> "div",
			        "heading" 		=> esc_html__( "Title", "helper" ),
			        "param_name" 	=> "partners_title"
			    ),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Posts Per Page', 'helper' ),
					'param_name' 	=> 'partners_showposts',
					'value' 		=> 12
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Extra class name', 'helper' ),
					'param_name' 	=> 'el_class',
					'description' 	=> esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'helper' )
				),
				array(
					'type' 			=> 'css_editor',
					'heading' 		=> esc_html__( 'CSS', 'helper' ),
					'param_name' 	=> 'css',
					'group' 		=> esc_html__( 'Design options', 'helper' )
				)
			)
		) );
	}
}
?>