<div class="popup-form">
	<form action="admin.php?page=at_users" method="post" id="add-edit-user" data-id="<?php echo (!empty($id) ? $id : ''); ?>">
		<div class="form-row">
			<div class="label"><?php echo __( 'Name', AT_ADMIN_TEXTDOMAIN); ?></div>
			<div class="field"><input type="text" class="text" name="name" id="name" value="<?php echo (!empty($name) ? $name : ''); ?>" /></div>
		</div>
		<div class="form-row">
			<div class="label"><?php echo __( 'Email', AT_ADMIN_TEXTDOMAIN); ?></div>
			<div class="field"><input type="text" name="email" id="email" value="<?php echo (!empty($email) ? $email : ''); ?>" <?php echo (!empty($id) ? 'readonly' : ''); ?> /></div>
		</div>
	<?php if(empty($id)) { ?>
		<div class="form-row">
			<div class="label"><?php echo __( 'Password', AT_ADMIN_TEXTDOMAIN); ?></div>
			<div class="field"><input type="password" name="password" id="password" value="" /></div>
		</div>
	<?php }?>
		<div class="form-row">
			<div class="label"><?php echo __( 'Phone', AT_ADMIN_TEXTDOMAIN); ?></div>
			<div class="field"><input type="text" name="phone" id="phone" value="<?php echo (!empty($phone) ? $phone : ''); ?>" /></div>
		</div>
		<div class="form-row">
			<div class="label"><?php echo __( 'Phone 2', AT_ADMIN_TEXTDOMAIN); ?></div>
			<div class="field"><input type="text" name="phone_2" id="phone_2" value="<?php echo (!empty($phone_2) ? $phone_2 : ''); ?>" /></div>
		</div>
		<?php if(isset($is_dealer) && $is_dealer) { ?>
		<div class="form-row">
			<div class="label"><?php echo __( 'Dealer alias', AT_ADMIN_TEXTDOMAIN); ?></div>
			<div class="field"><input type="text" name="alias" id="alias" value="<?php echo (!empty($alias) ? $alias : ''); ?>" /></div>
		</div>
		<?php } ?>
		<div class="form-row">
			<div class="label"><?php echo __( 'User status', AT_ADMIN_TEXTDOMAIN); ?></div>
			<div class="field">
				<input type="radio" name="is_dealer" id="is_dealer_1" value="1" <?php echo (isset($is_dealer) && $is_dealer == 1 ?  'checked' : ''); ?> />
				<label for="is_dealer_1"><?php echo __( 'Dealer', AT_ADMIN_TEXTDOMAIN); ?></label>
				<input type="radio" name="is_dealer" id="is_dealer_0" value="0" <?php echo (isset($is_dealer) && $is_dealer == 0 ?  'checked' : ''); ?> />
				<label for="is_dealer_0"><?php echo __( 'User', AT_ADMIN_TEXTDOMAIN); ?></label>
				<br/>&nbsp;
			</div>
		</div>
		<input type="hidden" name="action" value="<?php echo (!empty($id) ? 'save_user' : 'add_user'); ?>">
		<input type="hidden" name="user_id" value="<?php echo (!empty($id) ? $id : ''); ?>">
	</form>
	<?php if(!empty($id)) { ?>
	<a href="#" class="user_change_password" data-id="<?php echo $id; ?>"><?php echo __( 'Change Password', AT_ADMIN_TEXTDOMAIN ); ?></a>
	<?php } ?>
	<div class="spinner popup-modal"><?php echo __( 'Saving data...', AT_ADMIN_TEXTDOMAIN ); ?></div>
</div>