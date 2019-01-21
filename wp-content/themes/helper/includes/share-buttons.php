<?php
/**
 * Template to display sharing buttons
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

global $helper_option;
?>

<!-- Start : Share Buttons section -->
<div class="share-widget social share-widget">
	<div class="prints">		
		<ul>
			<li><a href="#" onclick="window.print();return false;" class="print-this print-link"><i class="typcn typcn-printer"></i> <?php _e('Print', 'helper'); ?></a></li>
		</ul>
	</div>
	<div class="socials">
			<b>Share this</b>
		<ul>
			<li><a class="addthis_button_facebook" fb:like:layout="button_count"><i class="typcn typcn-social-facebook"></i></a></li>
			<li><a class="addthis_button_twitter"><i class="typcn typcn-social-twitter"></i></a></li>
			<li><a class="addthis_button_google_plusone_share" g:plusone:size="medium"><i class="typcn typcn-social-google-plus"></i></a></li>
			<li><a class="addthis_button_pinterest_share" pi:pinit:layout="horizontal" pi:pinit:url="<?php echo get_permalink( $post->ID ); ?>"><i class="typcn typcn-social-pinterest"></i></a></li>
		</ul>

	</div>
</div>

<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>
<!-- End : Share Buttons section -->