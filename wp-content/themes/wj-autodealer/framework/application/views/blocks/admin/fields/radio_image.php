<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set radio_image">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<?php foreach ($items as $item_key => $item_src) { ?>
			<label for="<?php echo $name . '_' . $item_key; ?>">
				<img <?php echo !empty($width) ? 'style="width:' . $width . '"' : ''; ?> src="<?php echo $item_src; ?>" <?php if( $item_key == $value ) echo 'class="active"'; ?>>
				<input <?php if( $item_key == $value ) echo 'checked="checked"'; ?> type="radio" name="<?php echo $name; ?>" value="<?php echo $item_key; ?>" id="<?php echo $name . '_' . $item_key; ?>" />
			</label>
		<?php } ?>
		<br>
	</div>
	<div class="clear"></div>
</div>