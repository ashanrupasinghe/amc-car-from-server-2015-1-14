<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'jquery.validate', 'assets/js/jquery/jquery.validate.js'); ?>
<?php $this->add_script( 'vehicles', 'assets/js/recovery.js'); ?>
<div class="wrapper_login_page">
	<div class="background">
		<img src="<?php echo AT_Common::static_url('assets/images/bg_auto/' . $background ); ?>">
		<div id="recovery-page" class="popup">
			<div class="popup_title"><?php echo __( 'Password recovery', AT_TEXTDOMAIN ); ?></div>
			<div class="popup_content">
				<form method="post" action="<?php echo AT_Common::site_url('/auth/recovery/');?>" class="recovery-form">
					<input type="text" id="email" class="text" name="email" placeholder="<?php echo __( 'Email', AT_TEXTDOMAIN ); ?>">
					<a href="#" class="btn1 recovery" /><?php echo __( 'send', AT_TEXTDOMAIN ); ?></a>
				</form>
			</div>
		</div>
	</div>
</div>