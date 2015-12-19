<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
		<br>
	</div>
	<div class="clear"></div>
</div>