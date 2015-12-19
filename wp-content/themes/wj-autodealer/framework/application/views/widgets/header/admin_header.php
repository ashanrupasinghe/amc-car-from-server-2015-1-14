<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php
	//$this->add_style( 'bootstrap', 'assets/css/bootstrap.css');
	$this->add_style( 'style.css', 'assets/css/admin/admin.css');
	$this->add_style( 'fonts.css', 'assets/css/fonts.css');
	$this->add_style( 'icons.css', 'assets/css/icons.css');
	$this->add_style( 'select2.css', 'assets/css/select2/select2.css');
	$this->add_script( 'jquery.form', 'assets/js/jquery/jquery.form.js');
	//$this->add_script( 'admin-options', 'assets/js/admin/options/options.js', array( 'jquery', 'media-upload', 'thickbox' ) );
	$this->add_script( 'select2', 'assets/js/select2/select2.min.js');
	$this->add_script( 'admin-options', 'assets/js/admin/options/options.js');
	$this->add_script( 'admin-form-options', 'assets/js/admin/options/form-options.js', array( THEME_PREFIX . 'jquery.form' ));
?>
<div class="theme_header">
	<div class="theme_logo">
		<img src="<?php echo AT_Common::static_url('assets/images/admin/logo.png'); ?>"/>
	</div>
	<div class="theme_details">
		<p><?php echo __( 'AutoDealer', AT_ADMIN_TEXTDOMAIN ); ?> <?php echo __( 'by', AT_ADMIN_TEXTDOMAIN ); ?> <a target="_BLANK" href="http://winterjuice.com/">Winter Juice</a></p>
	</div>
</div>