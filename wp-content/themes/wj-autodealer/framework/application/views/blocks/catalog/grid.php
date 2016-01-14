<?php if (!defined("AT_DIR")) die('!!!'); ?>

<ul class="catalog_list">
	<?php $i = 1; ?>
	<?php foreach ($cars as $key => $car) { ?>
	<li class="<?php if ( $i==4 ) {?>last<?php $i = 0; } ?> <?php if($car['owner_info']['is_dealer']) echo 'is_dealer'; ?>">
		<a href="<?php echo get_permalink( $car['ID'] ); ?>">
			<?php if ( count( $car['photo'] ) > 0 ) { ?>
				<img src="<?php echo AT_Common::static_url( $car['photo']['photo_url'] . '213x164/' . $car['photo']['photo_name'] ); ?>"/>
			<?php } else { ?>
				<img src="<?php echo AT_Common::site_url( AT_URI_THEME . '/framework/assets/images/pics/noimage-small.jpg' ); ?>" alt="" />
			<?php } ?>
			<div class="description">
				<?php if($car['owner_info']['is_dealer']) { ?>
					<i class="icon-award-empty is-dealer award" style="float: right"></i>
				<?php } ?>
				<?php _e('Year', AT_TEXTDOMAIN ); ?> <?php echo $car['options']['_fabrication']; ?><br>
				<?php echo $car['options']['_cilindrics']; ?> cm³ <?php echo $car['options']['_fuel_id']['name']; ?><br>
				<?php _e('Body', AT_TEXTDOMAIN ); ?> <?php echo $car['options']['_body_type_id']['name']; ?><br>
				<?php echo number_format((int)$car['options']['_mileage'], 0, '', ' '); ?> <?php echo AT_Common::car_mileage(1); ?><br>
				<strong><?php echo ($car['options']['_category_id'] == 1 ) ? __( 'New', AT_TEXTDOMAIN ) : __( 'Used', AT_TEXTDOMAIN ) ; ?></strong>
			</div>
			<div class="title">
				<?php echo $car['options']['_manufacturer_id']['name'];?> 
				<span class="price">
					<?php echo AT_Common::show_full_price($value = $car['options']['_price'], $currency = $car['options']['_currency_id']); ?>
				</span>
			</div>
		</a>
	</li>
	<?php $i++; ?>
	<?php } ?>
</ul>