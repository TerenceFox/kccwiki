<?php 
/**
 * The template for displaying comments
 *
 * @package WordPress
 * @subpackage Helper
 * @since Helper 1.0.0
 */

// Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die (_e('Please do not load this page directly. Thanks!', 'helper'));

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'helper') ; ?></p>
<?php
		return;
		}
	}
?>

<?php if ( have_comments() ) : ?>

    <!-- START: COMMENT LIST -->
   <div class="comments-widget">
        <div class="block">
    		<h4 class="widget-title"><?php comments_number( __('No Comments', 'helper'), __('1 Comment', 'helper'), __('% Comments', 'helper') ); ?></h4>
            <div class="comments-list">
                <?php wp_list_comments('callback=warrior_comment_list'); ?>
            </div>
            
    		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
    			<div class="navigation clearfix">
    				<span class="prev"><?php previous_comments_link(__('&larr; Previous', 'helper'), 0); ?></span>
    				<span class="next"><?php next_comments_link(__('Next &rarr;', 'helper'), 0); ?></span>
    			</div>	
    		<?php endif; ?>
    	</div>	
    </div>
    <!-- END: COMMENT LIST -->
    
<?php else : // or, if we don't have comments: ?>
<?php endif; // end have_comments() ?> 

	<!-- START: RESPOND -->
    <?php if ( comments_open() ) : ?>
        <div id="comment-form" class="post-comment-form widget">
            <div class="content-inner">
                <?php 
                    comment_form( array(
                        'title_reply'			=>	'<h4 class="widget-title">'. __( 'Leave a Comment', 'helper' ) .'</h4>',
                        'comment_notes_before'	=>	'',
                        'comment_notes_after'	=>	'',
                        'label_submit'			=>	__( 'Submit Comment', 'helper' ),
                        'title_reply_to'    => __( 'Leave a Reply to %s', 'helper' ),
                        'cancel_reply_link' => __( '<i class="typcn typcn-delete"></i> Cancel Reply', 'helper' ),
            			'logged_in_as'			=>  '<p class="logged-user">' . sprintf( __( 'You are logged in as <a href="%1$s">%2$s</a> &#8212; <a href="%3$s">Logout &raquo;</a>', 'helper' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
                        'fields'				=> array(
                            'author'				=>	'<div class="form-group col-50"><label for="fullname"><span>'.sprintf( __('Fullname', 'helper')).'</span><input type="text" name="author" id="fullname" class="input" value="" placeholder="'.sprintf( __('Fullname', 'helper')).'"/></label></div>',
                            'email'					=>	'<div class="form-group col-50"><label for="fullname"><span>'.sprintf( __('Email', 'helper')).'</span><input type="text" name="email" id="email" class="input" value="" placeholder="'.sprintf( __('Email', 'helper')).'"/></label></div>',
                            'url'					=>	'<div class="form-group col-50"><label for="weburl"><span>'.sprintf( __('Website Url', 'helper')).'</span><input type="text" name="url" id="weburl" class="input" value="" placeholder="'.sprintf( __('Website Url', 'helper')).'"/></label></div>'
            									),
                        'comment_field'			=>	'<div class="form-group col-100"><label for="message"> <span>'.sprintf( __('Message', 'helper')).'</span><textarea name="comment" id="message" class="input textarea" placeholder="'.sprintf( __('Message', 'helper')).'"></textarea></label></div>'
                    ));
                ?>
            </div>   
        </div>
	<?php endif; ?>
 	<!-- END: RESPOND -->