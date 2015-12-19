<?php $this->add_script( 'install', 'assets/js/admin/install/install.js' ); ?>
<?php $this->add_script( 'jquery.validate', 'assets/js/jquery/jquery.validate.js' ); ?>
<div id="installation_page">
	<div class="step_breadcrumb">
		<a href="#" class="active" id="step_1"><span><?php echo __( 'step 1.  install', AT_ADMIN_TEXTDOMAIN );?></span></a>
		<a href="#" class="" id="step_2"><i></i><span><?php echo __( 'step 2.  settings', AT_ADMIN_TEXTDOMAIN );?></span></a>
	</div>
	<div class="install_page_content step_1">
		<h1><?php echo __( 'Welcome', AT_ADMIN_TEXTDOMAIN ); ?></h1>
		<div class="description_block">
			<h2><?php echo __( 'Welcome to Auto Dealer installer. Data collection will be performed to activate your website. Auto Dealer installer will create additional tables for frontend users.', AT_ADMIN_TEXTDOMAIN ); ?></h2>
			<p><?php echo __( 'Please follow the instructions and fill all fields with correct information. Information below required to create a front-end user (dealer) to control all your products from Auto Dealer UI.', AT_ADMIN_TEXTDOMAIN );?></p>
		</div>
		<div class="form_block">
		  <form action="#" id="install_form">
			<div class="row">
				<div class="field">
					<label><?php echo __( 'Full name:', AT_ADMIN_TEXTDOMAIN );?></label>
					<input type="text" name="at_name" value="Admin" id="at_name" required minlength="3" maxlength="50" >
				</div>
				<div class="descr">
					<?php echo __( 'Users will see your name publicaly as dealer name. You may enter company name, for example: Yourcompany LTD.', AT_ADMIN_TEXTDOMAIN ); ?>
				</div>
			</div>
			<div class="row">
				<div class="field">
					<label><?php echo __( 'Enter your email:', AT_ADMIN_TEXTDOMAIN );?></label>
					<input type="email" name="at_email" value="" required id="at_email" >
				</div>
				<div class="descr">
					<?php echo __( 'Email address required for login page and password recovery.', AT_ADMIN_TEXTDOMAIN ); ?>
				</div>
			</div>
			<div class="row">
				<div class="field">
					<label><?php echo __( 'Enter password:', AT_ADMIN_TEXTDOMAIN );?></label>
					<input type="password" name="at_password" value="" required id="at_password">
				</div>
				<div class="descr">
				</div>
			</div>
			<div class="row">
				<div class="field">
					<label><?php echo __( 'Confirm password:', AT_ADMIN_TEXTDOMAIN );?></label>
					<input type="password" name="at_confirm_password" value="" required equalTo="#at_password" id="at_confirm_password">
				</div>
				<div class="descr">
					<?php echo __( 'We are recommend use different password from your WordPress admin to protect your website.', AT_ADMIN_TEXTDOMAIN ); ?>
				</div>
			</div>
			<div class="row">
				<input type="checkbox" name="test_data" checked value="yes" id="test_data"><label for="test_data"><?php echo __( 'Install the test data?', AT_ADMIN_TEXTDOMAIN ); ?></label>
			</div>
		  </form>
		</div>
		<div class="block_submit">
			<a href="#" class="btn" id="install_submit"><?php echo __( 'Install', AT_ADMIN_TEXTDOMAIN ); ?></a>
			<span class="spinner" id="spinner_install" style="float:left;"></span>
		</div>
	</div>
	<br/>
	<div class="install_page_content step_2">
		<h1><?php echo __( 'Congratulations! You have installed all data successful!', AT_ADMIN_TEXTDOMAIN ); ?></h1>
		<div class="description_block no_bg">
			<p><?php echo __( 'Your website may function in two different modes - Sole Trader mode (only you may sell) and Dealer mode - when several partners (dealers) may sell their products. All dealers could be registered only by you.', AT_ADMIN_TEXTDOMAIN );?></p>
		</div>
		<div class="form_block">
			<div class="checked_img">
				<input type="hidden" id="site_type" value="mode_soletrader">
				<div class="img">
					<img class="active" data-id="mode_soletrader" src="<?php echo AT_URI ?>/assets/images/admin/site_type/mode_soletrader.png" />
				</div>
				<div class="img">
					<img data-id="mode_partnership" src="<?php echo AT_URI ?>/assets/images/admin/site_type/mode_partnership.png" />
				</div>
				<div class="img">
					<img data-id="mode_board" src="<?php echo AT_URI ?>/assets/images/admin/site_type/mode_board.png" />
				</div>
			</div>
		</div>
		<div class="block_submit">
			<a href="#" class="btn" id="install_complete"><?php echo __( 'Finish', AT_ADMIN_TEXTDOMAIN ); ?></a>
			<span class="spinner" id="spinner_complete" style="float:left;"></span>
		</div>
	</div>
</div>