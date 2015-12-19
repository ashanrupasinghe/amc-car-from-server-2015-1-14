<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<select name="<?php echo $name; ?>" <?php echo isset($id) ? 'id="' . $id . '"' : '';  ?> class="<?php if($sub_fields) echo 'sub_fields_change_select'; ?>" data-sub-fields-group="<?php if($sub_fields) echo $sub_fields; ?>" >
			<?php if( !$first_not_view ) { ?>
			<option value=""><?php echo __( 'Select option', AT_ADMIN_TEXTDOMAIN ) ?></option>
			<?php } ?>
		<?php foreach ($items as $item_key => $item_value) { ?>
			<option value="<?php echo $item_key; ?>" <?php if( $item_key == $value ) echo 'selected'; ?>><?php echo $item_value; ?></option>
		<?php } ?>
		</select>
		<br>
	</div>
	<div class="clear"></div>
</div>