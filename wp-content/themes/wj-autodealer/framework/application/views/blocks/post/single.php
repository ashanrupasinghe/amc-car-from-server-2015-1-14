<?php wp_enqueue_script( 'comment-reply' ); ?>
<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="blog layout_<?php echo $layout; ?>">
		<div id="post-<?php the_ID(); ?>" <?php post_class('blog_single_post'); ?>>
			<?php $this->add_widget( 'post_widget', array( 'data' => 'meta', 'class' => 'meta grey_area' ) ); ?>
			<div class="post">
				<?php 
                if ( get_post_meta( get_the_ID(), '_featured_video', true ) ) {
					global $wp_embed;
					echo '<div class="video">';
					$post_embed = $wp_embed->run_shortcode('[embed]' . get_post_meta( get_the_ID(), '_featured_video', true ) . '[/embed]');
					echo $post_embed;
					echo '</div>';
                } else {
					if ( !get_post_meta( get_the_ID(), '_disable_post_image', true ) ) {
						$this->add_widget( 'post_widget', array( 'data' => 'thumbnail', 'size' => 'full', 'is_preview' => true ) );
					}
                }
                ?>
				<div class="post-content">
					<?php the_content(); ?>
					<div class="clearboth"></div>
					<?php wp_link_pages( array(
						'before' => '<ul class="page_navigation pagination">',
						'after' => '</ul>',
						'link_before'      => '<li>',
						'link_after'       => '</li>',
						'echo' => true
					) ); ?>
					<!-- <h3><?php _e( 'Continue reading', AT_TEXTDOMAIN ) ?></h3> -->
					<!-- <?php edit_post_link( '<i class="icon-code"></i>' . __( 'Edit this post', AT_TEXTDOMAIN ), '<div class="edit_link">', '</div>' ); ?> -->
					<?php next_posts_link(); ?>
					<?php previous_posts_link(); ?>

					<?php comments_template( '', true ); ?>

				</div>
			</div>
		</div>
	</div>
<?php endwhile; ?>
