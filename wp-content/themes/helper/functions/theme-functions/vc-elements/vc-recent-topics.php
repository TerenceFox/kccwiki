<?php
/**
 * Function Visual Composer Extend (Recent Topics)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if( !function_exists('warrior_vc_recent_topic_shortcode') ) {
	function warrior_vc_recent_topic_shortcode( $atts ) {
	    extract( shortcode_atts( array(
	    	'type'			 				=> 'in_container',
	        'recent_topic_title' 			=> '',
	        'recent_topic_title_limiter'	=> '',
	        'recent_topic_showposts' 		=> '',
	        'css_animation' 				=> '',
	        'el_class' 						=> '',
	        'css' 							=> ''
	    ), $atts ) );

	    // wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_script( 'wpb_composer_front_js' );
		// wp_enqueue_style('js_composer_custom_css');

	    $css_class = apply_filters( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', vc_shortcode_custom_css_class( $css, ' ' ) );
	    $css_animate_class = apply_filters( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', 'wow ' . $css_animation, $atts );

		$warrior_set_content  = '<div class="column recent-blog-widget widget popular-post '.$type.' ' .$el_class.''.esc_attr( $css_class ). '" data-wow-delay="0.3s">';
            $warrior_set_content .= '<div class="inner '.$css_animate_class.'">';
                $warrior_set_content .= '<ul>';
?>
		<?php
			// Get the posts from database
			$args_recent_topic = array(
				'post_type' 			=> 'topic',
		        'post_status' 			=> 'publish',
				'ignore_sticky_posts' 	=> 1,
		        'posts_per_page' 		=> absint( $recent_topic_showposts )
			);

			$wp_query = new WP_Query();
			$wp_query->query( $args_recent_topic );
		       
		    if ( $wp_query->have_posts() ) {
		    	while ( $wp_query->have_posts() ) {
		    		$wp_query->the_post();

		            $warrior_set_content .= '<li class="forums-topics '.$css_animate_class.'">';
		            	$warrior_set_content .= '<div class="thumbnail">';
		            	$warrior_set_content .= '<a href="'. get_permalink() .'" title="'. get_the_title() .'"><i class="typcn typcn-messages"></i></a>';
			        	$warrior_set_content .= '</div>';    
	                    $warrior_set_content .= '<h3 class="post-title"><a href="'. get_the_permalink() .'" title="'. get_the_title() .'">' .wp_trim_words( get_the_title(), absint($recent_topic_title_limiter), '...' ). '</a></h3>';
	                    $warrior_set_content .= '<div class="entry-meta">';
	                        $warrior_set_content .= '<span class="date"><i class="typcn typcn-time"></i>'.date_i18n( 'M d, Y', strtotime( get_the_date() ) ).'</span>';
	                        $warrior_set_content .= '<span class="author"><i class="typcn typcn-pen"></i>'.get_the_author().'</span>';
	                    $warrior_set_content .= '</div>';
	                $warrior_set_content .= '</li>';
				}
			} else {
				echo _e('There\'s no recent topic found.', 'helper'); 
			}

			wp_reset_postdata();
			
			$warrior_set_content .= '</ul>';
            $warrior_set_content .= '</div>';
        $warrior_set_content .= '</div>';

		return $warrior_set_content;
	}
}	
add_shortcode( 'warrior_vc_recent_topic', 'warrior_vc_recent_topic_shortcode' );

/**
 * Function Visual Composer Extend (Recent Topics)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
add_action( 'vc_before_init', 'warrior_vc_recent_topic_set_param' );
if( !function_exists('warrior_vc_recent_topic_set_param') ) {
	function warrior_vc_recent_topic_set_param() {
		vc_map( array(
			"name" 				=> esc_html__(" Recent bbPress Topics", "helper"), // add a name
			"base" 				=> "warrior_vc_recent_topic", // bind with our shortcode
			"description" 		=> esc_html__( "Display recent bbPress topics.", "helper" ),
			"category" 			=> esc_html__("Warrior Widgets", "helper"), // set this category shortcode
			"icon" 				=> get_template_directory_uri() . "/images/warrior-icon.png", // Simply pass url to your icon here
			"content_element" 	=> true, // set this parameter when element will has a content
			"is_container" 		=> true, // set this param when you need to add a content element in this element

			// Here starts the definition of array with parameters of our component
			"params" => array(
			    array(
			        "type" 			=> "textfield",
			        "holder" 		=> "div",
			        "heading" 		=> esc_html__( "Title", "helper" ),
			        "param_name" 	=> "recent_topic_title"
			    ),
			    array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Post Title Limiter', 'helper' ),
					'param_name' 	=> 'recent_topic_title_limiter',
					'value' 		=> 7
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Post Per Page', 'helper' ),
					'param_name' 	=> 'recent_topic_showposts',
					'value' 		=> 4
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'CSS Animation', 'helper' ),
					'param_name' 	=> 'css_animation',
					'admin_label' 	=> true,
					'value' 		=> array(
										esc_html__( 'No', 'helper' ) => '',
										esc_html__( 'Flash', 'helper' ) => 'flash',
										esc_html__( 'Pulse', 'helper' ) => 'pulse',
										esc_html__( 'FadeIn', 'helper' ) => 'fadeIn',
										esc_html__( 'ZoomIn', 'helper' ) => "zoomIn"
									),
					'description' 	=> esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'helper' )
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