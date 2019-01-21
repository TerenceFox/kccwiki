<?php
/**
 * Function Visual Composer Extend (Knowledge Base Statistics)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */ 
if( ! function_exists('warrior_vc_knowledgebase_statistics_shortcode') ) {
	function warrior_vc_knowledgebase_statistics_shortcode( $atts ) {
	    extract( shortcode_atts( array(
	    	'type'			 								=> 	'in_container',
	        'knowledgebase_statistics_title' 				=> 	'',
	        'knowledgebase_statistics_icon_color'			=> 	'#ffffff',
	        'knowledgebase_statistics_text_color'			=> 	'#ffffff',
	        'knowledgebase_statistics_forums_icon'			=>	'typcn-messages',
	        'knowledgebase_statistics_topics_icon'			=>	'typcn-feather',
	        'knowledgebase_statistics_articles_icon'		=>	'typcn-bookmark',
	        'knowledgebase_statistics_categories_icon'		=>	'typcn-folder',
	        'knowledgebase_statistics_forums_option'		=> 	'1',
	        'knowledgebase_statistics_topics_option'		=> 	'1',
	        'knowledgebase_statistics_articles_option'		=> 	'1',
	        'knowledgebase_statistics_categories_option'	=> 	'1',
	        'css_animation' 								=>  '',
	    ), $atts ) );

	    // wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_script( 'wpb_composer_front_js' );
		// wp_enqueue_style('js_composer_custom_css');

		$css_animate_class = apply_filters( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', 'wow ' . $css_animation, $atts );

	    global $wpdb;
	    $warrior_set_content  = '<section id="statistic-widget" class="homepage-widget '.$type.'">';
	     	$warrior_set_content  .= '<div class="container">';
	            $warrior_set_content .= '<div class="countup-widget row row-4-column">';
		            $warrior_kbstat_forums_post_type = 'forum';
				    $warrior_kbstat_forums_post_type_reply = 'topic';
					$warrior_kbstat_forums_numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' and post_type = '$warrior_kbstat_forums_post_type'");
						if (0 < $warrior_kbstat_forums_numposts) $warrior_kbstat_forums_numposts = number_format($warrior_kbstat_forums_numposts); 
					$warrior_kbstat_forums_numcats = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts where post_type = '$warrior_kbstat_forums_post_type_reply'");
						if (0 < $warrior_kbstat_forums_numcats) $warrior_kbstat_forums_numcats = number_format($warrior_kbstat_forums_numcats);
					
					// Forum statistic
					if( function_exists('is_bbpress') ) {
						if ($knowledgebase_statistics_forums_option == '1') {
		                	$warrior_set_content .= '<div class="count-wrapper column">';
			                    $warrior_set_content .= '<div class="count-icon '.$css_animate_class.'"><i class="typcn '.esc_attr($knowledgebase_statistics_forums_icon).'" style="color:'. $knowledgebase_statistics_icon_color .'"></i></div>';
			                    $warrior_set_content .= '<div class="odometer count-number" data-number="'.absint($warrior_kbstat_forums_numposts).'" style="color: '. $knowledgebase_statistics_text_color .'">';
			                    $warrior_set_content .= '0';
			                    $warrior_set_content .= '</div>';
			                    $warrior_set_content .= '<div class="count-label" style="color: '. $knowledgebase_statistics_text_color .'">'.esc_html__('Forums', 'helper').'</div>';
		                	$warrior_set_content .= '</div>';
		            	}
	            	}

	            	// Topic statistic
	            	if( function_exists('is_bbpress') ) {
						if ($knowledgebase_statistics_topics_option == '1') {
			                $warrior_set_content .= '<div class="count-wrapper column">';
			                    $warrior_set_content .= '<div class="count-icon '.$css_animate_class.'"><i class="typcn '.esc_attr($knowledgebase_statistics_topics_icon).'" style="color:'. $knowledgebase_statistics_icon_color .'"></i></div>';
			                    $warrior_set_content .= '<div class="odometer count-number" data-number="'.absint($warrior_kbstat_forums_numcats).'" style="color: '. $knowledgebase_statistics_text_color .'">';
			                    $warrior_set_content .= '0';
			                    $warrior_set_content .= '</div>';
			                    $warrior_set_content .= '<div class="count-label" style="color: '. $knowledgebase_statistics_text_color .'">'.esc_html__('Topics', 'helper').'</div>';
			                $warrior_set_content .= '</div>';
		                }
	            	}

	                $warrior_kbstat_article_post_type = 'knowledge_base';
				    $warrior_kbstat_article_taxonomy = 'kb_category';
					$warrior_kbstat_article_numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' and post_type = '$warrior_kbstat_article_post_type'");
						if (0 < $warrior_kbstat_article_numposts) $warrior_kbstat_article_numposts = number_format($warrior_kbstat_article_numposts); 

					// Article statistic
					if ($knowledgebase_statistics_articles_option == '1') {
		                $warrior_set_content .= '<div class="count-wrapper column">';
		                    $warrior_set_content .= '<div class="count-icon '.$css_animate_class.'"><i class="typcn '.esc_attr($knowledgebase_statistics_articles_icon).'" style="color:'. $knowledgebase_statistics_icon_color .'"></i></div>';
		                    $warrior_set_content .= '<div class="odometer count-number" data-number="'.absint($warrior_kbstat_article_numposts).'" style="color: '. $knowledgebase_statistics_text_color .'">';
		                    $warrior_set_content .= '0';
		                    $warrior_set_content .= '</div>';
		                    $warrior_set_content .= '<div class="count-label" style="color: '. $knowledgebase_statistics_text_color .'">'.esc_html__('Articles', 'helper').'</div>';
	                	$warrior_set_content .= '</div>';
	            	}

				    $warrior_kbstat_cat_post_type = 'knowledge_base';
				    $warrior_kbstat_cat_taxonomy = 'kb_category';
					$warrior_kbstat_cat_numcats = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->term_taxonomy where taxonomy = '$warrior_kbstat_cat_taxonomy'");
						if (0 < $warrior_kbstat_cat_numcats) $warrior_kbstat_cat_numcats = number_format($warrior_kbstat_cat_numcats);

					// Knowledge Base Category statistic
					if ($knowledgebase_statistics_categories_option == '1') {
		                $warrior_set_content .= '<div class="count-wrapper column">';
		                    $warrior_set_content .= '<div class="count-icon '.$css_animate_class.'"><i class="typcn '.esc_attr($knowledgebase_statistics_categories_icon).'" style="color:'. $knowledgebase_statistics_icon_color .'"></i></div>';
		                    $warrior_set_content .= '<div class="odometer count-number" data-number="'.absint($warrior_kbstat_cat_numcats).'" style="color: '. $knowledgebase_statistics_text_color .'">';
		                    $warrior_set_content .= '0';
		                    $warrior_set_content .= '</div>';
		                    $warrior_set_content .= '<div class="count-label" style="color: '. $knowledgebase_statistics_text_color .'">'.esc_html__('Categories', 'helper').'</div>';
		                $warrior_set_content .= '</div>';
	            	}

	            $warrior_set_content .= '</div>';
	        $warrior_set_content .= '</div>';    
	    $warrior_set_content .= '</section>';

		return $warrior_set_content;
	}
}	
add_shortcode( 'warrior_vc_knowledgebase_statistics', 'warrior_vc_knowledgebase_statistics_shortcode');

/**
 * Function Visual Composer Extend (Knowledge Base Statistics)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
add_action( 'vc_before_init', 'warrior_vc_knowledgebase_statistics_set_param' );
if( ! function_exists('warrior_vc_knowledgebase_statistics_set_param') ) {
	function warrior_vc_knowledgebase_statistics_set_param() {
		vc_map( array(
			"name" 				=> esc_html__("Knowledge Base Statistics", "helper"), // add a name
			"base" 				=> "warrior_vc_knowledgebase_statistics", // bind with our shortcode
			"description" 		=> esc_html__( "Display knowledge base statistics.", "helper" ),
			"category" 			=> esc_html__("Warrior Widgets", "helper"), // set this category shortcode
			"icon" 				=> get_template_directory_uri() . "/images/warrior-icon.png", // Simply pass url to your icon here
			"content_element"	=> true, // set this parameter when element will has a content
			"is_container" 		=> true, // set this param when you need to add a content element in this element

			// Here starts the definition of array with parameters of our component
			"params" => array(
			    array(
			        "type" 			=> "textfield",
			        "holder" 		=> "div",
			        "heading" 		=> esc_html__("Title", "helper"),
			        "value" 		=> esc_html__( "Knowledge Base Statistics", "helper" ),
			        "param_name" 	=> "knowledgebase_statistics_title"
			    ),
				array(
					'type' 			=> 'colorpicker',
					'heading' 		=> esc_html__( 'Icon Color', 'helper' ),
					'param_name' 	=> 'knowledgebase_statistics_icon_color',
            		'value'			=> '#ffffff',
				),
				array(
					'type' 			=> 'colorpicker',
					'heading' 		=> esc_html__( 'Text Color', 'helper' ),
					'param_name' 	=> 'knowledgebase_statistics_text_color',
            		'value'			=> '#ffffff',
				),
			    array(
			        "type" 			=> "textfield",
			        "heading" 		=> esc_html__("Forum Icon", "helper"),
			        "value" 		=> esc_html__( "typcn-messages", "helper" ),
			        "param_name" 	=> "knowledgebase_statistics_forums_icon"
			    ),
			    array(
			        "type" 			=> "textfield",
			        "heading" 		=> esc_html__("Topic Icon", "helper"),
			        "value" 		=> esc_html__( "typcn-feather", "helper" ),
			        "param_name" 	=> "knowledgebase_statistics_topics_icon"
			    ),
			    array(
			        "type" 			=> "textfield",
			        "heading" 		=> esc_html__("Article Icon", "helper"),
			        "value" 		=> esc_html__( "typcn-bookmark", "helper" ),
			        "param_name" 	=> "knowledgebase_statistics_articles_icon"
			    ),
			    array(
			        "type" 			=> "textfield",
			        "heading" 		=> esc_html__("Category Icon", "helper"),
			        "value" 		=> esc_html__( "typcn-folder", "helper" ),
			        "param_name" 	=> "knowledgebase_statistics_categories_icon",
			        "description" 	=> esc_html__( 'Type in the icon name, example icon: "typcn-globe, typcn-help, typcn-paper, typcn-folder, etc. Click <a href="http://typicons.com" target="_blank">here</a> to see list of available icons" .', 'helper' )
			    ),
			    array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Display bbPress Forum Statistics', 'helper' ),
					'param_name' 	=> 'knowledgebase_statistics_forums_option',
					'value' 		=> array(
										esc_html__( 'Show', 'helper' ) => '1',
										esc_html__( 'Hide', 'helper' ) => '0'
					)
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Display bbPress Topic Statistics', 'helper' ),
					'param_name' 	=> 'knowledgebase_statistics_topics_option',
					'value' 		=> array(
										esc_html__( 'Show', 'helper' ) => '1',
										esc_html__( 'Hide', 'helper' ) => '0'
					)
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Display Article Statistics', 'helper' ),
					'param_name' 	=> 'knowledgebase_statistics_articles_option',
					'value' 		=> array(
										esc_html__( 'Show', 'helper' ) => '1',
										esc_html__( 'Hide', 'helper' ) => '0'
					)
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Display Category Statistics', 'helper' ),
					'param_name' 	=> 'knowledgebase_statistics_categories_option',
					'value' 		=> array(
										esc_html__( 'Show', 'helper' ) => '1',
										esc_html__( 'Hide', 'helper' ) => '0'
					)
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
				)
			)
		) );
	}
}
?>