<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'jquery.validate', 'assets/js/jquery/jquery.validate.js'); ?>
<?php $this->add_script( 'vehicles', 'assets/js/confirm_email.js'); ?>
<div class="wrapper_login_page">
	<div class="background">
		<img src="<?php echo AT_Common::static_url('assets/images/bg_auto/' . $background ); ?>">
		<div id="confirm-email-page" class="popup">
			<div class="popup_title"><?php echo __( 'Confirm Email', AT_TEXTDOMAIN ); ?></div>
			<div class="popup_content">
				<form method="post" action="<?php echo AT_Common::site_url('/auth/confirm_email/');?>" class="confirm-form">
					<input type="text" id="code" class="text" name="code" placeholder="<?php echo __( 'Auth-Code', AT_TEXTDOMAIN ); ?>">
					<a href="#" class="btn1 confirm" /><?php echo __( 'Confirm', AT_TEXTDOMAIN ); ?></a> 
					<a href="#" class="btn2 send_mail_again" style="float:right;" /><?php echo __( 'Resend code', AT_TEXTDOMAIN ); ?></a>
				</form>
			</div>
		</div>
	</div>
</div>