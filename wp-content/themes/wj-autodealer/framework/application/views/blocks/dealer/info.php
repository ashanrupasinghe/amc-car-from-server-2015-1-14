<div class="blog layout_<?php echo $layout; ?>">
	<?php if( count( $best_offers ) > 0 ) { ?>
	<div class="home_slider car_layout_<?php echo $layout; ?>">
		<div class="label"><strong>Best</strong> offer</div>
		<div class="slider slider_1" data-settings='{
							"auto": false,
							"slideWidth": 940,
							"pause": 4000,
							"minSlides": 1,
							"maxSlides": 1,
							"slideMargin": 0,
							"controls" : false}'>
		<?php foreach ($best_offers as $key => $car) { ?>
			<div class="slide">
				<?php if ( count( $car['photo'] ) > 0 ) { ?>
					<a title=" <?php echo $car['options']['_manufacturer_id']['name'];?> <?php echo $car['options']['_model_id']['name'];?> <?php echo $car['options']['_version'];?>" href="<?php echo get_permalink( $car['ID'] ); ?>">
						<img src="<?php echo AT_Common::static_url( $car['photo']['photo_url'] . 'original/' . $car['photo']['photo_name'] ); ?>" alt=" <?php echo $car['options']['_manufacturer_id']['name'];?> <?php echo $car['options']['_model_id']['name'];?> <?php echo $car['options']['_version'];?>"/>
					</a>
				<?php } ?>
				<div class="description">
					<a href="<?php echo get_permalink( $car['ID'] ); ?>">
						<h2 class="title"><?php echo $car['options']['_fabrication']; ?> <?php echo $car['options']['_manufacturer_id']['name'];?> <?php echo $car['options']['_model_id']['name'];?> <?php echo $car['options']['_version'];?></h2>
					</a>
					<p class="desc">
						<?php echo (!empty($car['options']['_mileage']) ? '<span><strong>' . AT_Common::car_mileage(0) . ': </strong>' . number_format((int)$car['options']['_mileage'], 0, '', ',') . '</span>' : '' ); ?>
						<?php echo (!empty($car['options']['_cilindrics']) ? '<span><strong>' . __( 'Engine', AT_TEXTDOMAIN ) . ': </strong>' . $car['options']['_cilindrics'] . ' ' . __( 'cm³', AT_TEXTDOMAIN ) . '</span> ' : '' ); ?>
					</p>
					<div class="price">
						<?php echo AT_Common::show_full_price($value = $car['options']['_price'], $currency = $car['options']['_currency_id']); ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	</div>
	<?php } ?>
	<?php if( isset($block['right_side']) ) echo '<div class="dealer_page">' . $block['right_side'] . '</div>'; ?>
	<?php if( isset($block['right_side']) || count( $best_offers ) > 0 ) { ?>
	<div class="offers">
		<!-- <div class="count"><strong>Offers: </strong>24</div>
		<a href="#" class="all">view all offers</a> -->
	</div>
	<?php } ?>
	<ul class="dealer_catalog">
		<?php foreach ($cars as $key => $car) { ?>
		<li>
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
					<span><?php echo $car['options']['_cilindrics']; ?> <?php echo __( 'cm³', AT_TEXTDOMAIN ); ?> <?php echo $car['options']['_fuel_id']['name']; ?></span>
					<span><?php echo __('Body', AT_TEXTDOMAIN ); ?> <?php echo $car['options']['_body_type_id']['name']; ?></span>
					<span><?php echo number_format((int)$car['options']['_mileage'], 0, '', ' '); ?> <?php echo AT_Common::car_mileage(0); ?></span>
					<span><strong><?php echo ($car['options']['_category_id'] == 1 ) ? __( 'New car', AT_TEXTDOMAIN ) : __( 'Used', AT_TEXTDOMAIN ) ; ?></strong></span>
				</div>
				<a href="<?php echo get_permalink( $car['ID'] ); ?>" class="more markered"><?php echo __('View details', AT_TEXTDOMAIN ); ?></a>
			</div>
		</li>
		<?php } ?>
	</ul>
	<div class="dealer_bottom">
		<?php if( isset( $block['pagination'] ) ) echo $block['pagination']; ?>
		<div class="clear"></div>
	</div>
</div>