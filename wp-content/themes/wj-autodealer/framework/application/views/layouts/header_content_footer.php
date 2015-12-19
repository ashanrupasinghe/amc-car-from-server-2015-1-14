<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_widget('header_widget'); ?>
<?php echo isset($block['breadcrumbs']) ? $block['breadcrumbs'] : ''; ?>
<?php echo isset($block['page_title']) ? $block['page_title'] : ''; ?>
<div class="main_wrapper">
	<?php echo $block['content']; ?>
	<div class="clear"></div>
</div>
<?php $this->add_widget('footer_widget'); ?>