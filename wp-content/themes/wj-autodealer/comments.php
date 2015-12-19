<?php
/**
 * Comments Template
 *
 * @package WinterJuice
 * @package CLeanLab
 */
	if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die( __( 'Please do not load this page directly.', AT_TEXTDOMAIN ) );
	if ( !post_type_supports( get_post_type(), 'comments' ) || ( !have_comments() && !comments_open() && !pings_open() ) )
		return;
	if ( post_password_required() ) : ?>
		<h3 class="comments-header"><?php _e( 'Password Protected', AT_TEXTDOMAIN ); ?></h3>
		<p class="alert password-protected">
			<?php _e( 'Enter the password to view comments.', AT_TEXTDOMAIN ); ?>
		</p><!-- .alert .password-protected -->
		<?php return; ?>
	<?php endif; ?>
<?php if ( have_comments() ) : ?>
	<div id="comments" class="comments">
	<h4><?php _e('Comments', AT_TEXTDOMAIN); ?></h4>
		<?php echo apply_filters( 'at_comments_title', '<h3 id="comments-title">' . sprintf( _n( '1 Comment', '%1$s Comments', get_comments_number(), AT_TEXTDOMAIN ), number_format_i18n( get_comments_number() ), get_the_title() ) . '</h3>', array( 'comments_number' => get_comments_number(), 'title' =>  get_the_title() ) ); ?>
		<ul class="commentlist">
			<?php wp_list_comments( array( 'format' => 'html5', 'type' => 'all', 'avatar_size' => 64 ) ); ?>
		</ul>
		<?php if ( get_option( 'page_comments' ) ) : ?>
			<div class="comment-navigation paged-navigation">
				<?php paginate_comments_links(); ?>
			</div><!-- .comment-navigation -->
		<?php endif; ?>


	</div><!-- #comments -->
<?php endif; ?>
<?php 
	$args = array( 
		'id_submit'         => 'comment-btn',
		'label_submit'	=> __( 'Post Comment', AT_TEXTDOMAIN ),
		'title_reply' 	=> __( 'Leave a Comment', AT_TEXTDOMAIN )
	);
	comment_form( $args ); 
?>