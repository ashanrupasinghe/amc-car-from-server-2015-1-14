<?php if (!defined("AT_DIR")) die('!!!'); ?>

<div class="wrapper_login_page">
	<div class="background">
		<img src="<?php echo AT_Common::static_url('assets/images/bg_auto/' . $background ); ?>">
		<div id="login-page" class="popup">
			<div class="popup_title"><?php echo __('Login', AT_TEXTDOMAIN); ?></div>
			<div class="popup_content">
				<form method="post" action="<?php echo AT_Common::site_url('/auth/login/');?>" id="login-form-page" class="login-form">
					<input type="text" id="email" class="text" name="email" placeholder="<?php echo __('Email', AT_TEXTDOMAIN); ?>">
					<input type="password" class="text" name="password" id="password" placeholder="<?php echo __('Password', AT_TEXTDOMAIN); ?>">
					<div class="col1"><a href="#" class="btn1 login" /><?php echo __('LOGIN', AT_TEXTDOMAIN); ?></a></div>
					<?php /*<div class="col2"><a href="<?php echo AT_Common::site_url('/auth/registration/');?>" class="btn2" class="registration" /><?php echo __('REGISTRATION', AT_TEXTDOMAIN); ?></a></div> */?>
					<div class="col1"><a href="<?php echo AT_Common::site_url('/auth/recovery/');?>" class="lost_password"><?php echo __('Lost password?', AT_TEXTDOMAIN); ?></a></div>
					<div class="col2"><label class="checkbox"><input type="checkbox" name="remember" id=""> <?php echo __('Remember me', AT_TEXTDOMAIN); ?></label></div>
				</form>
			</div>
		</div>
	</div>
</div>