<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<span class="min"><?php echo $min; ?></span>
		<input name="<?php echo $name; ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>" class="range-input-selector range-input-composer" type="range" value="<?php echo $value; ?>"/>
		<span class="max"><?php echo $max; ?></span><br/>
		<span class="value"><?php echo $value; ?></span>
		<span class="unit"><?php echo $unit; ?></span>
		<br>
	</div>
	<div class="clear"></div>
</div>