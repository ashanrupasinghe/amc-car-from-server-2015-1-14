<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $upload_dir = wp_upload_dir(); ?>
<div class="theme_option_set one-row">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<a class="button pushtocall" data-action="<?php echo $action; ?>"><?php echo $button; ?></a>
	</div>
	<div class="clear"></div>
</div>