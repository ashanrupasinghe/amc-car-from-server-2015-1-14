<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'admin-users', 'assets/js/admin/reference/reference.js');?>
<?php $this->add_style( 'jquery-ui', 'assets/css/admin/jquery-ui.min.css');?>
<?php $this->add_style( 'jquery-ui-theme', 'assets/css/admin/jquery-ui.theme.css');?>
<div id="dialog-confirm-delete" data-tab="<?php echo $tab; ?>" title="<?php echo __( 'Delete this Item?', AT_ADMIN_TEXTDOMAIN );?>" style='display:none;'>
	</span><?php echo __( 'These items will be permanently deleted and cannot be recovered. Are you sure?', AT_ADMIN_TEXTDOMAIN);?>
</div>
<div class="theme-table">
	<div class="theme-table-header">
		<div class="cl_45"><?php echo __( 'Name', AT_ADMIN_TEXTDOMAIN ); ?></div>
		<div class="cl_45"><?php echo __( 'Alias', AT_ADMIN_TEXTDOMAIN ); ?>	</div>
		<div class="cl_10 center">&nbsp;</div>
	</div>
	<div class="theme-table-body" <?php if($sortable) echo 'id="sortable-table"'; ?>>
	<?php foreach ($items as $key => $item) { ?>
		<div class="theme-table-body-row" id="item_<?php echo $item['alias']; ?>">
			<div class="cl_45" rel="name"><?php echo $item['name']; ?></div>
			<div class="cl_45" rel="alias"><?php echo $item['alias']; ?></div>
			<div class="cl_10 center">
				<a href="#" class="item_edit" data-id="<?php echo $item['alias']; ?>"><?php echo __( 'Edit', AT_ADMIN_TEXTDOMAIN ); ?></a> | 
				<a href="#" class="item_delete" data-id="<?php echo $item['alias']; ?>"><?php echo __( 'Delete', AT_ADMIN_TEXTDOMAIN ); ?></a>
			</div>
		</div>
	<?php } ?>
	</div>
</div>