<?php
/**
 * Warrior Advanced Search
 *
 * This file contains Advanced Search widget
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>
<?php global $helper_option; ?>

<?php if( $helper_option['general_display_advanced_search'] ) : ?>
<div id="search-widget">
	<div class="container">
		<div class="row search-widget">
			<form role="search" method="get" id="warrior-advanced-search" action="<?php echo esc_url(home_url( '/' )); ?>" >
				<div class="input-holder input-term">
					<input type="text" name="s" id="s" placeholder="<?php _e('Enter a search term...', 'helper'); ?>" class="input s" value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off"/>
					<i class="live-search-reset typcn typcn-backspace-outline"></i>
				</div>
				<button type="submit" class="button blue large searchbutton">
				<?php _e('Search', 'helper'); ?>
				</button>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	'use strict';
	jQuery('#warrior-advanced-search #s').liveSearch({
		url: '<?php echo home_url("/?ajax=1&s="); ?>',
		loadingClass: 'loading',
	});
});	
</script>
<?php endif; ?>