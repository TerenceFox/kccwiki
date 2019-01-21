<?php
/**
 * Twitter tweets widget
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'warrior_twitter_widget' );

// Register our widget
function warrior_twitter_widget() {
	register_widget( 'Warrior_Twitter' );
}

// Warrior Twitter Widget
class Warrior_Twitter extends WP_Widget {

	//  Setting up the widget
	function Warrior_Twitter() {
		$widget_ops  = array( 'classname' => 'twitter', 'description' => __('Display latest tweets from Twitter.', 'helper') );
		$control_ops = array( 'id_base' => 'warrior_twitter' );

		parent::__construct( 'warrior_twitter', __('Latest Twitter Tweets', 'helper'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$warrior_twitter_title        = apply_filters( 'widget_title', empty( esc_attr( $instance['warrior_twitter_title'] ) ) ? __( 'Latest Tweets', 'helper' ) : esc_attr( $instance['warrior_twitter_title'] ), $instance, $this->id_base );
		$warrior_twitter_username     = esc_attr( $instance['warrior_twitter_username'] );
		$warrior_twitter_tweets_count = ! empty( $instance['warrior_twitter_tweets_count'] ) ? absint( $instance['warrior_twitter_tweets_count'] )  : 1;
		$warrior_twitter_button_text  = esc_attr( $instance['warrior_twitter_button_text'] );
		$twitter_consumer_key		  = esc_attr( $instance['twitter_consumer_key'] );
		$twitter_consumer_secret	  = esc_attr( $instance['twitter_consumer_secret'] );
		
		$tweets = warrior_get_recent_tweets( $warrior_twitter_username, $twitter_consumer_key, $twitter_consumer_secret, $warrior_twitter_tweets_count );

		echo $before_widget;
?>

		<?php echo $before_title . $warrior_twitter_title . $after_title; ?>

        <div id="warrior-twitter">
			<ul id="tweets">
			<?php if ( $tweets ) : ?>
				<?php foreach ( $tweets as $tweet ) : ?>
					<li class="tweet">
						<?php echo esc_url( twitter_links($tweet['text']) ); ?>
						<div class="meta"><span><i class="fa fa-clock-o"></i> <a href="https://twitter.com/<?php echo $warrior_twitter_username .'/status/'. $tweet['status_id'];?>" target="_blank"><?php echo date('F jS, Y', strtotime($tweet['created_at']) ); ?></a></span></div>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
	        </ul>
        </div>

        <a class="follow" target="_blank" href="https://twitter.com/<?php echo $warrior_twitter_username;?>"><?php echo $warrior_twitter_button_text; ?></a>

<?php
	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['warrior_twitter_title']			= strip_tags( $new_instance['warrior_twitter_title'] );
		$instance['warrior_twitter_username']		= esc_attr( $new_instance['warrior_twitter_username'] );
		$instance['warrior_twitter_tweets_count']	= esc_attr( $new_instance['warrior_twitter_tweets_count'] );
		$instance['warrior_twitter_button_text']	= esc_attr( $new_instance['warrior_twitter_button_text'] );
		$instance['twitter_consumer_key']			= esc_attr( $new_instance['twitter_consumer_key'] );
		$instance['twitter_consumer_secret']		= esc_attr( $new_instance['twitter_consumer_secret'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('warrior_twitter_title' => __('Latest Tweets', 'helper'), 'warrior_twitter_username' => 'themewarrior', 'warrior_twitter_tweets_count' => '1', 'warrior_twitter_button_text' => __('Follow me on Twitter', 'helper'), 'twitter_consumer_key' => '', 'twitter_consumer_secret' => '' ) );
	?>
        <p>
            <label for="<?php echo $this->get_field_id( 'warrior_twitter_title' ); ?>"><?php _e('Widget Title:', 'helper'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'warrior_twitter_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'warrior_twitter_title' ); ?>" value="<?php echo esc_attr( $instance['warrior_twitter_title'] ); ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'warrior_twitter_username' ); ?>"><?php _e('Twitter Username:', 'helper'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'warrior_twitter_username' ); ?>" name="<?php echo $this->get_field_name( 'warrior_twitter_username' ); ?>" value="<?php echo esc_attr( $instance['warrior_twitter_username'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'warrior_twitter_tweets_count' ); ?>"><?php _e('Tweets Count:', 'helper'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'warrior_twitter_tweets_count' ); ?>" name="<?php echo $this->get_field_name( 'warrior_twitter_tweets_count' ); ?>" value="<?php echo esc_attr( $instance['warrior_twitter_tweets_count'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'warrior_twitter_button_text' ); ?>"><?php _e('Button Text:', 'helper'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'warrior_twitter_button_text' ); ?>" name="<?php echo $this->get_field_name( 'warrior_twitter_button_text' ); ?>" value="<?php echo esc_attr( $instance['warrior_twitter_button_text'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_consumer_key' ); ?>"><?php _e('Twitter Consumer key:', 'helper'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'twitter_consumer_key' ); ?>" value="<?php echo esc_attr( $instance['twitter_consumer_key'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_consumer_secret' ); ?>"><?php _e('Twitter Consumer secret:', 'helper'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'twitter_consumer_secret' ); ?>" value="<?php echo esc_attr( $instance['twitter_consumer_secret'] ); ?>" />
		</p>
		<p><small><?php _e('You can get Twitter consumer key & consumer secret by <a href="http://dev.twitter.com" target="_blank">creating an app</a> on Twitter.', 'helper'); ?></small></p>
		<p><small><?php _e('Note* Can only be used for accounts that are not protected or private !!!.', 'helper'); ?></small></p>
	<?php
	}
}


function warrior_get_recent_tweets( $screen_name = '', $consumer_key = '', $consumer_secret = '', $tweets_count = 3 ) {

	// some variables
	$token = get_option('warriorTwitterToken');

	// get recent tweets from cache
	$recent_tweets = get_transient('warriorRecentTweets');

	// cache version does not exist or expired
	if (false === $recent_tweets) {

		// getting new auth bearer only if we don't have one
		if(!$token) {

			// preparing credentials
			$credentials = $consumer_key . ':' . $consumer_secret;
			$toSend = base64_encode($credentials);
 
			// http post arguments
			$args = array(
				'method' => 'POST',
				'httpversion' => '1.1',
				'blocking' => true,
				'headers' => array(
					'Authorization' => 'Basic ' . $toSend,
					'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
				),
				'body' => array( 'grant_type' => 'client_credentials' )
			);
 
			add_filter('https_ssl_verify', '__return_false');
			$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);

			$keys = json_decode(wp_remote_retrieve_body($response));

			if($keys) {
				// saving token to wp_options table
				update_option('warriorTwitterToken', $keys->access_token);
				$token = $keys->access_token;
			}
		}

		// we have bearer token wether we obtained it from API or from options
		$args = array(
			'httpversion' => '1.1',
			'blocking' => true,
			'headers' => array(
				'Authorization' => "Bearer $token"
			)
		);

		add_filter('https_ssl_verify', '__return_false');
		$api_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$screen_name&count=$tweets_count";
		$response = wp_remote_get($api_url, $args);
 
		if (!is_wp_error($response)) {
			$tweets = json_decode(wp_remote_retrieve_body($response));

			if(!empty($tweets)){
				for($i=0; $i<count($tweets); $i++){
					$recent_tweets[] = array('text' => $tweets[$i]->text, 'created_at' => $tweets[$i]->created_at, 'status_id' => $tweets[$i]->id_str);
				}
			}			
		}else{
			_e('Error Notice !!! This account is protected, Please unprotect this account, to see your tweets !', 'helper');
		}
		
		// cache for an hour
		set_transient( 'warriorRecentTweets', $recent_tweets, 1*60*60 );
    }

	return $recent_tweets;

}

function twitter_links( $tweet_text ) {
	$tweet_text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet_text);
	$tweet_text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet_text);
	$tweet_text = preg_replace("/@(\w+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet_text);
	$tweet_text = preg_replace("/#(\w+)/", "<a href=\"http://twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet_text);
	return $tweet_text;
}

?>