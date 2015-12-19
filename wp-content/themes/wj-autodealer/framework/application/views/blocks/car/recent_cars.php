<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php if ( !empty( $cars ) ) { ?>
<div class="recent_cars">
	<h2><?php echo __( '<strong>Similar</strong> offers', AT_TEXTDOMAIN ); ?></h2>
	<ul>
		<?php foreach ($cars as $key => $car) { ?>
			<li<?php if ($key == 3) echo ' class="last"'; ?>>
			<?php
				$cost = AT_Common::show_full_price($value = $car['options']['_price'], $currency = $car['options']['_currency_id']);
				echo '
				<a href="' . get_permalink( $car['ID'] ) . '">
					<img src="' . AT_Common::static_url( $car['photo']['photo_url'] . '213x164/' . $car['photo']['photo_name'] ) . '" alt="' . $car['options']['_manufacturer_id']['name'] . ' ' . $car['options']['_model_id']['name'] . '"/>
					<div class="description">
						Registration ' . $car['options']['_fabrication'] . '<br/>' . 
						(!empty($car['options']['_cilindrics']) ? $car['options']['_cilindrics'] . ' cm³ ' : '' ) . 
						(!empty($car['options']['_fuel_id']['name']) ? $car['options']['_fuel_id']['name'] . '<br/>' : '' ) . 
						(!empty($car['options']['_engine_power']) ? $car['options']['_engine_power'] . ' HP<br/>' : '' ) . 
						(!empty($car['options']['_body_type_id']['name']) ? 'Body ' . $car['options']['_body_type_id']['name'] . '<br/>' : '' ) . 
						(!empty($car['options']['_mileage']) ? number_format((int)$car['options']['_mileage'], 0, '', ' ') . ' ' . AT_Common::car_mileage(0) : '' ) .
					'</div>
					<div class="title">' . $car['options']['_manufacturer_id']['name'] . ' ' . $car['options']['_model_id']['name'] . ' <span class="price">' . $cost . '</span></div>
				</a>';
			?>
			</li>
		<?php } ?>
		<!-- <li class="last">
			<a href="#">
				<img src="<?php echo AT_Common::static_url('/assets/images/pics/recent_1.jpg');?>" alt=""/>
				<div class="description">Registration 2010<br/>3.0 Diesel<br/>230 HP<br/>Body Coupe<br/>80 000 Miles</div>
				<div class="title">Mercedes-Benz <span class="price">$ 115 265</span></div>
			</a>
		</li> -->
	</ul>
</div>
<?php } ?>