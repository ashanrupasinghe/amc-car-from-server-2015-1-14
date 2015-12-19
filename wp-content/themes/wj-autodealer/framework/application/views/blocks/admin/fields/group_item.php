<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php if(isset($empty_form)) {?>
<script type = "text/template" class="empty_form">
<?php } else  { ?>
<li data-id="<?php echo $id; ?>" class="item_sort _group_item">
<?php } ?>
	<dl class="menu-item-bar">
		<dt class="menu-item-handle">
			<span class="item-title"><?php echo $title; ?></span>
			<span class="item-controls">
				<a class="submit_delete " href="#"><?php echo __( 'Delete', AT_ADMIN_TEXTDOMAIN ) ?></a>
			</span>
		</dt>
	</dl>
	<div class="menu-item-options">
		<?php echo $block['item']; ?>
	</div>
<?php if(isset($empty_form)) {?>
</script>
<?php } else  { ?>
</li>
<?php } ?>