<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set <?php echo $toggle_class; ?>">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<input type="checkbox" name="<?php echo $name; ?>" value="1" <?php if($value) echo 'checked="checked"'; ?> class="<?php echo $toggle; ?>" />
		<br>
	</div>
	<div class="clear"></div>
</div>