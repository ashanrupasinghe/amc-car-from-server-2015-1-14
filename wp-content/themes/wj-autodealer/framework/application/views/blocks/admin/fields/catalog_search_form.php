<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set one-row toogle_block">
	<?php if( $view_title ) { ?>
	<div class="theme_option_header toogle_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<p><?php echo $description; ?></p>
		<div class="clear"></div>
	</div>
	<?php } ?>
	<div class="theme_option <?php if( $view_title ) echo 'toogle_content'; ?>">
		<div class="catalog_sortable_title"><?php echo __( 'Catalog Search Form:', AT_ADMIN_TEXTDOMAIN ); ?></div>
		<div class="catalog_sortable_title"><?php echo __( 'Sets:', AT_ADMIN_TEXTDOMAIN ); ?></div>
		<div class="clear"></div>
		<ul class="catalog_sortable" id="catalog_selected_items">
			<?php foreach ($value as $key => $item) { ?>
				<li>
					<span><?php echo $item['title']; ?></span>
					<input type="hidden" name="<?php echo $name . '[value][' . $key . ']'; ?>" value="" />
				</li>
			<?php } ?>
		</ul>
		<ul class="catalog_sortable last" id="catalog_sets_items">
			<?php foreach ($sets as $key => $item) { ?>
				<li>
					<span><?php echo $item['title']; ?></span>
					<input type="hidden" name="<?php echo $name . '[sets][' . $key. ']'; ?>" value="" />
				</li>
			<?php } ?>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>