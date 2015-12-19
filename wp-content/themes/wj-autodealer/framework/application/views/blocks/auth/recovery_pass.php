<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'jquery.validate', 'assets/js/jquery/jquery.validate.js'); ?>
<?php $this->add_script( 'vehicles', 'assets/js/recovery.js'); ?>
<div class="wrapper_login_page">
	<div class="background">
		<img src="<?php echo AT_Common::static_url('assets/images/bg_auto/' . $background ); ?>">
		<div id="recovery-hash-page" class="popup">
			<div class="popup_title"><?php echo __( 'Password recovery', AT_TEXTDOMAIN ); ?></div>
			<div class="popup_content">
				<form method="post" action="<?php echo AT_Common::site_url('/auth/recovery_pass/');?>" class="recovery-pass-form">
					<input type="text" id="hash" class="text" value="<?php echo $hash; ?>" name="hash" placeholder="<?php echo __( 'Hash', AT_TEXTDOMAIN ); ?>">
					<span id="passwords" <?php if( !$valid ) { ?>style="display:none;"<?php } ?>>
						<input type="password" id="new_password" class="text" <?php if( !$valid ) { ?>disabled<?php } ?> name="new_password" placeholder="<?php echo __( 'Password', AT_TEXTDOMAIN ); ?>">
						<input type="password" id="password_again" class="text" <?php if( !$valid ) { ?>disabled<?php } ?> name="password_again" placeholder="<?php echo __( 'Password repeat', AT_TEXTDOMAIN ); ?>">
					</span>
					<a href="#" class="btn1 recovery" /><?php echo __( 'Send', AT_TEXTDOMAIN ); ?></a>
				</form>
			</div>
		</div>
	</div>
</div>