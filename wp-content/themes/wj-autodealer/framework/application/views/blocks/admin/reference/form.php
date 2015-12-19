<div class="popup-form">
	<form action="admin.php?page=at_reference&tab=<?php echo $tab; ?>" method="post" id="add-edit-reference" data-id="<?php echo (!empty($id) ? $id : ''); ?>">
		<div class="form-row">
			<div class="label"><?php echo __( 'Name', AT_ADMIN_TEXTDOMAIN); ?></div>
			<div class="field"><input type="text" class="text" name="name" id="name" value="<?php echo (!empty($item['name']) ? $item['name'] : ''); ?>" /></div>
		</div>
		<?php if($get_alias) { ?>
			<div class="form-row">
				<div class="label"><?php echo __( 'Alias', AT_ADMIN_TEXTDOMAIN); ?></div>
				<div class="field">
					<?php if ( $tab == 'transport_types' ) { ?>
						<select name="alias" id="alias">
						<?php foreach ($item['icons'] as $key => $icon) {
							echo '<option value="' . $icon['class'] . '" ' . ( !empty($item['alias']) && ($icon['class'] == $item['alias']) ? 'selected="selected"' : '' ) . ' >' . $icon['name'] . '</option>';
						}?>
					</select>
					<?php } else { ?>
						<input type="text" name="alias" id="alias" value="<?php echo (!empty($item['alias']) ? $item['alias'] : ''); ?>" /></div>
					<?php } ?>
			</div>
		<?php }?>
		<?php if ( $tab == 'equipments' ) { ?>
		<input type="hidden" name="action" value="<?php echo (!empty($item['alias']) ? 'save_item' : 'add_item'); ?>">
		<input type="hidden" name="item_id" value="<?php echo (!empty($item['alias']) ? $item['alias'] : ''); ?>">
		<?php } else { ?>
		<input type="hidden" name="action" value="<?php echo (!empty($item['id']) ? 'save_item' : 'add_item'); ?>">
		<input type="hidden" name="item_id" value="<?php echo (!empty($item['id']) ? $item['id'] : ''); ?>">
		<?php if (!empty($item['manufacturer_id'])) { ?>
		<input type="hidden" name="manufacturer_id" value="<?php echo $item['manufacturer_id']; ?>">
		<?php } ?>

		<?php if (!empty($item['region_id'])) { ?>
		<input type="hidden" name="region_id" value="<?php echo $item['region_id']; ?>">
		<?php } ?>
		<?php } ?>
		<input type="hidden" name="tab" value="<?php echo $tab; ?>">
	</form>
	<div class="spinner popup-modal"><?php echo __( 'Saving data...', AT_ADMIN_TEXTDOMAIN ); ?></div>
</div>