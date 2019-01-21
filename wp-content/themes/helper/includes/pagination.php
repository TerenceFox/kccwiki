<?php
/**
 * The Template for displaying pagination section
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */
?>
<?php global $wp_query; ?>

<?php if($wp_query->max_num_pages > 1) : ?>
	<!-- Start : Pagination section -->
	 <div class="pagination">
        <div class="pagination-holder">
    	<?php
			if( function_exists('wp_pagenavi') ) {
				wp_pagenavi();
			} else {
				next_posts_link( '<i class="typcn typcn-arrow-right"></i>' );
				previous_posts_link( '<i class="typcn typcn-arrow-left"></i>' );
			}
		?>
        </div>
    </div>
	<!-- End : Pagination section -->
<?php endif; ?>