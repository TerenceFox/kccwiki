<?php
/**
 * Function to collect the title of the current page
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_archive_title' ) ) {
	function warrior_archive_title() {
		global $wp_query;

		$title = '';
		if ( is_home() ) :
			
		elseif ( is_category() ) :
			$title = sprintf( __( 'Category Archives: %s', 'helper' ), single_cat_title( '', false ) );
		elseif ( is_tag() ) :
			$title = sprintf( __( 'Tag Archives: %s', 'helper' ), single_tag_title( '', false ) );
		elseif ( is_tax('kb_tag') ) :
			$title = sprintf( __( 'Tag Archives: %s', 'helper' ), single_tag_title( '', false ) );
		elseif ( is_tax('kb_category') ) :
			$title = sprintf( __( 'Knowledge Base Archives: %s', 'helper' ), single_cat_title( '', false ) );
		elseif ( is_day() ) :
			$title = sprintf( __( 'Daily Archives: %s', 'helper' ), date_i18n('M d', strtotime( get_the_date() ) ) );
		elseif ( is_month() ) :
			$title = sprintf( __( 'Monthly Archives: %s', 'helper' ), date_i18n( 'F Y', strtotime( get_the_date() ) ) );
		elseif ( is_year() ) :
			$title = sprintf( __( 'Yearly Archives: %s', 'helper' ), date_i18n( 'Y', strtotime( get_the_date() ) ) );
		elseif ( is_author() ) :
			$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
			$title = sprintf( __( 'Author Archives: %s', 'helper' ), get_the_author_meta( 'display_name', $author->ID ) );
		elseif ( is_search() ) :
			if ( $wp_query->found_posts ) {
				$title = sprintf( __( 'Search Results for: "%s"', 'helper' ), esc_attr( get_search_query() ) );
			} else {
				$title = sprintf( __( 'No Results for: "%s"', 'helper' ), esc_attr( get_search_query() ) );
			}
		elseif ( is_404() ) :
			$title = __( 'Not Found', 'helper' );
		else :
			$title = '';
		endif;
		
		return $title;
	}
}

/**
 * Function to get post views
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_get_post_views' ) ) {
	function warrior_get_post_views($postID) {
		$warrior_count_key = 'post_views_count';
	    $warrior_get_count = get_post_meta($postID, $warrior_count_key, true);
	    $text_views = __(' Views', 'helper');
	    $text_view 	= __(' View', 'helper');
	    if( $warrior_get_count == '' ) {
	        delete_post_meta($postID, $warrior_count_key);
	        add_post_meta($postID, $warrior_count_key, '0');
	        return "0".$text_view;
	    }
	    return $warrior_get_count.$text_views;
	}
}

if ( ! function_exists( 'warrior_set_post_views' ) ) {
	function warrior_set_post_views($postID) {
	    $warrior_count_key = 'post_views_count';
	    $warrior_get_count = get_post_meta($postID, $warrior_count_key, true);
	    if($warrior_get_count==''){
	        $warrior_get_count = 0;
	        delete_post_meta($postID, $warrior_count_key);
	        add_post_meta($postID, $warrior_count_key, '0');
	    }else{
	        $warrior_get_count++;
	        update_post_meta($postID, $warrior_count_key, $warrior_get_count);
	    }
	}
}

/**
 * Function to display social media author
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_social_media_author' ) ) {
	function warrior_social_media_author() {
		global $helper_option;
		global $post;

		$author 		= $post->post_author;
		$author_ID 		= $author;		
        $facebook 		= get_field('facebook_url_author', 'user_'. $author_ID);
        $twitter 		= get_field('twitter_url_author', 'user_'. $author_ID);
        $googleplus 	= get_field('google_plus_url_author', 'user_'. $author_ID);
        $instagram 		= get_field('instagram_url_author', 'user_'. $author_ID);
        $dribbble 		= get_field('dribbble_url_author', 'user_'. $author_ID);
        $pinterest 		= get_field('pinterest_url_author', 'user_'. $author_ID);
        $github 		= get_field('github_url_author', 'user_'. $author_ID);
        $tumblr 		= get_field('tumblr_url_author', 'user_'. $author_ID);
?>
	<ul class="social-link">
		<li><span><b><?php _e('Follow ', 'helper'); echo  get_the_author(); echo " :"; ?></b></span></li>
		<?php if(!empty($facebook)) : ?>
			<li><a href="<?php echo esc_url($facebook); ?>" target="_blank"><i class="typcn typcn-social-facebook"></i></a></li>
		<?php endif; ?>
		<?php if(!empty($twitter)) : ?>
			<li><a href="<?php echo esc_url($twitter); ?>" target="_blank"><i class="typcn typcn-social-twitter"></i></a></li>
		<?php endif; ?>
		<?php if(!empty($googleplus)) : ?>
			<li><a href="<?php echo esc_url($googleplus); ?>" target="_blank"><i class="typcn typcn-social-google-plus"></i></a></li>
		<?php endif; ?>
		<?php if(!empty($instagram)) : ?>
			<li><a href="<?php echo esc_url($instagram); ?>" target="_blank"><i class="typcn typcn-social-instagram"></i></a></li>
		<?php endif; ?>
		<?php if(!empty($dribbble)) : ?>
			<li><a href="<?php echo esc_url($dribbble); ?>" target="_blank"><dribbble class="typcn typcn-social-dribbble"></i></a></li>
		<?php endif; ?>
		<?php if(!empty($pinterest)) : ?>
			<li><a href="<?php echo esc_url($pinterest); ?>" target="_blank"><i class="typcn typcn-social-pinterest"></i></a></li>
		<?php endif; ?>
		<?php if(!empty($github)) : ?>
			<li><a href="<?php echo esc_url($github); ?>" target="_blank"><i class="typcn typcn-social-github"></i></a></li>
		<?php endif; ?>	
		<?php if(!empty($tumblr)) : ?>
			<li><a href="<?php echo esc_url($tumblr); ?>" target="_blank"><i class="typcn typcn-social-tumbler"></i></a></li>
		<?php endif; ?>	
	</ul>	
<?php	
	}
}

/**
 * Function to display post meta
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_post_meta' ) ) {
	function warrior_post_meta() {
		global $helper_option;
		global $post;
	?>
		<span class="entry-meta">
			<span><i class="typcn typcn-time"></i> <?php echo date_i18n( 'M d, Y', strtotime( get_the_date('Y-m-d'), false ) ); ?> <?php _e('/', 'helper'); ?>  <?php _e('Modified', 'helper'); ?> <?php echo human_time_diff( get_the_modified_date('U'), current_time('timestamp') ) . __(' ago', 'helper'); ?></span>
        </span> 
	<?php
	}
}

/**
 * Function to display post date
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_post_date' ) ) {
	function warrior_post_date() {
		global $helper_option;
		global $post;
	?>
		<div class="leftside">
            <div class="blog-date">
                <?php echo date_i18n( 'd', strtotime( get_the_date('Y-m-d'), false ) ); ?> <span><?php echo date_i18n( 'F Y', strtotime( get_the_date('Y-m-d'), false ) ); ?></span> 
            </div>
        </div>
	<?php
	}
}

/**
 * Function to display related post meta
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_related_post_meta' ) ) {
	function warrior_related_post_meta() {
		global $helper_option;
		global $post;
	?>
		<div class="entry-meta">
			<span><i class="typcn typcn-time"></i> <?php echo date_i18n( 'M, Y', strtotime( get_the_date('Y-m-d'), false ) ); ?></span>
            <span><i class="typcn typcn-pen"></i><?php the_author_posts_link(); ?></span>
        </div> 
	<?php
	}
}

/**
 * function to display all tags from custom post type
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'post_type_tags_fix' ) ) {
function post_type_tags_fix($request) {
	if ( isset($request['tag']) && !isset($request['post_type']) )
	$request['post_type'] = 'any';
	return $request;
} 
add_filter('request', 'post_type_tags_fix');
}

/**
 * Assign global variables
 * function array_cat_list_id, return array of hierarchical categories,
 * with category id is the array key and category name as array value
 * this returned array will use on theme options of dropdown select or multiple select elements
 * with category id as element value
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'array_cat_list_id' ) ) {
	function array_cat_list_id($parent, $obj, $arr, $lvl) {
		global $arr;
		$lvl++;
		foreach ( $obj as $cat ) {
			if ( $cat->parent==$parent ) {
				$arr[$cat->cat_ID] = str_pad('',($lvl - 1) ,'-').' '.$cat->cat_name;
				$arr = array_cat_list_id($cat->cat_ID, $obj, $arr, $lvl);
			}
		}
		return $arr;
	}
}

/**
 * Function to load comment list
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_comment_list' ) ) {
	function warrior_comment_list($comment, $args, $depth) {
		global $post;
		$author_post_id 	= $post->post_author;
		$GLOBALS['comment'] = $comment;

		// Allowed html tags will be display
		$allowed_html = array(
			'a' 			=> array( 'href' => array(), 'title' => array() ),
			'abbr' 			=> array( 'title' => array() ),
			'acronym' 		=> array( 'title' => array() ),
			'strong' 		=> array(),
			'b' 			=> array(),
			'blockquote' 	=> array( 'cite' => array() ),
			'cite' 			=> array(),
			'code' 			=> array(),
			'del' 			=> array( 'datetime' => array() ),
			'em' 			=> array(),
			'i' 			=> array(),
			'q' 			=> array( 'cite' => array() ),
			'strike' 		=> array(),
			'ul' 			=> array(),
			'ol' 			=> array(),
			'li' 			=> array()
		);
		
		switch ( $comment->comment_type ) :
			case '' :
	?>
	<ul>
		<li id="comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
			<div class="thumbnail"><?php echo get_avatar( $comment, 80 ); ?></div>
			<div class="comment-detail">
				<div class="author">
					<h5><?php comment_author_link(); ?></h5>
					<div class="entry-meta">
						<span><i class="typcn typcn-time"></i><?php comment_date('j M, Y'); echo ',  '; comment_time(); ?></span>
					</div>
					<span class="edit-link"><?php edit_comment_link(__('<i class="typcn typcn-edit"></i> Edit Comment', 'helper'), '', ''); ?></span>
				</div>
				<?php if ($comment->comment_approved == '0') : ?>
					<p class="moderate"><?php _e('Your comment is now awaiting moderation before it will appear on this post.', 'helper');?></p>
				<?php endif; ?>
				<?php echo apply_filters('comment_text', wp_kses( get_comment_text(), $allowed_html ) );  ?>
				
				<div class="reply">
					<?php echo comment_reply_link(array('reply_text' => '<i class="typcn typcn-message-typing"></i> '. __('Reply', 'helper'), 'depth' => $depth, 'max_depth' => $args['max_depth'] ));  ?>			
				</div><!-- .reply -->
			</div>
			<div class="clearfix"></div>
		</li>
	</ul>	

	<?php
			break;
			case 'pingback'  :
			case 'trackback' :
	?>
			<li id="comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
				<div class="thumbnail"><?php echo get_avatar( $comment, 80 ); ?></div>
				<div class="comment-detail">
					<div class="author">
						<h5><?php comment_author_link(); ?></h5>
						<div class="entry-meta">
							<span><i class="typcn typcn-time"></i><?php comment_date('j M, Y'); echo ',  '; comment_time(); ?></span>
						</div>
						<span class="edit-link"><?php edit_comment_link(__('<i class="typcn typcn-edit"></i> Edit Comment', 'helper'), '', ''); ?></span>
					</div>
					<?php if ($comment->comment_approved == '0') : ?>
						<p class="moderate"><?php _e('Your comment is now awaiting moderation before it will appear on this post.', 'helper');?></p>
					<?php endif; ?>
					<?php echo apply_filters('comment_text', wp_kses( get_comment_text(), $allowed_html ) );  ?>
					
					<div class="reply">
						<?php echo comment_reply_link(array('reply_text' => '<i class="typcn typcn-message"></i> '. __('Reply', 'helper'), 'depth' => $depth, 'max_depth' => $args['max_depth'] ));  ?>			
					</div><!-- .reply -->
				</div>
				<div class="clearfix"></div>
			</li>
	<?php
			break;
		endswitch;
	}
}

if ( ! function_exists( 'warrior_comment_form_top' ) ) {
	function warrior_comment_form_top() {
	?>
	<div class="comment-form float-label">
	<?php
	}
	add_action( 'comment_form_top', 'warrior_comment_form_top' );

	function warrior_comment_form_bottom() {
	?>
	</div>
	
	<?php
	}
}
add_action( 'comment_form', 'warrior_comment_form_bottom', 1 );


/**
 * Add class on posts prev & next
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

if ( ! function_exists( 'next_posts_link_class' ) ) {
	function next_posts_link_class() {
	    return 'class="next"';
	}
}
add_filter('next_posts_link_attributes', 'next_posts_link_class');


if ( ! function_exists( 'previous_posts_link_class' ) ) {
	function previous_posts_link_class() {
	    return 'class="prev"';
	}
}
add_filter('previous_posts_link_attributes', 'previous_posts_link_class');


/**
 * Live chat code
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if( ! function_exists('warrior_live_chat_code') ) {
	function warrior_live_chat_code() {
		global $helper_option;

		if( isset($helper_option['general_live_chat_code']) ) {
		    echo $helper_option['general_live_chat_code'];
		}
	}
}

global $helper_option;
if( $helper_option['general_live_chat_code_location'] == '2' ) {
	add_action('wp_enqueue_scripts', 'warrior_live_chat_code');
} else {
	add_action('wp_enqueue_scripts', 'warrior_live_chat_code');
}

/**
 * Facebook Open Graph Generator
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if( ! function_exists('warrior_open_graph') ) {
	function warrior_open_graph() {
	    echo '<meta property="og:title" content="' . get_the_title() . '" />';
	    echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />';
		global $post;

		if ( is_singular() ) {
	        echo '<meta property="og:type" content="article" />';
	        echo '<meta property="og:url" content="' . get_permalink() . '" />';
			
			if (has_post_thumbnail( $post->ID )) { // use featured image if there is one
		        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
		        echo '<meta property="og:image" content="' . esc_url( $feat_image[0] ) . '" />';
			} else {
		        echo '<meta property="og:image" content="http://placehold.it/500x400&amp;text='. __('No Thumbnail', 'helper') .'" />';
			}
		}

		if ( is_home() ) {
	        echo '<meta property="og:type" content="website" />';
	        echo '<meta property="og:url" content="' . esc_url(get_home_url('/')) . '" />';
	        echo '<meta property="og:image" content="http://placehold.it/500x400&amp;text='. __('No Thumbnail', 'helper') .'" />';
		}
	}
}
add_action( 'wp_enqueue_scripts', 'warrior_open_graph' );

/**
 * Function to get twitter update
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if( ! function_exists('warrior_get_recent_tweets') ) {
	function warrior_get_recent_tweets( $screen_name = '', $consumer_key = '', $consumer_secret = '', $tweets_count = 5 ) {

		if ( !$screen_name)
			return false;
		
		// some variables
		$token = get_option('warriorTwitterToken'.$screen_name);

		// get recent tweets from cache
		$recent_tweets = get_transient('warriorRecentTweets'.$screen_name);

		// cache version does not exist or expired
		if (false === $recent_tweets) {

			// getting new auth bearer only if we don't have one
			if(!$token) {

				// preparing credentials
				$credentials = $consumer_key . ':' . $consumer_secret;
				$toSend = base64_encode($credentials);
	 
				// http post arguments
				$args = array(
					'method' 		=> 'POST',
					'httpversion' 	=> '1.1',
					'blocking' 		=> true,
					'headers' 		=> array(
						'Authorization' => 'Basic ' . $toSend,
						'Content-Type' 	=> 'application/x-www-form-urlencoded;charset=UTF-8'
					),
					'body' 			=> array( 'grant_type' => 'client_credentials' )
				);
	 
				add_filter('https_ssl_verify', '__return_false');
				$response 	= wp_remote_post('https://api.twitter.com/oauth2/token', $args);
				$keys 		= json_decode(wp_remote_retrieve_body($response));

				if($keys) {
					// saving token to wp_options table
					update_option('warriorTwitterToken'.$screen_name, $keys->access_token);
					$token = $keys->access_token;
				}
			}

			// we have bearer token wether we obtained it from API or from options
			$args = array(
				'httpversion' 	=> '1.1',
				'blocking' 		=> true,
				'headers' 		=> array(
					'Authorization' => "Bearer $token"
				)
			);

			add_filter('https_ssl_verify', '__return_false');
			$api_url 	= "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$screen_name&count=$tweets_count";
			$response 	= wp_remote_get($api_url, $args);
	 
			if (!is_wp_error($response)) {
				$tweets = json_decode(wp_remote_retrieve_body($response));

				if(!empty($tweets)){
					for($i=0; $i<count($tweets); $i++){
						$recent_tweets[] = array(
							'text' 			=> $tweets[$i]->text, 
							'created_at' 	=> $tweets[$i]->created_at, 
							'status_id' 	=> $tweets[$i]->id_str
						);
					}
				}			
			}
			
			// cache for an hour
			set_transient('warriorRecentTweets'.$screen_name, $recent_tweets, 1*60*60);
		}

		return $recent_tweets;

	}
}

/**
 * Function to set author page
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

if( ! function_exists('author_filter') ) {
function author_filter($query) {
    if ($query->is_author()) {
    	$query->set('post_type', array('post', 'knowledge_base'));
    }
}
}
add_action('pre_get_posts','author_filter');

/**
 * Function to replace replace permalink on tweet
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

if( ! function_exists('warrior_twitter_links') ) {
	function warrior_twitter_links($tweet_text) {
		$tweet_text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet_text);
		$tweet_text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet_text);
		$tweet_text = preg_replace("/@(\w+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet_text);
		$tweet_text = preg_replace("/#(\w+)/", "<a href=\"http://twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet_text);
		return $tweet_text;
	}
}

/**
 * Change default excerpt more text
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if( !function_exists( 'warrior_excerpt_more ') ) {
	function warrior_excerpt_more( ) {
		return ' ...';
	}
}
add_filter( 'excerpt_more', 'warrior_excerpt_more', 999 );

/**
 * Warrior Breadcrumb function
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

if ( ! function_exists( 'warrior_the_breadcrumb' ) ) {
	function warrior_the_breadcrumb() {
		if (!is_home()) {
			echo '<li><a href="';
			echo esc_url(home_url());
			echo '">';
			echo __('Home', 'helper');
			echo "</a></li>";
			if (is_category()) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Category : ', 'helper');
				echo '</li> ';
				echo '<li class="breadcrumb_current">';
				the_category('title_li=');
				echo '</li>';
			} elseif ( is_tag() || get_post_type() == 'post' ) {
				if (!is_tag() && !is_author()) {
					echo '<li class="separator">/</li>';
					echo '<li>';
					echo __('Category : ', 'helper');
					echo '</li> ';
					echo '<li class="breadcrumb_current">';
					the_category('title_li=');
					echo '</li>';
					echo '<li class="separator">/</li>';
					echo '<li class="breadcrumb_current">';
					the_title();
					echo '</li>';
				}
				if (is_tag()) {
					echo '<li class="separator">/</li>';
					echo '<li>';
					echo __('Tags : ', 'helper');
					echo '</li>';
					echo '&nbsp;';
					echo '<li class="breadcrumb_current">';
					single_tag_title();
					echo '</li>';
				}
				if ( is_author() ) {
					global $wp_query;
					$curauth = $wp_query->get_queried_object();
					echo '<li class="separator">/</li>';
					echo '<li>';
					echo __('Author : ', 'helper');
					echo '</li>';
					echo '&nbsp;';
					echo '<li class="breadcrumb_current">';
					echo $curauth->display_name;
					echo '</li>';
				}
			} elseif ( is_single() && get_post_type() == 'knowledge_base' ) {
				if (!is_tax('kb_tag')) {
					echo '<li class="separator">/</li>';
					echo '<li>';
					echo __('Knowledge Base : ', 'helper');
					echo '</li> ';
					echo '<li class="breadcrumb_current">';
					$terms = get_the_terms( get_the_ID() , 'kb_category' );
					if( !empty($terms) ) {
						foreach ( $terms as $term ) :
						  echo $term->name;
						endforeach;
					}
					echo '</li>';
				}
				if ( is_single() ) {
					echo '<li class="separator">/</li>';
					echo '<li class="breadcrumb_current">';
					the_title();
					echo '</li>';
				}
				if (is_tax('kb_tag')) {
					echo '<li class="separator">/</li>';
					echo '<li>';
					echo __('Knowledge Base Tags : ', 'helper');
					echo '</li>';
					echo '&nbsp;';
					echo '<li class="breadcrumb_current">';
					single_tag_title();
					echo '</li>';
				}
			} elseif (is_page()) {
				echo '<li class="separator">/</li>';
				echo '<li class="breadcrumb_current">';
				echo the_title();
				echo '</li>';
			} elseif (is_tag()) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Tags : ', 'helper');
				echo '</li>';
				echo '&nbsp;';
				echo '<li class="breadcrumb_current">';
				single_tag_title();
				echo '</li>';
			} elseif (is_author()) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Post by ', 'helper');
				echo '</li>';
				echo '&nbsp;';
				echo '<li class="breadcrumb_current">';
				the_author_posts_link();
				echo '</li>';
			} elseif (is_day()) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Archive on ', 'helper');
				echo '</li>';
				echo '&nbsp;';
				echo '<li class="breadcrumb_current">';
				echo get_the_time('F jS, Y');
				echo '</li>';
			} elseif (is_month()) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Archive on ', 'helper');
				echo '</li>';
				echo '&nbsp;';
				echo '<li class="breadcrumb_current">';
				echo get_the_time('F, Y');
				echo '</li>';
			} elseif (is_year()) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Archive on ', 'helper');
				echo '</li>';
				echo '&nbsp;';
				echo '<li class="breadcrumb_current">';
				echo get_the_time('Y');
				echo '</li>';
			} elseif( is_tax( 'kb_category' ) ) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Knowledge Base : ', 'helper');
				echo '</li>';
				$category = get_queried_object();
				echo ' <li class="breadcrumb_current"><a href="'.get_term_link($category->slug, "kb_category").'">'.$category->name.'</a></li>';
			} elseif( is_tax( 'kb_tag' ) ) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Knowledge Base : ', 'helper');
				echo '</li >';
				$tag = get_queried_object();
				echo ' <li class="breadcrumb_current"><a href="'.get_term_link($tag->slug, "kb_tag").'">'.$tag->name.'</a></li>';
			} elseif ( is_search() ) {
				echo '<li class="separator">/</li>';
				echo '<li>';
				echo __('Search result : ', 'helper');
				echo '</li>';
				echo '&nbsp;';
				echo '<li class="breadcrumb_current">';
				echo get_search_query();
				echo '</li>';
			} elseif (get_post_type() == 'forum') {
				echo '<li class="separator">/</li>';
				echo '<li class="breadcrumb_current">';
				the_title();
				echo '</li>';
			}
			elseif (get_post_type() == 'topic') {
				echo '<li class="separator">/</li>';
				echo '<li class="breadcrumb_current">';
				the_title();
				echo '</li>';
			}
			elseif (get_post_type() == 'reply') {
				echo '<li class="separator">/</li>';
				echo '<li class="breadcrumb_current">';
				the_title();
				echo '</li>';
			} elseif (is_404()) {
				echo '<li class="separator">/</li>';
				echo '<li class="breadcrumb_current">';
				echo __('Error 404 : Page not found', 'helper');
				echo '</li>';
			}
		}

	}	
}

/**
 * Function to collect the title of the current page
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
if ( ! function_exists( 'warrior_get_taxonomy' ) ) {
	function warrior_get_taxonomy() {
		global $wp_query;

		$title = '';
		if ( is_category() ) :
			$title = sprintf( single_cat_title( '', false ) );
		elseif ( is_tag() ) :
			$title = sprintf( single_tag_title( '', false ) );
		elseif ( is_author() ) :
			if ( get_post_type() == "post" ) :
			$category = get_the_category(); 
			$title = $category[0]->cat_name;
			
			else :

			$terms = get_the_terms( get_the_ID() , 'kb_category' );
			if(isset($terms)) :
	            if( !empty($terms) ) :
	                foreach ( $terms as $term ) :
	                  $title = $term->name;
	                endforeach;
	            endif;
	        endif;  

	        endif;

		elseif ( is_tax('kb_category') ) :
			$title = sprintf( single_cat_title( '', false ) );
		elseif ( is_tax('kb_tag') ) :
			$title = sprintf( single_tag_title( '', false ) );
		elseif ( is_day() ) :
			$title = sprintf( date_i18n('M d', strtotime( get_the_date() ) ) );
		elseif ( is_month() ) :
			$title = sprintf( date_i18n( 'F Y', strtotime( get_the_date() ) ) );
		elseif ( is_year() ) :
			$title = sprintf( date_i18n( 'Y', strtotime( get_the_date() ) ) );
		elseif ( is_author() ) :
			$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
			$title = sprintf( get_the_author_meta( 'display_name', $author->ID ) );
		elseif ( is_search() ) :
			if ( $wp_query->found_posts ) {
				$title = sprintf( __( 'Search Results for: "%s"', 'helper' ), esc_attr( get_search_query() ) );
			} else {
				$title = sprintf( __( 'No Results for: "%s"', 'helper' ), esc_attr( get_search_query() ) );
			}
		elseif ( is_404() ) :
			$title = __( 'Not Found', 'helper' );
		else :
			$category = get_the_category(); 
			$title = $category[0]->cat_name;
		endif;
		
		return $title;
	}
}
?>