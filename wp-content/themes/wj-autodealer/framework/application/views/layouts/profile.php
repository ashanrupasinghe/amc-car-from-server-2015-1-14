<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_widget('header_widget'); ?>
<?php echo isset($block['breadcrumbs']) ? $block['breadcrumbs'] : ''; ?>
<div class="main_wrapper profile_layout">
	<div class="sidebar"><?php echo $block['left_side']; ?></div>
	<?php echo $block['content']; ?>
	<div class="clear mb1"></div>
</div>
<?php $this->add_widget('footer_widget'); ?>