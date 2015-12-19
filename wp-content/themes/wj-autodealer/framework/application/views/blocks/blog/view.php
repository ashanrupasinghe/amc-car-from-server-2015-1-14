<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php
	function my_excerpt_length($length) {
		return 15;
	}
	add_filter('excerpt_length', 'my_excerpt_length');
?>
<div class="blog layout_<?php echo $layout; ?>">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<?php $p_class = 'blog_post';
	if ( has_post_thumbnail() && !get_post_meta( get_the_ID(), '_disable_post_image', true ) ) {
		$p_class .= ' has_post_thumbnail';
	 } else {
	 	$p_class .= ' no_post_thumbnail';
	 }
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class( $class=$p_class ); ?>>
		<?php $this->add_widget( 'post_widget', array( 'data' => 'thumbnail', 'size' => array( 180, 150 ), 'is_link' => true ) ); ?>
		<div class="blog_desc">
			<h4><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?> <?php echo get_post_meta( get_the_ID(), '_page_tagline', true ); ?></a></h4>
			<?php $this->add_widget( 'post_widget', array( 'data' => 'meta', 'class' => 'grey_area' ) ); ?>
			<div class="post"><?php the_excerpt(); ?></div>
			<a href="<?php echo get_permalink(); ?>" class="more markered"><?php echo __( 'Read more', AT_TEXTDOMAIN ); ?></a>
		</div>
	</div>
<?php endwhile; ?>
<?php if( isset( $block['pagination'] ) ) echo $block['pagination']; ?>

<div class="clear"></div>
</div>