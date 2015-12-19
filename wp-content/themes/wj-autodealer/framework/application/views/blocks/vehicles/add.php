<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'jquery.selectik', 'assets/js/jquery/jquery.selectik.js'); ?>
<?php $this->add_script( 'jquery.form', 'assets/js/jquery/jquery.form.js'); ?>
<?php $this->add_script( 'jquery.validate', 'assets/js/jquery/jquery.validate.js'); ?>
<?php $this->add_script( 'plupload', 'assets/js/plupload/plupload.full.min.js'); ?>
<?php $this->add_script( 'vehicles', 'assets/js/vehicles.js'); ?>
<?php $this->add_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?v=3.exp'); ?>
<?php $this->add_script( 'jquery.gmap3', 'assets/js/jquery/jquery.gmap3.js'); ?>
<?php 
	if( $owner_info['is_dealer'] ){
		$car_limit_photos = $this->get_option( 'car_limit_photos_dealer', '6' );
	} else {
		$car_limit_photos = $this->get_option( 'car_limit_photos', '6' );
	}
	$this->add_localize_script( 'vehicles', 'car_limit_photos', $car_limit_photos );
?>
<div class="main_wrapper">
<?php if (empty($car_info)) { ?>
	<h1><?php echo __( '<strong>Add</strong> Car', AT_TEXTDOMAIN );?></h1>
	<form method="post" action="<?php echo AT_Common::site_url('profile/vehicles/add/');?>" id="vehicle-form">
<?php } else { ?>
	<h1><?php echo __( '<strong>Edit</strong> Car', AT_TEXTDOMAIN );?></h1>
	<form method="post" action="<?php echo AT_Common::site_url('profile/vehicles/edit/' . $car_info['ID'] .'/');?>" id="vehicle-form">
<?php } ?>
	<div class="step_breadcrumb">
		<a href="#" class="active" id="step_1"><?php echo __( 'step 1. announcement', AT_TEXTDOMAIN );?></a>
		<a href="#" class="" id="step_2"><?php echo __( 'step 2. specifications', AT_TEXTDOMAIN );?></a>
		<a href="#" class="" id="step_3"><?php echo __( 'step 3. item location', AT_TEXTDOMAIN );?></a>
		<a href="#" class=""  id="step_4"><?php echo __( 'step 4 photo', AT_TEXTDOMAIN );?></a>
	</div>
	<div class="sell_box step_1" style="display:;">
		<h2><?php echo __( '<strong>Vehicle</strong> data', AT_TEXTDOMAIN );?></h2>
		<div class="col_1">
			<div class="select_wrapper">
				<label><span>* </span><strong><?php echo __( 'Transport Type:', AT_TEXTDOMAIN ); ?></strong></label>
				<select class=" text" id="_transport_type_id" name="_transport_type_id" required value-invalid="0" >
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($transport_types as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_transport_type_id']) && $car_info['options']['_transport_type_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="select_wrapper">
				<label><span>* </span><strong><?php echo __( 'Make:', AT_TEXTDOMAIN ); ?></strong></label>
				<select class=" text select2" id="_manufacturer_id" name="_manufacturer_id" <?php echo ( !empty($car_info['options']['_manufacturer_id'] )  ? 'readonly' : ' required  value-invalid="0"' ); ?>>
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php if( !empty( $car_info['options']['_manufacturer_id'] ) ) { ?>
					<option selected="selected" value="<?php echo $car_info['options']['_manufacturer_id']['id']; ?>"><?php echo $car_info['options']['_manufacturer_id']['name']; ?></option>
					<?php } else { ?>
						<?php foreach ($manufacturers as $key => $value) { ?>
							<option <?php echo (!empty($car_info['options']['_manufacturer_id']) && $car_info['options']['_manufacturer_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
			<div class="select_wrapper">
				<label><strong><?php echo __( 'Model:', AT_TEXTDOMAIN ); ?></strong></label>
				<select class=" text select2" id="_model_id" name="_model_id" <?php echo ( !empty($car_info['options']['_model_id'] ) ? 'readonly' : '' ); ?>>
					<?php if( !empty( $car_info['options']['_model_id'] ) ) { ?>
						<option selected="selected" value="<?php echo $car_info['options']['_model_id']['id']; ?>"><?php echo $car_info['options']['_model_id']['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="input_wrapper">
				<label><span> </span><strong><?php _e( 'Version:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" id="_version" name="_version" value="<?php if (!empty($car_info['options']['_version'])) echo $car_info['options']['_version']; ?>"/>
			</div>

			<!--
 			<div class="input_wrapper">
				<label><span> </span>

				<span class="custom_chb">
					<input name="_custom_model_name" type="checkbox" id="_custom_model_name" />
				</span>

				<strong><?php echo __( 'Custom model:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" id="_model_alter" name="_model_alter" value="" placeholder="<?php _e( 'custom model value', AT_TEXTDOMAIN ); ?>" />
			</div>
			-->
		</div>
		<div class="col_2">
			<div class="select_wrapper">
				<label><span>* </span><strong><?php echo __( 'Body Type:', AT_TEXTDOMAIN ); ?></strong></label>
				<select name="_body_type_id" id="_body_type_id" class="text" required value-invalid="0">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($body_types as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_body_type_id']) && $car_info['options']['_body_type_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="select_wrapper">
				<label><span>* </span><strong><?php echo __( 'Year:', AT_TEXTDOMAIN ); ?></strong></label>
				<select name="_fabrication" id="_fabrication" class="text select2" required value-invalid="0">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php for($i = date('Y'); $i >= 1912; $i--) { ?>
						<option  <?php echo (!empty($car_info['options']['_fabrication']) && $car_info['options']['_fabrication'] == $i) ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="input_wrapper with_text">
				<label><span>* </span><strong><?php echo __( 'Mileage:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" name="_mileage" id="_mileage" required value="<?php if (isset($car_info['options']['_mileage'])) echo $car_info['options']['_mileage']; ?>"/>
				<?php echo AT_Common::car_mileage(2); ?>
			</div>
			<div class="input_wrapper">
				<label><span> </span><strong><?php echo __( 'VIN / chassis number:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" id="_vin" name="_vin" value="<?php if (!empty($car_info['options']['_vin'])) echo $car_info['options']['_vin']; ?>"/>
			</div>
		</div>
		<div class="col_3">
			<div class="input_wrapper price">
				<label><span>* </span><strong><?php echo __( 'Price:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" id="_price" name="_price" required number value="<?php if (!empty($car_info['options']['_price'])) echo $car_info['options']['_price']; ?>" onblur="if(this.value=='') this.value='0.00';" onfocus="if(this.value=='0.00') this.value='';"/>
			</div>
			<div class="input_wrapper price">
				<label><span>* </span><strong><?php echo __( 'Currency:', AT_TEXTDOMAIN ); ?></strong></label>
				<select class="text" id="_currency_id" name="_currency_id">
					<?php foreach ($currencies as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_currency_id']) && $car_info['options']['_currency_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="select_wrapper">
				<label><span> </span><strong><?php echo __( 'Technical condition:', AT_TEXTDOMAIN ); ?> </strong></label>
				<select class="text" id="_technical_condition_id" name="_technical_condition_id">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($technical_conditions as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_technical_condition_id']) && $car_info['options']['_technical_condition_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<!--
			<?php if( count($affiliates) > 0 ) {?>
			<div class="select_wrapper">
				<label><span> </span><strong><?php echo __( 'Affiliate:', AT_TEXTDOMAIN ); ?> </strong></label>
				<select class="text" id="_affiliate_id" name="_affiliate_id">
					<option value="0"><?php echo __( 'Default affiliate', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($affiliates as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_affiliate_id']) && $car_info['options']['_affiliate_id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<?php } ?>
			-->
		</div>
		<div class="clear"></div>
		<div class="col_all">
			<div class="input_wrapper fullwidth">
				<label><span> </span><strong><?php echo __( 'Description:', AT_TEXTDOMAIN ); ?> </strong></label>
				<textarea class="text" id="post_content" name="post_content"><?php echo (!empty($car_info['post_content']) ? $car_info['post_content'] : ''); ?></textarea>
			</div>
		</div>
		<div class="clear"></div>
		<h2></h2>
		<div class="checkbox_items">
			<span class="custom_chb_wrapper">
				<span class="custom_chb">
					<input name="_price_negotiable" type="checkbox" id="_price_negotiable" <?php if( isset($car_info) && (isset($car_info['options']) && isset($car_info['options']['_price_negotiable']) && !empty($car_info['options']['_price_negotiable']) && $car_info['options']['_price_negotiable'] == 'on' || $car_info['options']['_price_negotiable'] == '1')) { ?>checked="checked"<?php } ?>/>
					<input name="_price_negotiable" type="hidden" id="_price_negotiable" <?php if( isset($car_info) && (isset($car_info['options']) && isset($car_info['options']['_price_negotiable']) && !empty($car_info['options']['_price_negotiable']) && $car_info['options']['_price_negotiable'] == 'on' || $car_info['options']['_price_negotiable'] == '1' )) { ?>value="1"<?php }else{ ?> value="0"<?php } ?>/>
				</span>
				<label><?php echo __( 'Price Negotiable', AT_TEXTDOMAIN ); ?></label>
			</span>
			<!-- <span class="custom_chb_wrapper">
				<span class="custom_chb">
					<input type="checkbox" name="">
				</span>
				<label><?php echo __( 'Can be exchanged for real estate', AT_TEXTDOMAIN ); ?></label>
			</span>
			<span class="custom_chb_wrapper">
				<span class="custom_chb">
					<input type="checkbox" name="">
				</span>
				<label><?php echo __( 'Can be exchanged for real estate', AT_TEXTDOMAIN ); ?></label>
			</span> -->
		</div>
		<h2></h2>
		<div class="btn-next">
			<a href="#" class="btn1"><?php echo __( 'Next', AT_TEXTDOMAIN ); ?></a>
		</div>
	</div>
	<div class="sell_box step_2" style="display:none;">
		<h2><?php echo __( '<strong>Technical</strong> Specifications', AT_TEXTDOMAIN );?></h2>
		<div class="col_1">
			<div class="select_wrapper">
				<label><span> </span><strong><?php echo __( 'Transmission:', AT_TEXTDOMAIN ); ?></strong></label>
				<select name="_transmission_id" id="_transmission_id" class="text">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($transmissions as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_transmission_id']) && $car_info['options']['_transmission_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="select_wrapper">
				<label><span> </span><strong><?php echo __( 'Drive:', AT_TEXTDOMAIN ); ?></strong></label>
				<select class=" text" id="_drive_id" name="_drive_id">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($drive as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_drive_id']) && $car_info['options']['_drive_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="select_wrapper">
				<label><span> </span><strong><?php echo __( 'Number of doors:', AT_TEXTDOMAIN ); ?></strong></label>
				<select name="_door_id" id="_door_id" class="text">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($doors as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_door_id']) && $car_info['options']['_door_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col_2">
			<div class="input_wrapper">
				<label><span> </span><strong><?php echo __( 'Number of seats:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" name="_seats" id="_seats" value="<?php if (!empty($car_info['options']['_seats'])) { echo $car_info['options']['_seats'];} ?>" />
			</div>
			<div class="select_wrapper">
				<label><span> </span><strong><?php echo __( 'Color:', AT_TEXTDOMAIN ); ?></strong></label>
				<select class=" text" id="_color_id" name="_color_id">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($colors as $key => $value) { ?>
						<option data-selectik="<span style='background-color:<?php echo $value['alias']; ?>'></span><?php echo $value['name']; ?>" data-color="<?php echo $value['alias']; ?>" <?php echo (!empty($car_info['options']['_color_id']) && $car_info['options']['_color_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="select_wrapper">
				<label><span> </span><strong><?php echo __( 'Fuel:', AT_TEXTDOMAIN ); ?></strong></label>
				<select name="_fuel_id" id="_fuel_id" class="text">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($fuels as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_fuel_id']) && $car_info['options']['_fuel_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col_3">
			<div class="select_wrapper with_text">
				<label><span> </span><strong><?php echo __( 'Engine:', AT_TEXTDOMAIN ); ?></strong></label>
				<select name="_cilindrics" id="_cilindrics" class="text">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php $car_engine_range = $this->get_option( 'car_engine_range', array( 'min' => 900, 'max' => 6500 ) );?>
					<?php for($i = $car_engine_range['min']; $i <= $car_engine_range['max']; $i=$i+100) { ?>
						<option <?php echo (!empty($car_info['options']['_cilindrics']) && $car_info['options']['_cilindrics'] == $i) ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select>
				<?php echo __( 'cm³', AT_TEXTDOMAIN ); ?>
			</div>
			<div class="input_wrapper with_text">
				<label><span> </span><strong><?php echo __( 'Engine power:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" name="_engine_power" id="_engine_power" value="<?php if (!empty($car_info['options']['_engine_power'])) echo $car_info['options']['_engine_power']; ?>" />
				<?php echo __( 'kW', AT_TEXTDOMAIN ); ?>
			</div>
			<div class="select_wrapper">
				<label><span> </span><strong><?php echo __( 'Category:', AT_TEXTDOMAIN ); ?></strong></label>
				<select name="_category_id" id="_category_id" class="text">
					<option value="1" <?php echo (!empty($car_info['options']['_category_id']) && $car_info['options']['_category_id']==1) ? 'selected="selected"' : '';?>><?php echo __( 'New car', AT_TEXTDOMAIN ); ?></option>
					<option value="2" <?php echo (empty($car_info['options']['_category_id']) || $car_info['options']['_category_id']==2) ? 'selected="selected"' : '';?>><?php echo __( 'Used', AT_TEXTDOMAIN ); ?></option>
				</select>
			</div>
		</div>
		<div class="clear"></div>
		<h2></h2>
		<?php /*		
		<span class="custom_chb_wrapper">
			<strong>Safety</strong>
		</span>
		*/?>
		<?php $current = 1; ?>
		<?php foreach ( $equipments as $key => $value) { ?>
			<div class="<?php echo ($current%4 == 0) ? 'col_opt_last': 'col_opt';?>">
				<span class="custom_chb_wrapper">
					<span class="custom_chb">
						<input id="<?php echo $value['alias']; ?>" name="<?php echo $value['alias']; ?>" type="checkbox" <?php if( isset($car_info) && !empty($car_info['options'][$key]) && $car_info['options'][$key] == 'on' || isset($car_info) && $car_info['options'][$key] == '1' ) { ?>checked="checked"<?php } ?>/>
						<input id="<?php echo $value['alias']; ?>" name="<?php echo $value['alias']; ?>" type="hidden" <?php if( isset($car_info) && !empty($car_info['options'][$key]) && $car_info['options'][$key] == 'on' || isset($car_info) && $car_info['options'][$key] == '1' ) { ?>value="1"<?php }else{ ?> value="0"<?php } ?>/>
					</span>
					<label><?php echo $value['name']; ?></label>
				</span>
			</div>
		<?php $current++; } ?>
		<div class="clear"></div>
		<h2></h2>
		<div class="btn-next">
			<a href="#" class="btn3"><?php echo __( 'Back', AT_TEXTDOMAIN );?></a>
			<a href="#" class="btn1"><?php echo __( 'Next', AT_TEXTDOMAIN );?></a>
		</div>
	</div>
	<div class="sell_box step_3" style="display:none;">
		<h2><?php echo __( '<strong>Item</strong> location', AT_TEXTDOMAIN );?></h2>
		<div class="col_1">
			<div class="select_wrapper">
				<label><span>* </span><strong><?php echo __( 'Country:', AT_TEXTDOMAIN ); ?></strong></label>
				<select class="text select2" id="_region_id" name="_region_id" required value-invalid="0">
					<option value="0"><?php echo __( 'Select', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($regions as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_region_id']) && $car_info['options']['_region_id']['id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="select_wrapper">
				<label><strong><?php echo __( 'State:', AT_TEXTDOMAIN ); ?></strong></label>
				<select class="text select2" id="_state_id" name="_state_id" required value-invalid="0">
				<?php if (!empty($car_info['options']['_region_id'])) { ?>
					<?php foreach ($states as $value) { ?>
						<option <?php echo (!empty($car_info['options']['_state_id']) && $car_info['options']['_state_id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				<?php } ?>
				</select>
			</div>
			<!--
			<div class="input_wrapper price">
				<label><strong><?php echo __( 'City/Town:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" id="_city" name="_city" value="<?php if (!empty($car_info['options']['_city'])) echo $car_info['options']['_city']; ?>"/>
			</div>

			<div class="input_wrapper price">
				<label><strong><?php echo __( 'Zip Code:', AT_TEXTDOMAIN ); ?></strong></label>
				<input type="text" class="text" id="_zip" name="_zip" value="<?php if (!empty($car_info['options']['_zip'])) echo $car_info['options']['_zip']; ?>"/>
			</div>
			-->
			<?php if( count($affiliates) > 0 ) {?>
			<div class="select_wrapper">
				<div class="heading-compromise compromise-or">
					<h4>
						<?php echo __( 'and/or', AT_TEXTDOMAIN ); ?>
					</h4>
				</div>
				<label><span> </span><strong><?php echo __( 'Affiliate:', AT_TEXTDOMAIN ); ?> </strong></label>
				<select class="text" id="_affiliate_id" name="_affiliate_id">
					<option value="0"><?php echo __( 'Default affiliate', AT_TEXTDOMAIN ); ?></option>
					<?php foreach ($affiliates as $key => $value) { ?>
						<option <?php echo (!empty($car_info['options']['_affiliate_id']) && $car_info['options']['_affiliate_id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<?php } ?>
		</div>
		<div class="col_2 col_two_third col_last">
			<div id="map-item-location"></div>

		</div>
		<div class="clear"></div>
		<h2></h2>
		<div class="btn-next">
			<a href="#" class="btn3"><?php echo __( 'Back', AT_TEXTDOMAIN );?></a>
			<a href="#" class="btn1"><?php echo __( 'Next', AT_TEXTDOMAIN );?></a>
		</div>
	</div>
	<div class="sell_box step_4" style="display:none;">
		<h2><?php echo __( '<strong>Vehicle</strong> photos', AT_TEXTDOMAIN );?></h2>
		<script type = "text/template" id="empty_foto_wrapper">
			<div class="foto_wrapper item {is_main}" >
				<span>
					<img src="{src}" alt=""/>
				</span>
				<div class="actions">
					<a href="#" data-id="0" class="icon set_main_image" title="<?php echo __( 'Set main', AT_TEXTDOMAIN );?>"><i class="icon-up-dir"></i></a>
					<a href="#" data-id="0" class="icon delete_image" title="<?php echo __( 'Delete', AT_TEXTDOMAIN );?>"><i class="icon-cancel"></i></a>
					<input type='hidden' name='photos[{file_name}]' value='{photo_value}'>
				</div>
			</div>
		</script>
		<?php if ( !empty($photos) ) {?>
			<ul class="photos_sortable">
			<?php foreach ($photos as $key => $photo) {?>
				<li class="foto_wrapper item <?php if($photo['is_main']) echo 'photo_main'; ?>" >
					<span>
						<img src="<?php echo AT_Common::static_url( $photo['photo_url'] . '165x120/' . $photo['photo_name'] ); ?>" alt=""/>
					</span>
					<div class="actions">
						<a href="#" data-id="<?php echo $photo['id']; ?>" class="icon set_main_image" title="<?php echo __( 'Set main', AT_TEXTDOMAIN );?>"><i class="icon-up-dir"></i></a>
						<a href="#" data-id="<?php echo $photo['id']; ?>" class="icon delete_image" title="<?php echo __( 'Delete', AT_TEXTDOMAIN );?>"><i class="icon-cancel"></i></a>
						<input type='hidden' name='photos[<?php echo $photo['id'];?>]' value='<?php echo $photo['is_main'] ? '1' : '0'; ?>'>
					</div>
				</li>
			<?php } ?>
			</ul>
		<?php } ?>
		<div id="loaded-images"></div>
		<div class="foto_wrapper" id="container">
			<span id="upload-photos">
				<img src="<?php echo AT_Common::static_url('/assets/images/upload.png');?>" alt="" class="upload"/>
				<?php echo __( 'upload photo', AT_TEXTDOMAIN );?>
			</span>
		</div>
		<div class="clear"></div>
		<h2></h2>
		<div class="btn-next">
			<a href="#" class="btn3"><?php echo __( 'Back', AT_TEXTDOMAIN );?></a>
			<?php if (empty($car_info)) { ?>
			<a href="#" class="btn1"><?php echo __( 'Add', AT_TEXTDOMAIN );?></a>
			<?php } else { ?>
			<a href="#" class="btn1"><?php echo __( 'Save', AT_TEXTDOMAIN );?></a>
			<?php } ?>
			<span class="form_loading"><img src="<?php echo AT_Common::static_url('assets/images/loading.gif'); ?>" /></span>
		</div>
	</div>
	</form>
</div>