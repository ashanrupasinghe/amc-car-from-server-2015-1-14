<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $this->add_script( 'jquery.selectik', 'assets/js/jquery/jquery.selectik.js'); ?>
<?php $this->add_script( 'catalog', 'assets/js/catalog.js', array( 'jquery' )); ?>
<div class="layout_<?php echo $layout; ?>">
	<?php if ( $this->get_option( 'catalog_car_categories_disable', false ) == false ) { ?>
	<div class="cars_categories">
		<input type="hidden" id="cars_categories" name="cars_categories" value="<?php echo $params['cars_categories'];?>" />
		<a href="#" class="<?php echo ($params['cars_categories'] === '' || $params['cars_categories'] === 'all' ? 'active' : '');?>" data-id="all"><span><?php echo __( 'All cars', AT_TEXTDOMAIN ); ?></span></a>
		<a href="#" class="<?php echo ($params['cars_categories'] === 'new' ? 'active' : '');?>" data-id="new"><span><?php echo __( 'new cars', AT_TEXTDOMAIN ); ?></span></a>
		<a href="#" class="<?php echo ($params['cars_categories'] === 'used' ? 'active' : '');?>" data-id="used"><span><?php echo __( 'used cars', AT_TEXTDOMAIN ); ?></span></a>
	</div>
	<?php } ?>
	<h1><strong><?php echo __( 'Cars', AT_TEXTDOMAIN ); ?></strong> (<?php echo $count_cars; ?> <?php echo __( 'results', AT_TEXTDOMAIN ); ?>)</h1>
<?php foreach( $layout_items as $key=>$view_item ) {?>
  <?php switch ($view_item) {
	case 'sidebar': ?>
		<div class="catalog_sidebar">
			<div class="search_auto">
				<?php $catalog_search_form = $this->get_option( 'catalog_search_form', array() ); ?>
				<?php foreach ($catalog_search_form as $key => $value) { 
					  	switch ( $key ) { 
				  			case 'title': ?>
				  				<h3><?php echo __( '<strong>Search</strong> auto', AT_TEXTDOMAIN ) ?></h3>
				  	<?php break;
				  			case 'transport_type': ?>
								<div class="categories">
									<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_transport_types' ), true ) as $key => $value) { ?>
									<input class="transport_type" type="radio" id="search_radio_<?php echo $value['id']; ?>" value="<?php echo $value['id']; ?>" name="transport_type_id" <?php if( $params['transport_type_id'] == $value['id'] ) echo 'checked="checked"'; ?> />
									<label for="search_radio_<?php echo $value['id']; ?>" title="<?php echo $value['name']; ?>"><i class="<?php echo $value['alias']; ?>"></i></label>
									<?php } ?>
								</div>
								<div class="clear"></div>
					<?php break; 
							case 'manufacturer_model': ?>
								<label><strong><?php echo __( 'Make:', AT_TEXTDOMAIN ) ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3 select2" id="manufacturer_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php //foreach ($manufacturers as $key => $value) { ?>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_manufacturers' ), true ) as $key => $value) { ?>
										<option <?php if( $params['manufacturer_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['alias']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
								<label><strong><?php echo __( 'Model:', AT_TEXTDOMAIN ); ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3 select2" id="model_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_models_by_manufacturer_id', 'params_arr' => array( $params['manufacturer_id'] ) ), true ) as $key => $value) { ?>
										<option <?php if( $params['model_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['alias']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
					<?php break;
							case 'year': ?>
								<label><strong><?php echo __( 'Year:', AT_TEXTDOMAIN ); ?></strong></label>
								<div class="select_box_2">
									<select class="custom-select select_4 select2" name="fabrication_from" id="fabrication_from">
										<option value="0"><?php echo __( 'From', AT_TEXTDOMAIN ); ?></option>
										<?php for($i = date('Y'); $i >= 1912; $i--) { ?>
											<option <?php if( $params['fabrication_from'] == $i ) echo 'selected="selected"'; ?>  value="<?php echo $i; ?>"><?php echo $i; ?></option>
										<?php } ?>
									</select>
									<select class="custom-select select_4 select2" name="fabrication_to" id="fabrication_to">
										<option value="0"><?php echo __( 'To', AT_TEXTDOMAIN ); ?></option>
										<?php for($i = date('Y'); $i >= 1912; $i--) { ?>
											<option <?php if( $params['fabrication_to'] == $i ) echo 'selected="selected"'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
										<?php } ?>
									</select>
									<div class="clear"></div>
								</div>
					<?php break;
							case 'price': ?>
								<label><strong><?php echo __( 'Price:', AT_TEXTDOMAIN ); ?></strong></label>
								<div class="select_box_2">
									<input type="text" name="price_from" id="price_from" value="<?php echo $params['price_from']; ?>" class="txb"/>
									<input type="text" name="price_to" id="price_to" value="<?php echo $params['price_to']; ?>" class="txb"/>
									<div class="clear"></div>
								</div>
					<?php break; 
							case 'mileage': ?>
								<label><strong><?php echo __( 'Mileage, ' . AT_Common::car_mileage(1) . ':', AT_TEXTDOMAIN ); ?></strong></label>
								<div class="select_box_2">
									<input type="text" name="mileage_from" placeholder="<?php echo __( 'From', AT_TEXTDOMAIN ); ?>" id="mileage_from"  value="<?php echo $params['mileage_from']; ?>" class="txb"/>
									<input type="text" name="mileage_to" placeholder="<?php echo __( 'To', AT_TEXTDOMAIN ); ?>" id="mileage_to" value="<?php echo $params['mileage_to']; ?>" class="txb"/>
									<div class="clear"></div>
								</div>
					<?php break; 
							case 'body_type': ?>
								<label><strong><?php echo __( 'Body type:', AT_TEXTDOMAIN ) ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3" id="body_type_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_body_types' ), true ) as $key => $value) { ?>
										<option <?php if( $params['body_type_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
					<?php break;
							case 'fuel': ?>
								<label><strong><?php echo __( 'Fuel:', AT_TEXTDOMAIN ) ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3" id="fuel_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_fuels' ), true ) as $key => $value) { ?>
										<option <?php if( $params['fuel_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
					<?php break;
							case 'transmission': ?>
								<label><strong><?php echo __( 'Transmission:', AT_TEXTDOMAIN ) ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3" id="transmission_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_transmissions' ), true ) as $key => $value) { ?>
										<option <?php if( $params['transmission_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
					<?php break;
							case 'doors': ?>
								<label><strong><?php echo __( 'Doors:', AT_TEXTDOMAIN ) ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3" id="door_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_doors' ), true ) as $key => $value) { ?>
										<option <?php if( $params['door_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
					<?php break;
							case 'region': ?>
								<label><strong><?php echo __( 'Region:', AT_TEXTDOMAIN ) ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3" id="region_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_regions' ), true ) as $key => $value) { ?>
										<option <?php if( $params['region_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
					<?php break;
							case 'region_state': ?>
										<div class="clear"></div>
										<label><strong><?php _e( "Country", AT_TEXTDOMAIN ); ?>:</strong></label>
										<div class="select_box_1">
											<select name="region_id" id="region_id" class="custom-select select_1 select2">';
												<option value="0"><?php _e( "Any", AT_TEXTDOMAIN );?></option>';
												<?php
												foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_regions' ), true ) as $key => $value) {
													$selected = '';
													if( $params['region_id'] == $value['id'] ) $selected = ' selected="selected" ';
													echo '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['name'] . '</option>';
												}
												?>
											</select>
										</div>
										<label><strong><?php _e( "State", AT_TEXTDOMAIN );?>:</strong></label>
										<div class="select_box_1">
											<select name="state_id" id="state_id" class="custom-select select_1 select2">
												<option value="0"><?php _e( "Any", AT_TEXTDOMAIN );?></option>
											</select>
										</div>
						<?php break;
							case 'drive': ?>
								<label><strong><?php echo __( 'Drive:', AT_TEXTDOMAIN ) ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3" id="drive_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_drive' ), true ) as $key => $value) { ?>
										<option <?php if( $params['drive_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
					<?php break;
							case 'color': ?>
								<label><strong><?php echo __( 'Color:', AT_TEXTDOMAIN ) ?></strong></label>
								<div class="select_box_1">
									<select class="custom-select select_3" id="color_id">
										<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN ); ?></option>
										<?php foreach ($this->add_widget( 'reference_widget', array( 'method' => 'get_colors' ), true ) as $key => $value) { ?>
										<!--option <?php if( $params['color_id'] == $value['id'] ) echo 'selected="selected"'; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option-->
										<option data-selectik="<span style='background-color:<?php echo $value['alias']; ?>'></span><?php echo $value['name']; ?>" data-color="<?php echo $value['alias']; ?>" <?php echo ($params['color_id'] == $value['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php } ?>
									</select>
								</div>
					<?php break;
							case 'cilindrics': ?>
								<label><strong><?php echo __( 'Engine, cm³:', AT_TEXTDOMAIN ); ?></strong></label>
								<div class="select_box_2">
									<select class="custom-select select_4" name="engine_from" id="engine_from">
										<option value="0"><?php echo __( 'From', AT_TEXTDOMAIN ); ?></option>
										<?php $car_engine_range = $this->get_option( 'car_engine_range', array( 'min' => 900, 'max' => 6500 ) );?>
										<?php for($i = $car_engine_range['min']; $i <= $car_engine_range['max']; $i=$i+100) { ?>
											<option <?php if( $params['engine_from'] == $i ) echo 'selected="selected"'; ?>  value="<?php echo $i; ?>"><?php echo $i; ?></option>
										<?php } ?>
									</select>
									<select class="custom-select select_4" name="engine_to" id="engine_to">
										<option value="0"><?php echo __( 'To', AT_TEXTDOMAIN ); ?></option>
										<?php $car_engine_range = $this->get_option( 'car_engine_range', array( 'min' => 900, 'max' => 6500 ) );?>
										<?php for($i = $car_engine_range['min']; $i <= $car_engine_range['max']; $i=$i+100) { ?>
											<option <?php if( $params['engine_to'] == $i ) echo 'selected="selected"'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
										<?php } ?>
									</select>
									<div class="clear"></div>
								</div>
					<?php break;
						case 'submit': ?>
						<input type="submit" value="<?php echo __( 'Search', AT_TEXTDOMAIN ); ?>" id="search_car" class="btn_search btn5"/>
					<?php break; 
					} 
				} ?>
				<div class="clear"></div>
			</div>
			<?php //echo $block['loan_calculator']; ?>
			<div><?php $this->add_widget( 'sidebar_widget', array( 'name' => 'Catalog' ) ); ?></div>
		</div>
	<?php break;
	case 'content': ?>
	<div class="main_catalog">
		<?php if ( $this->get_option( 'catalog_filters_disable', false ) == false ) { ?>
		<div class="top_catalog_box">
			<div class="switch button-group type_view">
				<input type="hidden" id="type_view" value="<?php echo $view; ?>"/>
				<a data-id="grid" href="#" class="table_view <?php echo ($view == 'grid' ? 'active' : ''); ?> start-btn"><i class="icon-grid"></i></a>
				<a data-id="list" href="#" class="list_view <?php echo ($view == 'list' ? 'active' : ''); ?> end-btn"><i class="icon-list"></i></a>
			</div>
			<?php if( count( $catalog_sorted_fields ) > 0 ) { ?>
			<div class="sorting drop_list" data-id="sorted_field">
				<strong><?php echo __( 'Sort by:', AT_TEXTDOMAIN ); ?> </strong>
				<input type="hidden" id="sorted_field" value="<?php echo $sorted_field;?>" />
				<input type="hidden" id="sorted_direction" value="<?php echo $sorted_direction;?>" />
				<div class="selected">
				<?php 
					$li = '';
					$sorted_name = __( 'Publish date:', AT_TEXTDOMAIN );
					foreach ( $catalog_sorted_fields as $key => $value ) { 
						if( ($value['field'] == $sorted_field) && ($value['direction'] == $sorted_direction) ) {
							$sorted_name = $value['name'];
						}
						$li .= '<li><a href="#" data-field="' . $value['field'] . '"  data-direction="' . $value['direction']. '">' . $value['name'] . '</a></li>';
					} 
				?>
					<span><a href="#"><?php echo $sorted_name;?></a></span>
					<ul><?php echo $li; ?></ul>
				</div>
				<div class="clear"></div>
			</div>
			<?php } else { ?>
				<input type="hidden" id="sorted_field" value="<?php echo $sorted_field;?>" />
				<input type="hidden" id="sorted_direction" value="<?php echo $sorted_direction;?>" />
			<?php } ?>
			<div class="view_on_page drop_list" data-id="view_on_page">
				<strong><?php echo __( 'View on page:', AT_TEXTDOMAIN ); ?></strong>
				<input type="hidden" id="view_on_page" value="<?php echo $view_on_page;?>" />
				<div class="selected">
					<span><a href="#"><?php echo $view_on_page;?></a></span>
					<ul>
						<?php 
							$catalog_per_pages = $this->get_option( 'catalog_per_pages', array( array( 'pages' => 12 ) ));
							foreach ($catalog_per_pages as $key => $value) {
						?>
							<li><a href="#" data-value="<?php echo $value['pages'] ?>"><?php echo $value['pages'] ?></a></li>
						<?php } ?>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<?php if ( $this->get_option( 'catalog_top_pagination_disable', false ) == false ) { ?>
				<?php echo $block['pagination']; ?>	
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<?php echo $block['cars']; ?>

		<?php if ( $this->get_option( 'catalog_bottom_pagination_disable', false ) == false ) { ?>
		<div class="bottom_catalog_box">
			<?php echo $block['pagination']; ?>
			<div class="clear"></div>
		</div>
		<?php } ?>
	</div>
	<?php break; ?>
  <?php }?>
<?php }?>
	<div class="clear"></div>
</div>