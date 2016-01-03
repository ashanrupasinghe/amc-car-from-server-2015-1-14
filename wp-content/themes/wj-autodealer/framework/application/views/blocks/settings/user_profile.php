<?php if (!defined("AT_DIR")) die('!!!'); ?>

<?php $this->add_script( 'jquery-ui-core'); ?>
<?php $this->add_script( 'jquery-ui-dialog'); ?>

<?php $this->add_script( 'jquery.form', 'assets/js/jquery/jquery.form.js'); ?>
<?php $this->add_script( 'jquery.validate', 'assets/js/jquery/jquery.validate.js'); ?>
<?php $this->add_script( 'plupload', 'assets/js/plupload/plupload.full.min.js'); ?>
<?php $this->add_script( 'login', 'assets/js/settings.js'); ?>

<?php $this->add_style( 'jquery-ui', 'assets/css/jquery-ui/jquery-ui.min.css');?>
<?php $this->add_style( 'jquery-ui-theme', 'assets/css/jquery-ui/jquery-ui.theme.css');?>
<?php
	$user_info = $this->registry->get( 'user_info' );
?>
<div id="dialog-confirm-delete" title="<?php echo __( 'You really want to delete logo?', AT_TEXTDOMAIN);?>" style='display:none;'></div>
<div id="dialog-change-password" title="<?php echo __( 'Change password', AT_TEXTDOMAIN);?>" style='display:none;'>
	<div style="">
		<form action="<?php echo AT_Common::site_url( 'profile/settings/' ); ?>" method="post" id="change-password">
			<div class="row">
				<label><?php echo __( 'Old password', AT_TEXTDOMAIN ); ?></label>
				<input type="password" name="old_password" id="old_password" value="" class="text" />
			</div>
			<div class="row">
				<label><?php echo __( 'New password', AT_TEXTDOMAIN ); ?></label>
				<input type="password" name="new_password" id="new_password" value="" class="text" />
			</div>
			<div class="row">
				<label><?php echo __( 'Repeat password', AT_TEXTDOMAIN ); ?></label>
				<input type="password" name="repeat_password" id="repeat_password" value="" class="text" />
			</div>
		</form>
	</div>
</div>
<div class="profile_content">
	<h1><?php echo __( '<strong>Profile</strong>', AT_TEXTDOMAIN); ?></h1>
	<div class="settings_photo" id="upload-photo-container">
		<div class="photo">
			<img id="profile_photo" src="<?php echo AT_Common::static_url( (!empty( $user_info['photo'] ) ? $user_info['photo']['photo_url'] . '138x138/' .  $user_info['photo']['photo_name'] : 'assets/images/no_photo_profile.png' ) );?>">
			<div class="loader"></div>
		</div>
		<div class="upload-photo">
			<div class="username"><?php echo $user_info['name']; ?></div>
			<a href="#" class="btn1" id="upload-photo"><?php echo __( 'upload a photo', AT_TEXTDOMAIN ); ?></a>
			<a href="#" class="btn2 photo_delete">X</a>
		</div>
	</div>
	<form action="<?php echo AT_Common::site_url('profile/settings/'); ?>" method="post" id="settings-form">
		<div class="settings_form">
			<div class="col1">
				<label class="text"><?php echo __( 'Full name:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="name" id="name" value="<?php echo $user_info['name']; ?>"/>
			</div>
			<div class="col2">
				<label class="text"><?php echo __( 'E-mail:', AT_TEXTDOMAIN); ?></label>
				<input type="text" disabled class="text" name="email" id="email" value="<?php echo $user_info['email']; ?>"/>
			</div>
			<div class="col1">
				<label class="text"><?php echo __( 'Phone 1:', AT_TEXTDOMAIN); ?></label>
				<input type="text" name="phone_1" class="text" value="<?php echo $user_info['phone']; ?>"/>
			</div>
			<div class="col2">
				<label class="text"><?php echo __( 'Phone 2:', AT_TEXTDOMAIN); ?></label>
				<input type="text"  name="phone_2" class="text" value="<?php echo $user_info['phone_2']; ?>"/>
			</div>
			<div class="col1 last">
				<label class="text"<?php echo __( '>Region:', AT_TEXTDOMAIN); ?>></label>
				<!-- <select name="region" id="region" class="text">
					<option>Region</option>
				</select> -->
				<select name="region_id" id="region_id" class="text">
					<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN); ?></option>
					<?php foreach ($regions as $key => $region) { ?>
						<option value="<?php echo $region['id']; ?>" <?php if( $region['id'] == $user_info['region_id'] ) echo 'selected'; ?> ><?php echo $region['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<?php /*
		<div class="settings_form">
			<label class="checkbox"><input type="checkbox" name="hide_number_ads" /><?php echo __( 'Hide number of my ads', AT_TEXTDOMAIN ); ?></label>
			<label class="checkbox"><input type="checkbox" name="" />Receive newsletter about upgrading, promotions, competitions</label>
			<label class="checkbox last"><input type="checkbox" name="" />Show my personal page on the site</label>
		</div>
		*/?>
		<a href="#" class="btn1 submit-save"><?php echo __( 'SAVE CHANGES', AT_TEXTDOMAIN ); ?></a>
		<a href="#" class="btn2 submit-change-password"><?php echo __( 'CHANGE PASSWORD', AT_TEXTDOMAIN ); ?></a>
		<span class="form_loading"><img src="<?php echo AT_Common::static_url('assets/images/loading.gif'); ?>" /></span>
	</form>
</div>