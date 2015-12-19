<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'jquery.datetimepicker', 'assets/js/jquery/jquery.datetimepicker.js', array( 'jquery' ) ); ?>
<?php $this->add_style( 'datetimepicker.css', 'assets/css/jquery/jquery.datetimepicker.css'); ?>
<div class="theme_option_set">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<input class="datetimepicker" data-min-date="<?php echo $min_date; ?>" data-format="<?php echo $format; ?>" type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
		<br>
	</div>
	<div class="clear"></div>
</div>