<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'login', 'assets/js/vehicles_list.js'); ?>
<div class="profile_content">
	<?php if( $car_status == 'publish' ) { ?>
	<h1 class="no_border"><?php echo __( '<strong>My</strong> cars', AT_TEXTDOMAIN ); ?></h1>
	<?php } else { ?>
	<h1 class="no_border"><?php echo __( '<strong>My</strong> cars archived', AT_TEXTDOMAIN ); ?></h1>
	<?php } ?>
	<!--div class="notice"><?php echo __( 'Public ads on the site', AT_TEXTDOMAIN ); ?></div-->
	<div class="search_cars">
		<a href="<?php echo AT_Common::site_url('/profile/vehicles/add/');?>" class="btn2" style="float:right;"><?php echo __( 'Add', AT_TEXTDOMAIN ); ?></a>
		<div class="found_count">
			<span><?php echo __( 'Found:', AT_TEXTDOMAIN ); ?></span> <?php echo $count_cars; ?> <?php echo __( 'cars', AT_TEXTDOMAIN ); ?>
			<?php if( $car_status == 'publish' ) { ?>
			<span style="padding-left:30px;"><?php echo __( 'Publish Limit:', AT_TEXTDOMAIN ); ?></span> <?php echo ( $publish_limit > 0 ) ? $publish_limit : __( 'Unlimite', AT_TEXTDOMAIN ); ?> <?php echo __( 'cars', AT_TEXTDOMAIN ); ?>
			<?php } ?>
		</div>
		<?php /*<a href="#" class="btn1"><?php echo __( 'Refine Your search', AT_TEXTDOMAIN ); ?></a>*/?>
	</div>
	<div class="actions_cars">
		<a href="#" class="btn1" id="apply_actions"><?php echo __( 'apply', AT_TEXTDOMAIN ); ?></a>
		<select class="text" id="select_action_car">
			<option value="0"><?php echo __( 'Select action', AT_TEXTDOMAIN ); ?></option>
			<?php if( $car_status == 'publish' ) { ?>
			<option value="car_republish"><?php echo __( 'Republish', AT_TEXTDOMAIN ); ?></option>
			<option value="car_archive"><?php echo __( 'Remove the archive', AT_TEXTDOMAIN ); ?></option>
			<?php } else { ?>
			<option value="car_publish"><?php echo __( 'Publish', AT_TEXTDOMAIN ); ?></option>
			<?php } ?>
			<?php if ( $paid['featured'] === true ) { ?>
				<!-- <option value="promote_featured"><?php echo __( 'Promote to featured vehicles', AT_TEXTDOMAIN ); ?></option> -->
			<?php } ?>
			<?php if ( $paid['top'] === true ) { ?>
				<!-- <option value="promote_top"><?php echo __( 'Promote to top', AT_TEXTDOMAIN ); ?></option> -->
			<?php } ?>
		</select>
		<div class="label_select"><?php echo __( 'Apply to the marked:', AT_TEXTDOMAIN ); ?></div>
		<div class="mark_all"><label><input type="checkbox" id="select_all_cars"><?php echo __( 'Select all cars on the page', AT_TEXTDOMAIN ); ?></label></div>
	</div>
	<div class="cars_list">
	  <?php if ( count($cars) > 0 ) { ?>
	  	<?php foreach( $cars as $key => $car) { ?>

		<div class="car" id="car-<?php echo $car['ID']; ?>">

			<div class="actions">
				<?php if ( isset($paid) && $paid['featured'] === true ) { ?>
					<!-- <a href="#" class="markered hidden promote_featured" data-id="<?php echo $car['ID']; ?>"><?php echo __( 'Promote to featured', AT_TEXTDOMAIN );?></a> -->
				<?php } ?>
				<?php if ( isset($paid) && $paid['top'] === true ) { ?>
					<a href="#" class="markered hidden promote_top" data-id="<?php echo $car['ID']; ?>"><?php echo __( 'Promote to top', AT_TEXTDOMAIN );?></a>
				<?php } ?>

				<?php if( $car_status == 'publish' ) { ?>
				<a href="#" class="markered car_republish" data-id="<?php echo $car['ID']; ?>"><?php echo __( 'Republish', AT_TEXTDOMAIN );?></a>
				<?php } else { ?>
				<a href="#" class="markered car_publish" data-id="<?php echo $car['ID']; ?>"><?php echo __( 'Publish', AT_TEXTDOMAIN );?></a>
				<?php } ?>
				<a href="<?php echo AT_Common::site_url('profile/vehicles/edit/' . $car['ID'] . '/'); ?>" class="markered"><?php echo __( 'Edit', AT_TEXTDOMAIN ); ?></a>
				<a href="<?php echo get_permalink( $car['ID'] ); ?>" class="markered"><?php echo __( 'View', AT_TEXTDOMAIN ); ?></a>
				<?php if( $car_status == 'publish' ) { ?>
					<?php if( $user_info['is_dealer'] ) { ?>
						<?php if( !$car['options']['_best_offer'] ) { ?>
						<a href="#" class="markered car_add_best_offer" data-id="<?php echo $car['ID']; ?>"><?php echo __( 'Add best offer', AT_TEXTDOMAIN ); ?></a>
						<?php } else { ?>
						<a href="#" class="markered car_remove_best_offer" data-id="<?php echo $car['ID']; ?>"><?php echo __( 'Remove best offer', AT_TEXTDOMAIN ); ?></a>
						<?php } ?>
					<?php } ?>
				<a href="#" class="markered last car_archive" data-id="<?php echo $car['ID']; ?>"><?php echo __( 'Archive', AT_TEXTDOMAIN ); ?></a>
				<?php } ?>
			</div>


			<div class="checkbox"><input type="checkbox" class="select_car" name="select_car" value="<?php echo $car['ID']; ?>" /></div>
			<div class="img">
				<?php if ( count( $car['photo'] ) > 0 ) { ?>
				<img src="<?php echo AT_Common::static_url( $car['photo']['photo_url'] . '165x120/' . $car['photo']['photo_name'] ); ?>" alt=""/>
				<?php } else { ?>
				<img src="<?php echo AT_Common::site_url( AT_URI_THEME . '/framework/assets/images/pics/noimage-small.jpg' ); ?>" alt="" />
				<!--img src="/wp-content/themes/winterjuice/framework/assets/images/pics/catalog.jpg" alt=""/-->
				<?php } ?>
			</div>
			<div class="general_info">
				<h2><?php echo $car['options']['_manufacturer_id']['name'];?> <?php echo $car['options']['_model_id']['name'];?> <?php echo $car['options']['_version'];?></h2>
				<div class="date"><?php echo date( 'F d, Y', strtotime( $car['post_date'] ) ) ?></div>
				<div class="price">
					<?php echo AT_Common::show_full_price($value = $car['options']['_price'], $currency = $car['options']['_currency_id']); ?>
				</div>
			</div>
			<div class="location">
				<?php echo __('Year', AT_TEXTDOMAIN ); ?>: <?php echo $car['options']['_fabrication']; ?><br/>
				<?php if ( !empty( $car['options']['_region_id']) ) { ?>
				<?php echo __('Location:', AT_TEXTDOMAIN ); ?> <?php echo $car['options']['_region_id']['name']; ?>
				<?php } ?>
			</div>
			<div class="statistics_info">
				<div class="item-views"><?php echo $car['views']; ?> <?php echo __( 'views', AT_TEXTDOMAIN ); ?></div>
				<div class="item-id"><?php echo $car['ID']; ?></div>
				<?php /*if( $car['options']['_best_offer'] ) { ?><div class="item-info">best offer</div><?php } */?>
				<div class="item-info"><?php echo ($car['options']['_category_id'] == 1 ) ? __( 'New car', AT_TEXTDOMAIN ) : __( 'Used car', AT_TEXTDOMAIN ) ; ?></div>
			</div>
			<div class="count_photo"><?php echo $car['count_photos']; ?> <?php echo $car['count_photos'] != 1 ? 'photos' : 'photo'; ?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>
	  <?php } ?>
	</div>
	<div class="bottom_catalog_box">
		<?php echo $block['pagination']; ?>
		<div class="clear"></div>
	</div>
</div>