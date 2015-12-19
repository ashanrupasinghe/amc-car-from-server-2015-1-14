<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_controls">
	<div class="theme_button_group">
		<div class="theme_admin_save">
			<input type="button" class="button item_add" value="<?php echo __( 'Add Item', AT_ADMIN_TEXTDOMAIN ); ?>" name="submit">
		</div>
		<div class="spinner"><?php echo __( 'Saving...', AT_ADMIN_TEXTDOMAIN ); ?></div>
	</div>
	<h2 id="current_section_title"><?php echo __('Reference Tables', AT_ADMIN_TEXTDOMAIN); ?></h2>
</div>
<div id="dialog-form-reference" data-tab="<?php echo $tab; ?>" title="<?php echo __( 'Edit/Add Item', AT_ADMIN_TEXTDOMAIN );?>" style='display:none;'></div>
<?php echo $block['tabs']; ?>
<?php echo $block['items']; ?>