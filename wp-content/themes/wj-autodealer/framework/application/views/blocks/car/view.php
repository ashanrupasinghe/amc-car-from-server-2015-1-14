<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_style( 'print.css', 'assets/css/print.css', array(), 'print' ); ?>
<div class="main_wrapper <?php if($contacts_owner['is_dealer']) echo 'is_dealer'; ?>" >
	<div class="cars_id">
		<div class="id"><?php echo __( 'Offer ID', AT_TEXTDOMAIN ); ?> <span>C<?php echo get_the_ID(); ?></span></div>
		<div class="views"><?php echo __( 'The offer had', AT_TEXTDOMAIN ); ?> <?php echo $car_views; ?> <?php echo __( 'Views', AT_TEXTDOMAIN ); ?></div>
	</div>
	<h1>
		<strong><?php echo $car_info['options']['_manufacturer_id']['name'];?></strong> <?php echo $car_info['options']['_model_id']['name'];?> <?php echo $car_info['options']['_version'];?>
		<?php if( $car_info['post_status'] == 'archive' ) _e( 'Archived', AT_TEXTDOMAIN ); ?> 
	</h1>
	<div class="car_image_wrapper">
		<?php if($contacts_owner['is_dealer']) { ?>
			<div class="trusted"><?php _e( 'Featured', AT_TEXTDOMAIN ); ?></div>
		<?php } ?>
		<?php if (count($car_info['photo']) > 0) { ?>
		<div class="big_image">
			<a href="<?php echo AT_Common::static_url($car_info['photo']['photo_url'] . 'original/' . $car_info['photo']['photo_name']);?>" rel="car_group">
				<img src="<?php echo AT_Common::static_url('/assets/images/zoom.png');?>" alt="" class="zoom"/>
				<img src="<?php echo AT_Common::static_url($car_info['photo']['photo_url'] . '480x290/' . $car_info['photo']['photo_name']);?>" alt=""/>
			</a>
		</div>
		<?php } ?>
		<?php if (count($car_photos) > 1) { ?>
		<div class="small_img">
			<?php foreach ($car_photos as $key => $photo) { 
				if ( $photo['is_main'] ) continue; ?>
				<a href="<?php echo AT_Common::static_url($photo['photo_url'] . 'original/' . $photo['photo_name']);?>" rel="car_group">
					<img src="<?php echo AT_Common::static_url($photo['photo_url'] . '81x62/' . $photo['photo_name']);?>" alt=""/>
				</a>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<div class="car_characteristics">
		<a href="#" class="print" onclick="window.print();return false;"></a>
		<div class="price" data-currency="<?php echo $car_info['options']['_currency_id']['name']; ?>" data-price="<?php echo $car_info['options']['_price']; ?>">
			<?php if ( isset($car_info['options']['_price']) ) { 
				echo AT_Common::show_full_price($value = $car_info['options']['_price'], $currency = $car_info['options']['_currency_id']);
			} ?>
			<?php if($car_info['options']['_price_negotiable']) { ?>
			<span><?php echo __( '* Price negotiable', AT_TEXTDOMAIN ); ?></span>
			<?php } ?>
		</div>
		<div class="clear"></div>
		<div class="features_table">
			<div class="line">
				<div class="left"><?php echo __( 'Model', AT_TEXTDOMAIN ); ?>, <?php echo __( 'Body type', AT_TEXTDOMAIN ); ?>:</div>
				<div class="right"><?php echo $car_info['options']['_manufacturer_id']['name'];?> <?php echo $car_info['options']['_model_id']['name'];?> <?php echo $car_info['options']['_version'];?>, <?php echo $car_info['options']['_body_type_id']['name'];?></div>
			</div>
			<?php if( !empty( $car_info['options']['_fabrication'] ) ) { ?>
			<div class="line">
				<div class="left"><?php echo __( 'Fabrication', AT_TEXTDOMAIN ); ?>:</div>
				<div class="right"><?php echo $car_info['options']['_fabrication'];?></div>
			</div>
			<?php } ?>
			<?php if( !empty( $car_info['options']['_fuel_id'] ) ) { ?>
			<div class="line">
				<div class="left"><?php echo __( 'Fuel', AT_TEXTDOMAIN ); ?>:</div>
				<div class="right"><?php echo $car_info['options']['_fuel_id']['name'];?></div>
			</div>
			<?php } ?>
			<?php if( !empty( $car_info['options']['_cilindrics'] ) ) { ?>
			<div class="line">
				<div class="left"><?php echo __( 'Engine', AT_TEXTDOMAIN ); ?>:</div>
				<div class="right"><?php echo $car_info['options']['_cilindrics']; ?> cm³<!--3200 cm³ (373 kW / 507 CP)--></div>
			</div>
			<?php } ?>
			<?php if( !empty( $car_info['options']['_transmission_id'] ) ) { ?>
			<div class="line">
				<div class="left"><?php echo __( 'Transmision', AT_TEXTDOMAIN ); ?>:</div>
				<div class="right"><?php echo $car_info['options']['_transmission_id']['name'];?></div>
			</div>
			<?php } ?>
			<?php if( !empty( $car_info['options']['_color_id'] ) ) { ?>
			<div class="line">
				<div class="left"><?php echo __( 'Color', AT_TEXTDOMAIN ); ?>:</div>
				<div class="right">
					<span class="color" style="background-color:<?php echo $car_info['options']['_color_id']['alias']; ?>"></span> <?php echo $car_info['options']['_color_id']['name']; ?>
				</div>
			</div>
			<?php } ?>
			<?php if( !empty( $car_info['options']['_door_id'] ) ) { ?>
			<div class="line">
				<div class="left"><?php echo __( 'Doors', AT_TEXTDOMAIN ); ?>:</div>
				<div class="right"><?php echo $car_info['options']['_door_id']['name']; ?></div>
			</div>
			<?php } ?>
			<?php if( !empty( $car_info['options']['_mileage'] ) ) { ?>
			<div class="line">
				<div class="left"><?php echo __( 'Mileage', AT_TEXTDOMAIN ); ?>:</div>
				<div class="right"><?php echo number_format((int)$car_info['options']['_mileage'], 0, '', ' ') . ' ' . AT_Common::car_mileage(0); ?></div>
			</div>
			<?php } ?>
		</div>
		<?php if( $car_info['post_status'] == 'publish' && isset( $contacts_owner['add_offer'] ) && $contacts_owner['add_offer'] ) { ?>
		<?php $this->add_script( 'jquery-ui-core'); ?>
		<?php $this->add_script( 'jquery-ui-dialog'); ?>
		<?php $this->add_script( 'add_offer', 'assets/js/add_offer.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' )); ?>

		<?php $this->add_style( 'jquery-ui', 'assets/css/jquery-ui/jquery-ui.min.css');?>
		<?php $this->add_style( 'jquery-ui-theme', 'assets/css/jquery-ui/jquery-ui.theme.css');?>
		<?php if ($this->get_option( 'add_offer_btn', false ) == false ) { ?>
			<div class="wanted_line">
				<div class="left"><?php echo __( 'Interested in this vehicle?', AT_TEXTDOMAIN ); ?></div>
				<div class="right">
					<a href="#" id="add_offer" data-id="<?php echo $car_info['ID']; ?>"><?php echo __( 'add an offer', AT_TEXTDOMAIN ); ?></a>
					<div id="dialog_add_offer"  title="<?php echo __( 'Add offer', AT_TEXTDOMAIN);?>" style='display:none;'>
						<form action="#" method="post" id="add_offer_form">
							<div class="row">
								<label><?php echo __( 'Full name', AT_TEXTDOMAIN ); ?></label>
								<input type="text" name="fullname" id="fullname" value="" class="text"/>
							</div>
							<div class="row">
								<label><?php echo __( 'Email', AT_TEXTDOMAIN ); ?></label>
								<input type="text" name="email" id="email" value="" class="text"/>
							</div>
							<div class="row">
								<label><?php echo __( 'Offer details', AT_TEXTDOMAIN ); ?></label>
								<textarea name="offer_details" id="offer_details" class="text"></textarea>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php } ?>
	</div>
	<div class="clear"></div>
	<div class="info_box">
		<div class="car_info">
			<div class="info_left">
				<h2><?php echo __( '<strong>Vehicle</strong> information', AT_TEXTDOMAIN ); ?></h2>
				<p><?php /*<strong><?php echo __( 'Other parameters', AT_TEXTDOMAIN ); ?>, :</strong><br/>*/ ?>
				<?php
					$i = 0;
					foreach ($equipments as $key => $value) {
						if ( (isset($car_info['options'][$key]) && $car_info['options'][$key] == 1) || isset($car_info['options'][$key]) && $car_info['options'][$key] == 'on' ) {
							if ($i) { 
								echo ', ';
							}
							echo strtolower( $value['name'] );
							$i++;
						}
					}
				?>
				</p>
			</div>
			<div class="info_right">
				<h2><?php echo __( '<strong>More</strong> info', AT_TEXTDOMAIN ); ?></h2>
				<p class="first"><?php echo strip_tags($car_info['post_content']); ?></p>
			</div>
			<div class="clear"></div>
		</div>
		<?php if( $car_info['post_status'] == 'publish' ) { ?>
		<div class="car_contacts">
			<h2><?php echo __( '<strong>Contact</strong> details', AT_TEXTDOMAIN ); ?></h2>
			<p><?php echo $this->get_option('text_contact_details', ''); ?></p>
			<div class="left">
				<?php if(!empty( $contacts_owner['phones'] )) { ?>
					<div class="phones detail single_line spaced"><?php echo $contacts_owner['phones']; ?></div>
				<?php } ?>
					<div class="user-detail">
						<?php if ( count( $contacts_owner['photo'] ) ) { ?>
							<a href="<?php echo $contacts_owner['url']; ?>">
							<img style="max-width:70px;float:left;margin-right:20px;" src="<?php echo AT_Common::static_url( $contacts_owner['photo']['photo_url'] . '138x138/' . $contacts_owner['photo']['photo_name'] ); ?>" alt=""/>
							</a>
						<?php } ?>
						<?php if ( isset($is_dealer) && $is_dealer == 1 ) { ?>
							<i class="icon-award is-dealer award"></i>
						<?php } else { ?>
							<i class="icon-user"></i>
						<?php } ?>
						<?php if (!empty( $contacts_owner['url'] )) { ?>
							<a href="<?php echo $contacts_owner['url']; ?>">
							<?php echo $contacts_owner['name']; ?>
							</a>
						<?php } else { ?>
							<?php echo $contacts_owner['name']; ?>
						<?php } ?>
						<?php if(!empty( $contacts_owner['adress'] )) { ?>
							<div class="owner_location"><?php echo $contacts_owner['adress']; ?></div>
						<?php } ?>
						<?php if(!empty( $contacts_owner['email'] )) { ?>
							<div class="details mail-link"><a href="#" rel="<?php echo AT_Common::nospam( $contacts_owner['email'] ); ?>" class="email_link_noreplace markered"><?php echo __( 'Contact seller', AT_TEXTDOMAIN ); ?></a></div>
						<?php } ?>
					</div>
			</div>
			<div class="right">
				<?php
				$_item_location = $car_info['options']['_region_id']['name'];
				if( isset($car_info['options']['_state_id']) && !empty($car_info['options']['_region_id']['name']) ) {
					$_item_location = $car_info['options']['_state_id']['name'] . ', ' . $_item_location;
				}
				?>
				<div class="addr detail single_line"><?php echo $_item_location; ?></div>
				<?php if(!empty( $contacts_owner['url'] )) { ?>
					<div class="web detail single_line">
						<a href="<?php echo $contacts_owner['url']; ?>">
							<?php _e( 'View', AT_TEXTDOMAIN ) ?>
							<strong><?php echo $contacts_owner['name'];?></strong>
							<?php _e( 'page', AT_TEXTDOMAIN ) ?>
						</a>
					</div>
				<?php } ?>
			</div>
			<div class="clear"></div>
			<?php if(!empty( $contacts_owner['adress'] ) && $this->get_option( 'dealer_map', false ) == true ) { ?>
			<div id="dealer_map_canvas" style="width:100%; height: <?php echo $this->get_option( 'dealer_map_height', false ) ? $this->get_option( 'dealer_map_height', false ) : 100 ; ?>px;margin-bottom: 20px"></div>
			<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
			<script>
					function dealer_map_init() {
						var geocoder = new google.maps.Geocoder();
						var map_canvas = document.getElementById('dealer_map_canvas');
						var dealer_address = '<?php echo str_replace("'",'',$_item_location); ?>';
						// var map_center = new google.maps.address('<?php echo str_replace("'",'',$contacts_owner['adress']); ?>');
						var map_options = {
							center: new google.maps.LatLng(44.5403, -78.5463),
							zoom: 14,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						}
						var map = new google.maps.Map(map_canvas, map_options);
						if (geocoder) {
						  geocoder.geocode( { 'address': dealer_address}, function(results, status) {
						    if (status == google.maps.GeocoderStatus.OK) {
						      if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
						      map.setCenter(results[0].geometry.location);
						        var marker = new google.maps.Marker({
						            position: results[0].geometry.location,
						            map: map, 
						            title: dealer_address
						        }); 
						      }
						    }
						  });
						}

					}
					google.maps.event.addDomListener(window, 'load', dealer_map_init);
			</script>
			<?php } ?>
			<?php if( AT_Common::is_user_logged() && AT_Common::get_logged_user_id() == $car_info['options']['_owner_id'] ){ ?>
			<a href="<?php echo AT_Common::site_url( 'profile/vehicles/edit/' . $car_info['ID'] . '/' ); ?>" class="btn2"><?php echo __( 'Edit vehicle', AT_TEXTDOMAIN ); ?></a>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php } ?>
	</div>
	<div class="car_sidebar">
		<?php $this->add_widget( 'sidebar_widget', array( 'name' => 'car' ) ); ?>
	</div>
	<div class="clear"></div>
	<?php echo $block['recent_cars']; ?>
</div>