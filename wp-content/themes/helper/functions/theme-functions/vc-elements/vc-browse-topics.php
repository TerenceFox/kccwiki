<?php
/**
 * Function Visual Composer Extend (Browse Topics)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if( !function_exists('warrior_vc_browse_topics_shortcode') ) {
	function warrior_vc_browse_topics_shortcode( $atts ) {
	    extract( shortcode_atts( array(
	    	'type'			 				  => 'in_container',
	        'browse_topics_title' 			  => '',
	        'browse_topics_sub_title'		  => '',
	        'browse_topics_title_limiter'	  => '',
	        'browse_topics_content_limiter'	  => '',
	        'browse_topics_showposts' 		  => '',
	        'browse_topics_kb_link_pages'	  => '',
	        'browse_topics_forums_link_pages' => '',
	        'browse_topics_news_link_pages'	  => '',
	        'css_animation' 				  => '',
	        'el_class' 						  => '',
	        'css' 							  => ''
	    ), $atts ) );

	    // wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_script( 'wpb_composer_front_js' );
		// wp_enqueue_style('js_composer_custom_css');

	    $css_class = apply_filters( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', vc_shortcode_custom_css_class( $css, ' ' ) );
	    $css_animate_class = apply_filters( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', 'wow ' . $css_animation, $atts );

        $warrior_set_content  = '<section id="choose-section" class="homepage-widget '.$type.' ' .$el_class.''.esc_attr( $css_class ). '">';
            $warrior_set_content .= '<div class="simple-tab">';
                $warrior_set_content .= '<div class="simple-tab-nav">';
                    $warrior_set_content .= '<ul>';
                        $warrior_set_content .= '<li class="current"><a href="#tab-1">'.esc_html__("Knowledebase", "helper").'</a></li>';
                        $warrior_set_content .= '<li><a href="#tab-2">'.esc_html__("Forums", "helper").'</a></li>';
                        $warrior_set_content .= '<li><a href="#tab-3">'.esc_html__("Latest News", "helper").'</a></li>';
                    $warrior_set_content .= '</ul>';
                $warrior_set_content .= '</div>';

                $warrior_set_content .= '<div class="tab">';
                    $warrior_set_content .= '<div id="tab-1" class="tab-content">';
                    	$get_count_published_knowledge = wp_count_posts('knowledge_base')->publish;
                        $warrior_set_content .= '<div class="section-count">'.absint($get_count_published_knowledge).' <span>'.esc_html__(" Articles", "helper").'</span></div>';
                        $warrior_set_content .= '<div class="row row-4-column">';

                        // Get the posts from database
						$args_browse_topics = array(
							'post_type' 			=> 'knowledge_base',
					        'post_status' 			=> 'publish',
							'ignore_sticky_posts' 	=> 1,
					        'posts_per_page' 		=> absint( $browse_topics_showposts )
						);

						$wp_query = new WP_Query();
						$wp_query->query( $args_browse_topics );
					       
					    if ( $wp_query->have_posts() ) {
					    	while ( $wp_query->have_posts() ) {
					    		$wp_query->the_post();

				                $warrior_set_content .= '<article id="kb-'.get_the_ID().'" class="featured-post column el_class '.$css_animate_class.'" data-wow-delay="0.3s">';
	                                $warrior_set_content .= '<div class="inner">';
	                                    $warrior_set_content .= '<div class="entry-meta">';
	                                        $warrior_set_content .= ''.date_i18n( 'M d, Y', strtotime( get_the_date() ) ).'';
	                                    $warrior_set_content .= '</div>';
	                                    $warrior_set_content .= '<div class="thumbnail">';
					                        if ( has_post_thumbnail() ) {
												$warrior_set_content .= '<a href="'. get_permalink() .'" title="'. get_the_title() .'">' .get_the_post_thumbnail( get_the_ID(), 'blog-image' ). '</a>';
								            } else {
								            	$warrior_set_content .= '<a href="'.get_the_permalink().'" title="'. get_the_title() .'">';
								                $warrior_set_content .= '<img src="http://placehold.it/280x185/333333/ffffff?text='. esc_html__('No Thumbnail', 'helper').'">';
								                $warrior_set_content .= '</a>';
								            }
					                    $warrior_set_content .= '</div>';
	                                    $warrior_set_content .= '<h3 class="post-title"><a href="'. get_the_permalink() .'" title="'. get_the_title() .'">' .wp_trim_words( get_the_title(), absint($browse_topics_title_limiter), '...' ). '</a></h3>';
	                                    $warrior_set_content .= '<p>' .wp_trim_words( strip_shortcodes( get_the_content() ), absint($browse_topics_content_limiter), '...' ). '</p>';
	                                $warrior_set_content .= '</div>';
	                            $warrior_set_content .= '</article>';
							}
						} else {
							echo _e('There\'s no knowledge base post found.', 'helper'); 
						}

						wp_reset_postdata();

                        $warrior_set_content .= '</div>';
                        $warrior_set_content .= '<div class="simple-tab-footer">';
                        	$warrior_set_link_pages = esc_url( $browse_topics_kb_link_pages );
                        $warrior_set_content .= '</div>';
                    $warrior_set_content .= '</div>';

                    $warrior_set_content .= '<div id="tab-2" class="tab-content">';
                    	$get_count_published_forums = wp_count_posts('topic')->publish;
                        $warrior_set_content .= '<div class="section-count">'.absint($get_count_published_forums).' <span>'.esc_html__(" Topics", "helper").'</span></div>';
                        $warrior_set_content .= '<div class="row row-4-column">';

                        // Get the posts from database
						$args_browse_topics = array(
							'post_type' 			=> 'topic',
					        'post_status' 			=> 'publish',
							'ignore_sticky_posts' 	=> 1,
					        'posts_per_page' 		=> absint($browse_topics_showposts)
						);

						$wp_query = new WP_Query();
						$wp_query->query( $args_browse_topics );
					       
					    if ( $wp_query->have_posts() ) {
					    	while ( $wp_query->have_posts() ) {
					    		$wp_query->the_post();

				                $warrior_set_content .= '<article id="topic-'.get_the_ID().'" class="featured-post column el_class" data-wow-delay="0.3s">';
	                                $warrior_set_content .= '<div class="inner">';
	                                    $warrior_set_content .= '<div class="icon">';
	                                        $warrior_set_content .= '<span class="typcn typcn-messages"></span>';
	                                    $warrior_set_content .= '</div>';
	                                    $warrior_set_content .= '<h3 class="post-title"><a href="'. get_the_permalink() .'" title="'. get_the_title() .'">' .wp_trim_words( get_the_title(), absint($browse_topics_title_limiter), '...' ). '</a></h3>';
	                                    $warrior_set_content .= '<p>' .wp_trim_words( get_the_content(), absint($browse_topics_content_limiter), '...' ). '</p>';
	                                $warrior_set_content .= '</div>';
	                            $warrior_set_content .= '</article>';
							}
						} else {
							echo _e('There\'s no recent topic found.', 'helper'); 
						}

						wp_reset_postdata();

                        $warrior_set_content .= '</div>';
                        $warrior_set_content .= '<div class="simple-tab-footer">';
                        	$warrior_set_link_pages = esc_url( $browse_topics_forums_link_pages );
                        $warrior_set_content .= '</div>';
                    $warrior_set_content .= '</div>';

                    $warrior_set_content .= '<div id="tab-3" class="tab-content">';
                    	$get_count_published_posts = wp_count_posts('post')->publish;
                        $warrior_set_content .= '<div class="section-count">'.absint($get_count_published_posts).' <span>'.esc_html__(" Articles", "helper").'</span></div>';
                        $warrior_set_content .= '<div class="row row-4-column">';
                         
                        // Get the posts from database
						$args_browse_topics = array(
							'post_type' 			=> 'post',
					        'post_status' 			=> 'publish',
							'ignore_sticky_posts' 	=> 1,
					        'posts_per_page' 		=> absint($browse_topics_showposts)
						);

						$wp_query = new WP_Query();
						$wp_query->query( $args_browse_topics );
					       
					    if ( $wp_query->have_posts() ) {
					    	while ( $wp_query->have_posts() ) {
					    		$wp_query->the_post();

				                $warrior_set_content .= '<article id="blog-post-'.get_the_ID().'" class="featured-post column el_class" data-wow-delay="0.3s">';
	                                $warrior_set_content .= '<div class="inner">';
	                                    $warrior_set_content .= '<div class="entry-meta">';
	                                        $warrior_set_content .= ''.date_i18n( 'M d, Y', strtotime( get_the_date() ) ).'';
	                                    $warrior_set_content .= '</div>';
	                                    $warrior_set_content .= '<div class="thumbnail">';
					                        if ( has_post_thumbnail() ) {
												$warrior_set_content .= '<a href="'. get_permalink() .'" title="'. get_the_title() .'">' .get_the_post_thumbnail( get_the_ID(), 'blog-image' ). '</a>';
								            } else {
								            	$warrior_set_content .= '<a href="'.get_the_permalink().'" title="'. get_the_title() .'">';
								                $warrior_set_content .= '<img src="http://placehold.it/280x185/333333/ffffff?text='. esc_html__('No Thumbnail', 'helper').'">';
								                $warrior_set_content .= '</a>';
								            }
					                    $warrior_set_content .= '</div>';
	                                    $warrior_set_content .= '<h3 class="post-title"><a href="'. get_the_permalink() .'" title="'. get_the_title() .'">' .wp_trim_words( get_the_title(), absint($browse_topics_title_limiter), '...' ). '</a></h3>';
	                                    $warrior_set_content .= '<p>' .wp_trim_words( get_the_content(), absint($browse_topics_content_limiter), '...' ). '</p>';
	                                $warrior_set_content .= '</div>';
	                            $warrior_set_content .= '</article>';
							}
						} else {
							echo _e('There\'s no latest post found.', 'helper'); 
						}

						wp_reset_postdata();

                        $warrior_set_content .= '</div>';
                        $warrior_set_content .= '<div class="simple-tab-footer">';
                        	$warrior_set_link_pages = esc_url( $browse_topics_news_link_pages );
                        $warrior_set_content .= '</div>';
                    $warrior_set_content .= '</div>';

                $warrior_set_content .= '</div>';
            $warrior_set_content .= '</div>';
        $warrior_set_content .= '</section>';


		return $warrior_set_content;

	}
}	
add_shortcode( 'warrior_vc_browse_topics', 'warrior_vc_browse_topics_shortcode' );

/**
 * Function Visual Composer Extend (Browse Topics)
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
add_action( 'vc_before_init', 'warrior_vc_browse_topics_set_param' );
if( !function_exists('warrior_vc_browse_topics_set_param') ) {
	function warrior_vc_browse_topics_set_param() {
		vc_map( array(
			"name" 				=> esc_html__("Browse Topics", "helper"), // add a name
			"base" 				=> "warrior_vc_browse_topics", // bind with our shortcode
			"description" 		=> esc_html__( "Display browse topics.", "helper" ),
			"category" 			=> esc_html__("Warrior Widgets", "helper"), // set this category shortcode
			"icon" 				=> get_template_directory_uri() . "/images/warrior-icon.png", // Simply pass url to your icon here
			"content_element" 	=> true, // set this parameter when element will has a content
			"is_container" 		=> true, // set this param when you need to add a content element in this element

			// Here starts the definition of array with parameters of our component
			"params" => array(
			    array(
			        "type" 			=> "textfield",
			        "heading" 		=> esc_html__( "Title", "helper" ),
			        "param_name" 	=> "browse_topics_title"
			    ),
			    array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Post Title Limiter', 'helper' ),
					'param_name' 	=> 'browse_topics_title_limiter',
					'value' 		=> 4
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Post Content Limiter', 'helper' ),
					'param_name' 	=> 'browse_topics_content_limiter',
					'value' 		=> 20
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Posts Per Page', 'helper' ),
					'param_name' 	=> 'browse_topics_showposts',
					'value' 		=> 4
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'CSS Animation', 'helper' ),
					'param_name' 	=> 'css_animation',
					'admin_label'	=> true,
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