<?php if (!defined("AT_DIR")) die('!!!'); ?>
<ul class="catalog_table">
	<?php foreach ($cars as $key => $car) { ?>
		<li class="<?php if($car['owner_info']['is_dealer']) echo 'is_dealer'; ?>" >
			<a href="<?php echo get_permalink( $car['ID'] ); ?>" class="thumb">
				<?php if ( count( $car['photo'] ) > 0 ) { ?>
				<img src="<?php echo AT_Common::static_url( $car['photo']['photo_url'] . '165x120/' . $car['photo']['photo_name'] ); ?>" alt=""/>
				<?php } else { ?>
					<img src="<?php echo AT_Common::site_url( AT_URI_THEME . '/framework/assets/images/pics/noimage-small.jpg' ); ?>" alt="" />
				<?php } ?>
			</a>
			<div class="catalog_desc">
				<?php if ( !empty( $car['options']['_region_id']) ) { ?>
				<div class="location"><?php echo __('Location:', AT_TEXTDOMAIN ); ?> <?php echo $car['options']['_region_id']['name']; ?></div>
				<?php } ?>
				<div class="title_box">
					<h4><a href="<?php echo get_permalink( $car['ID'] ); ?>"><?php echo $car['options']['_manufacturer_id']['name'];?> <?php echo $car['options']['_model_id']['name'];?> <?php echo $car['options']['_version'];?></a></h4>
					<div class="price">
						<?php echo AT_Common::show_full_price($value = $car['options']['_price'], $currency = $car['options']['_currency_id']); ?>
					</div>
				</div>
				<div class="clear"></div>
				<div class="grey_area">
					<span><?php echo __('Year', AT_TEXTDOMAIN ); ?>: <?php echo $car['options']['_fabrication']; ?></span>
					<span><?php echo $car['options']['_cilindrics']; ?> cm³ <?php echo $car['options']['_fuel_id']['name']; ?></span>
					<span><?php echo __('Body', AT_TEXTDOMAIN ); ?> <?php echo $car['options']['_body_type_id']['name']; ?></span>
					<span><?php echo number_format((int)$car['options']['_mileage'], 0, '', ' '); ?> <?php echo AT_Common::car_mileage(0); ?></span>
					<span><strong><?php echo ($car['options']['_category_id'] == 1 ) ? __( 'New car', AT_TEXTDOMAIN ) : __( 'Used', AT_TEXTDOMAIN ) ; ?></strong></span>
				</div>
				<div style="float:right">
					<?php if($car['owner_info']['is_dealer']) { ?>
						<a href="<?php echo AT_Common::site_url( 'dealer/info/' . trim( $car['owner_info']['alias'] . '-'  . $car['owner_info']['id'], '-') . '/' ); ?>"><i class="icon-award-empty is-dealer award"></i><?php echo $car['owner_info']['name']; ?></a>
					<?php } else { ?>
						<?php echo $car['owner_info']['name']; ?>
					<?php } ?>
				</div>
				<a href="<?php echo get_permalink( $car['ID'] ); ?>" class="more markered"><?php echo __('View details', AT_TEXTDOMAIN ); ?></a>
			</div>
		</li>
	<?php } ?>
</ul>