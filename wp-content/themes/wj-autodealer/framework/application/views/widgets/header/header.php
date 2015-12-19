<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php
	$this->add_style( 'jquery.fancybox', 'assets/css/jquery.fancybox-1.3.4.css');
	$this->add_style( 'fonts.css', 'assets/css/fonts.css');
	$this->add_style( 'icons.css', 'assets/css/icons.css');
	$this->add_style( 'icons-transport.css', 'assets/css/icon-transport.css');
	$this->add_style( 'select2.css', 'assets/css/select2/select2.css');
	$this->add_style( 'style.css', 'assets/css/style.css');
	$this->add_style( 'ptsans', 'http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic&subset=latin,cyrillic-ext,cyrillic');


	if ( !$this->get_option( 'disable_responsiveness' ) ) {
		$this->add_style( 'vc-responsive.css', 'assets/css/vc-responsive.css');
	}
	
	if ( $this->get_option( 'custom_styled_css' ) != ''  ) {
		$this->add_style( 'custom_styled.css', 'assets/css/styled/' . $this->get_option( 'custom_styled_css' ), array( THEME_PREFIX . 'style.css' ) );	
	}

	$this->add_script( 'jquery');
	//$this->add_script( 'jquery-ui');
	$this->add_script( 'jquery.fitvids', 'assets/js/jquery/jquery.fitvids.js', array( 'jquery' ));
	$this->add_script( 'jquery.form', 'assets/js/jquery/jquery.form.js', array( 'jquery' ));
	$this->add_script( 'jquery.bxslider.min.js', 'assets/js/jquery/jquery.bxslider.min.js', array( 'jquery' ));
	$this->add_script( 'jquery.checkbox.js', 'assets/js/jquery/jquery.checkbox.js', array( 'jquery' ));
	$this->add_script( 'jquery.countdown.js', 'assets/js/jquery/jquery.countdown.js', array( 'jquery' ));
	$this->add_script( 'jquery.easing.js', 'assets/js/jquery/jquery.easing.1.3.js', array( 'jquery' ));
	$this->add_script( 'jquery.fancybox.js', 'assets/js/jquery/jquery.fancybox-1.3.4.pack.js', array( 'jquery' ));
	$this->add_script( 'jquery.mousewheel.js', 'assets/js/jquery/jquery.mousewheel-3.0.4.pack.js', array( 'jquery' ));
	//$this->add_script( 'jquery.selectik.js', 'assets/js/jquery/jquery.selectik.js', array( 'jquery' ));
	$this->add_script( 'jquery.validate', 'assets/js/jquery/jquery.validate.js', array( 'jquery' ));

	$this->add_script( 'vc.transitions', 'assets/js/js_composer/transition.js', array( 'jquery' ));
	$this->add_script( 'vc.carousel', 'assets/js/js_composer/vc_carousel.js', array( 'jquery' ));
	$this->add_script( 'select2', 'assets/js/select2/select2.min.js', array( 'jquery' ));

	$this->add_script( 'common', 'assets/js/common.js', array( 'jquery' ));
	$this->add_localize_script( 'common', 'theme_site_url', AT_Common::site_url('/') );

	$this->add_script( 'js', 'assets/js/js.js', array( 'jquery' ));

if ( !AT_Common::is_user_logged()) {
	$this->add_script( 'login', 'assets/js/login.js', array( 'jquery' ));
}
get_header();
?>
<!--BEGIN HEADER-->
	<div id="header">
		<div class="top_info">
			<div class="logo">
				<a href="<?php echo AT_Common::site_url( '/' ); ?>"><?php
						echo $logo; // Default logo if empty: framework/assets/pics/logo_auto_dealer.png
				?></a>
			</div>
			<div class="header_contacts">
				<?php if ( $header_style == 'html' ) { ?>
					<div class="header-custom-html"><?php echo $header_custom_html; ?></div>
				<?php } else { ?>
					<div class="phone"><?php echo $phone; ?></div>
					<div><?php echo $adress; ?></div>
				<?php } ?>
			</div>
			<div class="signin">
			<?php if ( !AT_Common::is_user_logged()) { ?>
			  <?php if( in_array( $site_type, array( 'mode_partnership', 'mode_board' ) ) ) { ?>
				<div class="nav">
					<?php if( in_array( $site_type, array( 'mode_board' ) ) ) { ?>
						<a href="<?php echo AT_Common::site_url('/auth/registration/');?>" class="user-register registration-open-popup" id="register"><i class="icon-list"></i><span><?php echo __( 'Register', AT_TEXTDOMAIN ); ?></span></a>
					<?php } ?>
					<a href="<?php echo AT_Common::site_url('/auth/login/');?>" class="user-icon" id="login"><i class="icon-user"></i><span><?php echo __( 'Login', AT_TEXTDOMAIN ); ?></span></a>
				</div>
				<div id="popup-login" class="popup">
					<a href="#" class="close" alt="Close"></a>
					<div class="popup_title"><?php echo __('Login', AT_TEXTDOMAIN); ?></div>
					<div class="popup_content">
						<form method="post" action="<?php echo AT_Common::site_url('/auth/login/');?>" id="login-form-popup" class="login-form">
							<input type="text" id="email" class="text" name="email" placeholder="<?php echo __('Email', AT_TEXTDOMAIN); ?>">
							<input type="password" class="text" name="password" id="password" placeholder="<?php echo __('Password', AT_TEXTDOMAIN); ?>">
							<div class="col1"><a href="#" class="btn1 login" /><?php echo __('LOGIN', AT_TEXTDOMAIN); ?></a></div>
							<?php if( $site_type == 'mode_board' && $this->get_option( 'registration_enable', true ) ) { ?>
							<div class="col2"><a href="<?php echo AT_Common::site_url('/auth/registration/');?>" class="btn2 registration-open-popup" id="registration" /><?php echo __('REGISTRATION', AT_TEXTDOMAIN); ?></a></div>
							<?php } ?>
							<div class="col1"><a href="<?php echo AT_Common::site_url('/auth/recovery/');?>" class="lost_password"><?php echo __('Lost password?', AT_TEXTDOMAIN); ?></a></div>
							<div class="col2"><label class="checkbox"><input type="checkbox" name="remember" id=""> <?php echo __('Remember me', AT_TEXTDOMAIN); ?></label></div>
						</form>
					</div>
				</div>
				<?php if( $site_type == 'mode_board' && $this->get_option( 'registration_enable', true ) ) { ?>
				<div id="popup-registration" class="popup">
					<a href="#" class="close" alt="Close"></a>
					<div class="popup_title"><?php echo __('Registration', AT_TEXTDOMAIN); ?></div>
					<div class="popup_content">
						<form method="post" action="<?php echo AT_Common::site_url('/auth/registration/');?>" id="registration-form-popup" class="registration-form">
							<input type="text" class="text" name="name" value="" placeholder="<?php echo __('Username', AT_TEXTDOMAIN); ?>" />
							<input type="text" class="text" name="email" placeholder="<?php echo __('Email', AT_TEXTDOMAIN); ?>">
							<input type="password" class="text" name="pass" placeholder="<?php echo __('Password', AT_TEXTDOMAIN); ?>" id="pass">
							<input type="password" class="text" name="pass_again" id="pass_again" placeholder="<?php echo __('Repeat password', AT_TEXTDOMAIN); ?>">
							<a href="#" class="btn1 registration" /><?php echo __('REGISTRATION', AT_TEXTDOMAIN); ?></a>
						</form>
					</div>
				</div>
				<?php } ?>
			  <?php } ?>
			<?php } else { ?>
				<div class="nav">
					<?php $user_info = $this->registry->get( 'user_info' ); ?>
					<a href="<?php echo AT_Common::site_url('/profile/');?>" class="user-icon profile" ><span><?php echo $user_info['name']; ?></span></a>
					<?php if( in_array( $site_type, array( 'mode_partnership', 'mode_board' ) ) ) { ?> | 
					<a href="<?php echo AT_Common::site_url('/auth/unlogged/');?>" ><span><?php echo __( 'Logout', AT_TEXTDOMAIN ); ?></span></a>
					<?php } ?>
				</div>
			<?php } ?>
			</div>
			<?php if($add_car_button) { ?>
			<div class="add_car">
				<?php if ( AT_Common::is_user_logged() ) { ?>
				<a href="<?php echo AT_Common::site_url('/profile/vehicles/add/');?>" class="btn2"><?php echo __( '+ Add auto', AT_TEXTDOMAIN ); ?></a>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( $sociable_view ) { ?>
			<div class="socials">
				<?php
				foreach ($sociable as $key => $item) {
					$sociable_link = ( !empty( $item['link'] ) ) ? $item['link'] : '#';
					echo '<a href="' . esc_url( $sociable_link ) . '"><i class="' . $item['icon'] . '"></i></a>';
				}
				?>
			</div>
			<?php } ?>
		</div>
		<div class="bg_navigation">
			<div class="navigation_wrapper">
				<div id="navigation">
					<span><?php echo __( 'Navigation', AT_TEXTDOMAIN ) ?></span>
					<?php $this->add_widget('menu_widget'); ?>
				</div>
				
				<?php
					if ( $searchbox ) {
						get_search_form();
					}
				?> 
				<div class="clear"></div>
			</div>
		</div>
	</div>
<!--EOF HEADER-->
<!--BEGIN CONTENT-->
	<div id="content">
		<?php if (!$content_auto_width): ?>
		<div class="content">
		<?php else: ?>
		<div class="">
		<?php endif ?>
			<!-- <div class="main_wrapper"> -->