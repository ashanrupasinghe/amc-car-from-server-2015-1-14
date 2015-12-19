<?php if (!defined("AT_DIR")) die('!!!'); ?>
<form action="<?php echo 'admin.php?page=' . THEME_PREFIX . 'site_options_' . $alias; ?>" id="options_form" method="post">
<div class="theme_controls">
	<div class="theme_button_group">
		<div class="theme_admin_reset">
			<input type="reset" class="button" value="<?php echo __( 'Set to default', AT_ADMIN_TEXTDOMAIN ); ?>" name="submit">
		</div>
		<div class="theme_admin_save">
			<input type="submit" class="button" value="<?php echo __( 'Save all options', AT_ADMIN_TEXTDOMAIN ); ?>" name="submit">
		</div>
		<div class="spinner"><?php echo __( 'Saving...', AT_ADMIN_TEXTDOMAIN ); ?></div>
	</div>
	<h2 id="current_section_title"><?php echo $title; ?></h2>
</div>
<?php echo $block['content'] ?>
<div class="theme_footer_submit">
	<input type="submit" value="<?php echo __( 'Save All Changes', AT_ADMIN_TEXTDOMAIN ); ?>" name="submit">
</div>
</form>