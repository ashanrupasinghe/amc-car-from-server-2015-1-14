<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_widget('header_widget'); ?>
<?php echo isset($block['breadcrumbs']) ? $block['breadcrumbs'] : ''; ?>
<?php echo isset($block['page_title']) ? $block['page_title'] : ''; ?>
<div class="main_wrapper">
	<div class="sidebar"><?php $this->add_widget('sidebar_widget', array( 'layout' => 'left_content' )); ?></div>
	<?php echo $block['content']; ?>
	<div class="clear mb1"></div>
</div>
<?php $this->add_widget('footer_widget'); ?>