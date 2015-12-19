<?php if (!defined("AT_DIR")) die('!!!'); ?>

<div class="wrapper_login_page">
	<div class="background">
		<img src="<?php echo AT_Common::static_url('assets/images/bg_auto/' . $background ); ?>">
		<div id="registration-page" class="popup">
			<div class="popup_title"><?php echo __('Registration', AT_TEXTDOMAIN); ?></div>
			<div class="popup_content">
				<form method="post" action="<?php echo AT_Common::site_url('/auth/registration/');?>" id="registration-form-page" class="registration-form">
					<input type="text" class="text" name="name" value="" placeholder="<?php echo __('Username', AT_TEXTDOMAIN );?>" />
					<input type="text" class="text" name="email" placeholder="<?php echo __('Email', AT_TEXTDOMAIN ); ?>">
					<!-- <input type="password" class="text" name="pass" placeholder="<?php echo __('Password', AT_TEXTDOMAIN); ?>" id="pass">
					<input type="password" class="text" name="pass_again" id="pass_again" placeholder="<?php echo __('Repeat password', AT_TEXTDOMAIN); ?>"> -->

					<input type="password" class="text" name="pass" placeholder="<?php echo __('Password', AT_TEXTDOMAIN); ?>" id="pass">
					<input type="password" class="text" name="pass_again" id="pass_again" placeholder="<?php echo __('Repeat password', AT_TEXTDOMAIN); ?>">
					<a href="#" class="btn1 registration" /><?php echo __('REGISTER', AT_TEXTDOMAIN); ?></a>
				</form>
			</div>
		</div>
	</div>
</div>