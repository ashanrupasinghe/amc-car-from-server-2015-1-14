<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set <?php echo $toggle_class; ?>">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<textarea type="text" name="<?php echo $name; ?>" class="<?php echo isset($class) ? $class : ''; ?>"><?php echo $value; ?></textarea>
		<br>
	</div>
	<div class="clear"></div>
</div>