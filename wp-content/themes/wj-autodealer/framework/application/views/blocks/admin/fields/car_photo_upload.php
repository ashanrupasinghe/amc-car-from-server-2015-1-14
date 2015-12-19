<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="theme_option_set" id="car_photo_upload">
	<!-- <div class="theme_option_header">
		<h3 class="caption"><?php //echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php //echo $description; ?></p>
	</div> -->
	<div class="theme_option">
	  <?php global $pagenow; if( $pagenow != 'post-new.php') { ?>
	  	<script type = "text/template" id="empty_photo_wrapper">
			<li class="photo_wrapper item {is_main}" >
				<img src="{src}" alt=""/>
				<div class="actions">
					<a href="#" data-id="0" class="icon set_main_image" title="<?php echo __( 'Set main', AT_TEXTDOMAIN );?>"><i class="icon-up-dir"></i></a>
					<a href="#" data-id="0" class="icon delete_image" title="<?php echo __( 'Delete', AT_TEXTDOMAIN );?>"><i class="icon-cancel"></i></a>
					<input type='hidden' name='<?php echo THEME_PREFIX; ?>options[photos][{file_name}]' value='{photo_value}'>
				</div>
			</li>
		</script>
		<ul class="photos_sortable photos">
		<?php foreach ($items as $item_key => $photo) { ?>
			<li class="photo_wrapper item <?php if($photo['is_main']) echo 'photo_main'; ?>">
				<img src="<?php echo AT_Common::static_url( $photo['photo_url'] . '480x290/' . $photo['photo_name'] ); ?>" class="active" />
				<div class="actions">
					<a href="#" class="icon set_main_image" title="<?php echo __( 'Set main', AT_ADMIN_TEXTDOMAIN );?>"><i class="icon-up-dir"></i></a>
					<a href="#" class="icon delete_image" title="<?php echo __( 'Delete', AT_ADMIN_TEXTDOMAIN );?>"><i class="icon-cancel"></i></a>
					<input type='hidden' name='<?php echo THEME_PREFIX; ?>options[photos][<?php echo $photo['id'];?>]' value='<?php echo $photo['is_main'] ? '1' : '0'; ?>'>
				</div>
			</li>
		<?php } ?>
		</ul>
		<div>
			<a href="#" class="btn" id="upload-photos"><?php echo __( 'Upload photos', AT_ADMIN_TEXTDOMAIN ); ?></a>
		</div>
	  <?php } else { ?>
	  	<?php echo __( 'Publish post', AT_ADMIN_TEXTDOMAIN ); ?>
	  <?php } ?>
	</div>
	<div class="clear"></div>
</div>