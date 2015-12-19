jQuery(function() {
	//var searchFields = jQuery('.catalog_sidebar .search_auto select, form .search_auto select, .catalog_sidebar .search_auto  input[type="number"], form .search_auto input[type="number"], #cars_categories');
	if( jQuery("select").is("#color_id") ) {
		jQuery('#color_id').selectik({
			 	containerClass: 'custom-select color-select',
				width: 'auto',
				maxItems: 5,
				customScroll: 1,
				speedAnimation: 100,
				smartPosition: false
			});
		setTimeout(function() { 
			jQuery('#color_id').change();
		}, 5);
		jQuery('#color_id').change(function(){
			jQuery('.color-select .custom-text').css({'color': '#ffffff'});
			setTimeout(function() { 
				var value = jQuery('#color_id option[value=' + jQuery('#color_id').val() + ']').data('selectik');
				if(value != 'undefined') jQuery('.color-select .custom-text').html(value);
				jQuery('.color-select .custom-text').css({'color': '#607586'});
			}, 5);
		});
	}

	jQuery('.cars_categories a').click(function(e){
		e.preventDefault();
		jQuery('.cars_categories a').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('#cars_categories').val( jQuery(this).data('id') );
		jQuery('#search_car').click();
	});

	jQuery('.type_view a').click(function(e){
		e.preventDefault();
		jQuery('.type_view a').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('#type_view').val( jQuery(this).data('id') );
		jQuery('#search_car').click();
	});

	var get_transport_type = false;
	jQuery('input.transport_type').click(function(e){
		var tid = jQuery(this).attr('value');
		// if (!get_transport_type) {
			get_transport_type = true;
			jQuery.ajax({
	            url: site_url('/catalog/ajax_get_manufacturer_by_type/' + tid + '/'),
	            success: function(data) {
	                jQuery('#manufacturer_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
	                if(data.length > 0) {
		                jQuery.each(data, function(i, item) {
				           jQuery('#manufacturer_id').append(jQuery('<option></option>').attr('value', item.alias).text(item.name));
				        });
				    }
				    //jQuery('#model_id').data('selectik').refreshCS();
	                // get_transport_type = false;
	            }
	        });
	    // }
        //return false;

	});

	var get_model = false;
	jQuery('#manufacturer_id').change(function(e){
		var alias = jQuery('#manufacturer_id').val();
		if (!get_model) {
			get_model = true;
			jQuery.ajax({
	            url: site_url('/catalog/ajax_get_models/' + alias + '/'),
	            success: function(data) {
	                jQuery('#model_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
	                if(data.length > 0) {
		                jQuery.each(data, function(i, item) {
				           jQuery('#model_id').append(jQuery('<option></option>').attr('value', item.alias).text(item.name));
				        });
				    }
				    //jQuery('#model_id').data('selectik').refreshCS();
	                get_model = false;
	            }
	        });
	    }
        return false;
	});

	var get_state = false;
	jQuery('#region_id').change(function(e){
		var id = jQuery('#region_id').val();
		if (!get_state) {
			get_state = true;
			jQuery.ajax({
	            url: site_url('/catalog/ajax_get_states/' + id + '/'),
	            success: function(data) {
	                jQuery('#state_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
	                if(data.length > 0) {
		                jQuery.each(data, function(i, item) {
				           jQuery('#state_id').append(jQuery('<option></option>').attr('value', item.id).text(item.name));
				        });
				    }
				    //jQuery('#model_id').data('selectik').refreshCS();
	                get_state = false;
	            }
	        });
	    }
        return false;
	});
	//Change inappropriate values
	jQuery('select#fabrication_from').change(function(e){
		if (jQuery('#fabrication_to').val() != 0 && jQuery('#fabrication_from').val() > jQuery('#fabrication_to').val()) {
			jQuery("#fabrication_to [value='"+jQuery('#fabrication_from').val()+"']").attr("selected", "selected");
		}
	});
	jQuery('select#fabrication_to').change(function(e){
		if (jQuery('#fabrication_from').val() > jQuery('#fabrication_to').val()) {
			setTimeout(function(){
				jQuery("#fabrication_to [value='"+jQuery('#fabrication_from').val()+"']").attr("selected", "selected");
			}, 500);
		}
	});
	jQuery('select#engine_from').change(function(e){
		if (jQuery('#engine_to').val() != 0 && jQuery('#engine_from').val() > jQuery('#engine_to').val()) {
			jQuery('#engine_to').val( jQuery('#engine_from').val() );
		}
	});
	jQuery('select#engine_to').change(function(e){
		if (jQuery('#engine_from').val() > jQuery('#engine_to').val()) {
			setTimeout(function(){
				jQuery('#engine_to').val( jQuery('#engine_from').val() );
			}, 500);
		}
	});
	// jQuery('select#mileage_from').change(function(e){
	// 	if (jQuery('#mileage_to').val() != 0 && jQuery('#mileage_from').val() > jQuery('#mileage_to').val()) {
	// 		jQuery('#mileage_to').val( jQuery('#mileage_from').val() );
	// 	}
	// });
	// jQuery('select#mileage_to').change(function(e){
	// 	if (jQuery('#mileage_from').val() > jQuery('#mileage_to').val()) {
	// 		setTimeout(function(){
	// 			jQuery('#mileage_to').val( jQuery('#mileage_from').val() );
	// 		}, 500);
	// 	}
	// });


	jQuery('#search_car').click(function(){
		var params = [];
		var url = '/';
		if (typeof(jQuery('#type_view').val()) != 'undefined')
			params.push('view=' + jQuery('#type_view').val());
		
		if (typeof(jQuery('#manufacturer_id').val()) != 'undefined' && jQuery('#manufacturer_id').val() != 0){
			//params.push('manufacturer_id=' + jQuery('#manufacturer_id').val());
			url += jQuery('#manufacturer_id').val() + '/';
		}
		
		if (typeof(jQuery('#model_id').val()) != 'undefined' && jQuery('#model_id').val() != 0 && url != '/'){
			//params.push('model_id=' + jQuery('#model_id').val());
			url += jQuery('#model_id').val() + '/';
		}

		if (typeof(jQuery('#price_from').val()) != 'undefined')
			params.push('price_from=' + jQuery('#price_from').val());

		if (typeof(jQuery('#price_to').val()) != 'undefined')
			params.push('price_to=' + jQuery('#price_to').val());

		if (typeof(jQuery('#mileage_from').val()) != 'undefined')
			params.push('mileage_from=' + jQuery('#mileage_from').val());

		if (typeof(jQuery('#mileage_to').val()) != 'undefined')
			params.push('mileage_to=' + jQuery('#mileage_to').val());

		if (typeof(jQuery('#fabrication_from').val()) != 'undefined')
			params.push('fabrication_from=' + jQuery('#fabrication_from').val());

		if (typeof(jQuery('#fabrication_to').val()) != 'undefined')
			params.push('fabrication_to=' + jQuery('#fabrication_to').val());

		if (typeof(jQuery('#view_on_page').val()) != 'undefined')
			params.push('view_on_page=' + jQuery('#view_on_page').val());

		if (typeof(jQuery('#sorted_field').val()) != 'undefined')
			params.push('sorted_field=' + jQuery('#sorted_field').val());

		if (typeof(jQuery('#sorted_direction').val()) != 'undefined')
			params.push('sorted_direction=' + jQuery('#sorted_direction').val());

		if (typeof(jQuery('#cars_categories').val()) != 'undefined')
			params.push('cars_categories=' + jQuery('#cars_categories').val());

		if (typeof(jQuery('#body_type_id').val()) != 'undefined')
			params.push('body_type_id=' + jQuery('#body_type_id').val());

		if (typeof(jQuery('input[name=transport_type_id]:checked').val()) != 'undefined')
			params.push('transport_type_id=' + jQuery('input[name=transport_type_id]:checked').val());

		if (typeof(jQuery('#fuel_id').val()) != 'undefined')
			params.push('fuel_id=' + jQuery('#fuel_id').val());

		if (typeof(jQuery('#engine_from').val()) != 'undefined')
			params.push('engine_from=' + jQuery('#engine_from').val());

		if (typeof(jQuery('#engine_to').val()) != 'undefined')
			params.push('engine_to=' + jQuery('#engine_to').val());

		if (typeof(jQuery('#transmission_id').val()) != 'undefined')
			params.push('transmission_id=' + jQuery('#transmission_id').val());

		if (typeof(jQuery('#door_id').val()) != 'undefined')
			params.push('door_id=' + jQuery('#door_id').val());

		if (typeof(jQuery('#region_id').val()) != 'undefined')
			params.push('region_id=' + jQuery('#region_id').val());

		if (typeof(jQuery('#state_id').val()) != 'undefined')
			params.push('state_id=' + jQuery('#state_id').val());

		if (typeof(jQuery('#drive_id').val()) != 'undefined')
			params.push('drive_id=' + jQuery('#drive_id').val());

		if (typeof(jQuery('#color_id').val()) != 'undefined')
			params.push('color_id=' + jQuery('#color_id').val());

		// console.log(site_url('/catalog' + url) + '?' + params.join("&"));
		// return;
		location.href = site_url('/catalog' + url) + '?' + params.join("&");
	});
	jQuery('.view_on_page.drop_list .selected ul li').click(function(e){
		e.preventDefault();
		jQuery(this).closest('.selected').removeClass('active');
		jQuery('#' + jQuery(this).closest('.drop_list').data('id')).val(jQuery(this).find('a').data('value'));
		jQuery(this).closest('.selected').find('span').find('a').html(jQuery(this).find('a').html());

		jQuery('#search_car').click();
	});
	jQuery('.sorting.drop_list .selected ul li').click(function(e){
		e.preventDefault();
		jQuery(this).closest('.selected').removeClass('active');
		jQuery('#sorted_field').val(jQuery(this).find('a').data('field'));
		jQuery('#sorted_direction').val(jQuery(this).find('a').data('direction'));
		jQuery(this).closest('.selected').find('span').find('a').html(jQuery(this).find('a').html());

		jQuery('#search_car').click();
	});

	jQuery('#search_car_shortcode').click(function(e){
		e.preventDefault();
		var params = [];
		var url = '/';
		if (typeof(jQuery('#type_view').val()) != 'undefined')
			params.push('view=' + jQuery('#type_view').val());
		
		if (typeof(jQuery('#manufacturer_id').val()) != 'undefined' && jQuery('#manufacturer_id').val() != 0){
			//params.push('manufacturer_id=' + jQuery('#manufacturer_id').val());
			url += jQuery('#manufacturer_id').val() + '/';
		}
		
		if (typeof(jQuery('#model_id').val()) != 'undefined' && jQuery('#model_id').val() != 0 && url != '/'){
			//params.push('model_id=' + jQuery('#model_id').val());
			url += jQuery('#model_id').val() + '/';
		}

		if (typeof(jQuery('#price_from').val()) != 'undefined')
			params.push('price_from=' + jQuery('#price_from').val());

		if (typeof(jQuery('#price_to').val()) != 'undefined')
			params.push('price_to=' + jQuery('#price_to').val());

		if (typeof(jQuery('#mileage_from').val()) != 'undefined')
			params.push('mileage_from=' + jQuery('#mileage_from').val());

		if (typeof(jQuery('#mileage_to').val()) != 'undefined')
			params.push('mileage_to=' + jQuery('#mileage_to').val());

		if (typeof(jQuery('#fabrication_from').val()) != 'undefined')
			params.push('fabrication_from=' + jQuery('#fabrication_from').val());

		if (typeof(jQuery('#fabrication_to').val()) != 'undefined')
			params.push('fabrication_to=' + jQuery('#fabrication_to').val());

		if (typeof(jQuery('#view_on_page').val()) != 'undefined')
			params.push('view_on_page=' + jQuery('#view_on_page').val());

		if (typeof(jQuery('#sorted_field').val()) != 'undefined')
			params.push('sorted_field=' + jQuery('#sorted_field').val());

		if (typeof(jQuery('#sorted_direction').val()) != 'undefined')
			params.push('sorted_direction=' + jQuery('#sorted_direction').val());

		if (typeof(jQuery('#cars_categories:checked').val()) != 'undefined'){
			params.push('cars_categories=' + jQuery('#cars_categories').val());
		} else {
			params.push('cars_categories=all');
		}

		if (typeof(jQuery('#body_type_id').val()) != 'undefined')
			params.push('body_type_id=' + jQuery('#body_type_id').val());

		if (typeof(jQuery('input[name=transport_type_id]:checked').val()) != 'undefined')
			params.push('transport_type_id=' + jQuery('input[name=transport_type_id]:checked').val());

		if (typeof(jQuery('#fuel_id').val()) != 'undefined')
			params.push('fuel_id=' + jQuery('#fuel_id').val());

		if (typeof(jQuery('#engine_from').val()) != 'undefined')
			params.push('engine_from=' + jQuery('#engine_from').val());

		if (typeof(jQuery('#engine_to').val()) != 'undefined')
			params.push('engine_to=' + jQuery('#engine_to').val());

		if (typeof(jQuery('#transmission_id').val()) != 'undefined')
			params.push('transmission_id=' + jQuery('#transmission_id').val());

		if (typeof(jQuery('#door_id').val()) != 'undefined')
			params.push('door_id=' + jQuery('#door_id').val());

		if (typeof(jQuery('#region_id').val()) != 'undefined')
			params.push('region_id=' + jQuery('#region_id').val());

		if (typeof(jQuery('#state_id').val()) != 'undefined')
			params.push('state_id=' + jQuery('#state_id').val());

		if (typeof(jQuery('#drive_id').val()) != 'undefined')
			params.push('drive_id=' + jQuery('#drive_id').val());

		if (typeof(jQuery('#color_id').val()) != 'undefined')
			params.push('color_id=' + jQuery('#color_id').val());

		// console.log(site_url('/catalog' + url) + '?' + params.join("&"));
		// return;
		location.href = site_url('/catalog' + url) + '?' + params.join("&");
	});
});