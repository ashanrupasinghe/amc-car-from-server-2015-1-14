<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'admin-models', 'assets/js/admin/reference/models.js'); ?>
<?php $this->add_script( 'admin-users', 'assets/js/admin/reference/reference.js');?>
<?php $this->add_style( 'jquery-ui', 'assets/css/admin/jquery-ui.min.css');?>
<?php $this->add_style( 'jquery-ui-theme', 'assets/css/admin/jquery-ui.theme.css');?>
<div id="dialog-confirm-delete" data-tab="models" title="<?php echo __( 'Delete this Item?', AT_ADMIN_TEXTDOMAIN );?>" style='display:none;'>
	</span><?php echo __( 'These items will be permanently deleted and cannot be recovered. Are you sure?', AT_ADMIN_TEXTDOMAIN);?>
</div>
<div class="theme_option theme-table-filters">
	<div class="field">
		<label><?php echo __( 'Make:', AT_ADMIN_TEXTDOMAIN ); ?></label>
		<select id="select_manufacturer">
			<option value="0"><?php echo __( 'Select Make', AT_ADMIN_TEXTDOMAIN ); ?></option>
			<?php foreach ($additionals['manufacturers'] as $key => $item) { ?>
			<option value="<?php echo $item['id']; ?>" <?php if ($additionals['manufacturer_id'] == $item['id']) echo 'selected="selected"'; 
			?> ><?php echo $item['name']; ?></option>
			<?php } ?>
		</select>
	</div>
</div>
<div class="theme-table">
	<div class="theme-table-header">
		<div class="cl_45"><?php echo __( 'Name', AT_ADMIN_TEXTDOMAIN ); ?></div>
		<div class="cl_45"><?php echo __( 'Alias', AT_ADMIN_TEXTDOMAIN ); ?>	</div>
		<div class="cl_10">&nbsp;</div>
	</div>
	<div class="theme-table-body">
	<?php foreach ($items as $key => $item) { ?>
		<div class="theme-table-body-row" id="item_<?php echo $item['id']; ?>">
			<div class="cl_45" rel="name"><?php echo $item['name']; ?></div>
			<div class="cl_45" rel="alias"><?php echo $item['alias']; ?></div>
			<div class="cl_10 center">
				<a href="#" class="item_edit" data-id="<?php echo $item['id']; ?>"><?php echo __( 'Edit', AT_ADMIN_TEXTDOMAIN ); ?></a> | 
				<a href="#" class="item_delete" data-id="<?php echo $item['id']; ?>"><?php echo __( 'Delete', AT_ADMIN_TEXTDOMAIN ); ?></a>
			</div>
		</div>
	<?php } ?>
	</div>
</div>