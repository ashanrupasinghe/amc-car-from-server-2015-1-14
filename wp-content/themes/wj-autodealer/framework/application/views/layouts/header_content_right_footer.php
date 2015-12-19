<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_widget('header_widget'); ?>
<?php echo isset($block['breadcrumbs']) ? $block['breadcrumbs'] : ''; ?>
<?php echo isset($block['page_title']) ? $block['page_title'] : ''; ?>
<div class="main_wrapper">
	<?php echo $block['content']; ?>
	<div class="sidebar">
		<?php 
			if ( isset( $block['right_side'] ) ) { 
				echo $block['right_side']; 
			} else {
				$this->add_widget('sidebar_widget', array( 'layout' => 'content_right' )); 
			}
		?>
	</div>
	<div class="clear mb1"></div>
</div>
<?php $this->add_widget('footer_widget'); ?>