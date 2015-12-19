<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'admin-users', 'assets/js/admin/users/users.js');?>
<?php $this->add_style( 'jquery-ui', 'assets/css/admin/jquery-ui.min.css');?>
<?php $this->add_style( 'jquery-ui-theme', 'assets/css/admin/jquery-ui.theme.css');?>
<div id="dialog-confirm-block" title="<?php echo __( 'Block This User?', AT_ADMIN_TEXTDOMAIN );?>" style='display:none;'>
	<div class="popup-form" style="display:none;">
		<strong><?php echo __( 'This user will be blocked. Are you sure?', AT_ADMIN_TEXTDOMAIN);?></strong>
		<form method="post" action="admin.php?page=at_users" id="form_block_user">
			<div class="form-row">
				<div class="label"><?php echo __( 'What to do with vehicles?', AT_ADMIN_TEXTDOMAIN ); ?></div>
				<div class="field">
					<label for="action_vehicles_archive">
						<input type="radio" value="archive" id="action_vehicles_archive" name="action_vehicles" checked>
						<?php echo __( 'Move to archive', AT_ADMIN_TEXTDOMAIN ); ?>
					</label><br/>
					<label for="action_vehicles_another">
						<input type="radio" value="another" id="action_vehicles_another" name="action_vehicles">
						<?php echo __( 'Assign to another user', AT_ADMIN_TEXTDOMAIN ); ?>
					</label>
				</div>
			</div>
			<div class="form-row" style="display:none;" id="assign_user">
				<div class="label"><?php echo __( 'Assign user', AT_ADMIN_TEXTDOMAIN ); ?></div>
				<div class="field">
					<select name="assign_user_id" id="assign_user_id">
						<!-- <option>2</option> -->
					</select>
				</div>
			</div>
			<!-- <input type="hidden" value="1" name="user_id"> -->
		</form>
	</div>
	<div class="spinner popup-modal" style="display:block;"><?php echo __( 'Loading...', AT_ADMIN_TEXTDOMAIN ); ?></div>
</div>
<div id="dialog-confirm-unblock" title="<?php echo __( 'Unblock This User?', AT_ADMIN_TEXTDOMAIN );?>" style='display:none;'>
	<?php echo __( 'This user will be unblocked. Are you sure?', AT_ADMIN_TEXTDOMAIN);?>
</div>
<div id="dialog-change-password" title="<?php echo __( 'Change user password', AT_ADMIN_TEXTDOMAIN );?>" style='display:none;'>
	<div class="popup-form">
		<form method="post" action="admin.php?page=at_users">
			<div class="form-row">
				<div class="label"><?php echo __( 'New password', AT_ADMIN_TEXTDOMAIN ); ?></div>
				<div class="field"><input type="text" value="" autocomplete="off" id="password" name="password" class="text"></div>
			</div>
			<!-- <input type="hidden" value="1" name="user_id"> -->
		</form>
	</div>
</div>
<div id="dialog-form-user" title="<?php echo __( 'Edit/Add User', AT_ADMIN_TEXTDOMAIN );?>" style='display:none;'></div>
<div class="theme_controls">
	<div class="theme_button_group">
		<?php if($this->get_option( 'site_type', 'mode_soletrader' ) == 'mode_partnership' ) { ?>
		<div class="theme_admin_save">
			<input type="button" class="button user_add" data-id="0" value="<?php echo __( 'Add Item', AT_ADMIN_TEXTDOMAIN ); ?>" name="submit">
		</div>
		<?php } ?>
		<div class="spinner"><?php echo __( 'Saving...', AT_ADMIN_TEXTDOMAIN ); ?></div>
	</div>
	<h2 id="current_section_title"><?php echo __('Users', AT_ADMIN_TEXTDOMAIN); ?></h2>
</div>
<?php echo $block['tabs']; ?>
<div class="theme-table">
	<div class="theme-table-header">
		<div class="cl_5"><?php echo __( 'Photo', AT_ADMIN_TEXTDOMAIN ); ?></div>
		<div class="cl_16">
			<a 
			<?php if( $orderby != 'name' ) { ?> 
				href="admin.php?page=at_users&orderby=name&order=asc" 
			<?php } elseif( $orderby == 'name' && $order == 'asc' ) { ?> 
				href="admin.php?page=at_users&orderby=name&order=desc" class="asc"
			<?php } elseif( $orderby == 'name' && $order == 'desc' ) { ?> 
				href="admin.php?page=at_users&orderby=name&order=asc" class="desc" 
			<?php } ?>
			>
				<span><?php echo __( 'Name', AT_ADMIN_TEXTDOMAIN ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</div>
		<div class="cl_14">
			<a 
			<?php if( $orderby != 'email' ) { ?> 
				href="admin.php?page=at_users&orderby=email&order=asc" 
			<?php } elseif( $orderby == 'email' && $order == 'asc' ) { ?> 
				href="admin.php?page=at_users&orderby=email&order=desc" class="asc"
			<?php } elseif( $orderby == 'email' && $order == 'desc' ) { ?> 
				href="admin.php?page=at_users&orderby=email&order=asc" class="desc" 
			<?php } ?>
			>
				<span><?php echo __( 'Email', AT_ADMIN_TEXTDOMAIN ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</div>
		<!-- <div class="cl_14"><?php echo __( 'Phone', AT_ADMIN_TEXTDOMAIN ); ?></div>
		<div class="cl_14"><?php echo __( 'Phone 2', AT_ADMIN_TEXTDOMAIN ); ?></div> -->
		<div class="cl_14 center">
			<a 
			<?php if( $orderby != 'date_create' ) { ?> 
				href="admin.php?page=at_users&orderby=date_create&order=asc" 
			<?php } elseif( $orderby == 'date_create' && $order == 'asc' ) { ?> 
				href="admin.php?page=at_users&orderby=date_create&order=desc" class="asc"
			<?php } elseif( $orderby == 'date_create' && $order == 'desc' ) { ?> 
				href="admin.php?page=at_users&orderby=date_create&order=asc" class="desc" 
			<?php } ?>
			>
				<span><?php echo __( 'Date Create', AT_ADMIN_TEXTDOMAIN ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</div>
		<div class="cl_14 center">
			<a 
			<?php if( $orderby != 'date_active' ) { ?> 
				href="admin.php?page=at_users&orderby=date_active&order=asc" 
			<?php } elseif( $orderby == 'date_active' && $order == 'asc' ) { ?> 
				href="admin.php?page=at_users&orderby=date_active&order=desc" class="asc"
			<?php } elseif( $orderby == 'date_active' && $order == 'desc' ) { ?> 
				href="admin.php?page=at_users&orderby=date_active&order=asc" class="desc" 
			<?php } ?>
			>
				<span><?php echo __( 'Date Active', AT_ADMIN_TEXTDOMAIN ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</div>
		<div class="cl_10 center"><?php echo __( 'User Type', AT_ADMIN_TEXTDOMAIN ); ?></div>
		<div class="cl_10 center"><?php echo __( 'Status', AT_ADMIN_TEXTDOMAIN ); ?></div>
		<div class="cl_15">&nbsp;</div>
	</div>
	<div class="theme-table-body">
	<?php foreach ($items as $key => $item) { ?>
	<?php //print_r($item); ?>
		<div class="theme-table-body-row" id="user_<?php echo $item['id']; ?>">
			<div class="cl_5 user_photo">
				<img src="<?php echo AT_Common::static_url( (!empty( $item['photo'] ) ? $item['photo']['photo_url'] . '138x138/' .  $item['photo']['photo_name'] : 'assets/images/no_photo_profile.png' ) );?>">
			</div>
			<div class="cl_16"><?php echo $item['name']; ?></div>
			<div class="cl_14"><?php echo $item['email']; ?></div>
			<!-- <div class="cl_14"><?php echo !empty($item['phone']) ? $item['phone'] : '&nbsp;'; ?></div>
			<div class="cl_14"><?php echo !empty($item['phone_2']) ? $item['phone_2'] : '&nbsp;'; ?></div> -->
			<div class="cl_14 center"><?php echo $item['date_create']; ?></div>
			<div class="cl_14 center"><?php echo '&nbsp;'; ?></div>
			<div class="cl_10 center">
				<?php echo __($item['is_dealer'] ? 'Dealer' : 'User', AT_ADMIN_TEXTDOMAIN); ?>
			</div>
			<div class="cl_10 center status <?php echo ($item['is_block'] ? 'blocked' : ''); ?>">
				<?php 
					if( $item['id'] != '1' ) {
						echo ($item['is_block'] ? __( 'Blocked', AT_ADMIN_TEXTDOMAIN ) : __( 'Actived', AT_ADMIN_TEXTDOMAIN ) );
					} else {
						echo __( 'Main User', AT_ADMIN_TEXTDOMAIN );
					}
				?>
			</div>
			<div class="cl_15 center">
				<a href="#" class="user_edit" data-id="<?php echo $item['id']; ?>"><?php echo __( 'Edit', AT_ADMIN_TEXTDOMAIN ); ?></a> | 
				<a href="edit.php?post_status=all&post_type=car&_owner_id=<?php echo $item['id']; ?>"><?php echo __( 'Cars', AT_ADMIN_TEXTDOMAIN ); ?></a>
				<?php if( $item['id'] != '1' ) { ?> | 
				<a href="#" <?php if($item['is_block']) { ?>style="display:none;"<?php } ?> class="user_block" data-id="<?php echo $item['id']; ?>"><?php echo __( 'Trash', AT_ADMIN_TEXTDOMAIN ); ?></a>
				<a href="#" <?php if(!$item['is_block']) { ?>style="display:none;"<?php } ?> class="user_unblock" data-id="<?php echo $item['id']; ?>"><?php echo __( 'Restore', AT_ADMIN_TEXTDOMAIN ); ?></a>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
<div class="pagination_admin">
<?php echo $block['pagination']; ?>
</div>