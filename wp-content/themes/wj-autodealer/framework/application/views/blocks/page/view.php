<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="blog layout_<?php echo $layout; ?>">
		<div class="blog_single_post" id="post-<?php the_ID(); ?>">
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
						if ( has_post_thumbnail() && !get_post_meta( get_the_ID(), '_disable_post_image', true ) ) {
							the_post_thumbnail( 'full' );
						}
					}
				}
				the_content();
			?>
			</div>
		</div>
	</div>
<?php endwhile; ?>
