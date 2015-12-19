<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set one-row type_group">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<div class="btn submit_add"><?php echo $submit; ?></div>
		<p><?php echo $description; ?></p>
		<div class="clear"></div>
	</div>
	<div class="theme_option">
		<ul class="group_sortable group_items menu">
			<?php echo $block['items']; ?>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="theme_option_footer">
		<div class="btn submit_add"><?php echo $submit; ?></div>
		<div class="clear"></div>
	</div>
</div>