<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php //$this->add_template('general/header'); ?>
<?php $this->add_widget('header_widget', array( 'content_auto_width' => true )); ?>
<?php echo isset($block['breadcrumbs']) ? $block['breadcrumbs'] : ''; ?>
<?php echo $block['content']; ?>
<?php $this->add_widget('footer_widget'); ?>