(function($){
    $.nano = function(template, data) {
        return template.replace(/\{([\w\.]*)\}/g, function (str, key) {
            var keys = key.split("."), value = data[keys.shift()];
            $.each(keys, function () { value = value[this]; });
            return (value === null || value === undefined) ? "" : value;
        });
    };
})(jQuery);
jQuery(function() {

	var vehicle_address;
	var region = jQuery('#_region_id option:selected').text();
	var state = jQuery('#_state_id option:selected').text();

	vehicle_address = state + ' ' + region;


	var render_item_location = function() {
		if ( jQuery('#_region_id').val() != 0 ) {
			jQuery('#map-item-location').gmap3({
			 map: {
			    options: {
					maxZoom: 14 
			    }  
			 },
			 marker:{
			    address: vehicle_address
			 }
			},
			"autofit" );
		}
	}
	jQuery('#_color_id').selectik({
		 	containerClass: 'custom-select color-select',
		// 	width: 205,
			width: 'auto',
			maxItems: 5,
			customScroll: 1,
			speedAnimation: 100,
			smartPosition: false
		}
	);
	setTimeout(function() { 
		jQuery('#_color_id').change();
	}, 5);
	jQuery('#_color_id').change(function(){
		jQuery('.color-select .custom-text').css({'color': '#ffffff'});
		setTimeout(function() { 
			var value = jQuery('#_color_id option[value=' + jQuery('#_color_id').val() + ']').data('selectik');
			if(value != 'undefined') jQuery('.color-select .custom-text').html(value);
			jQuery('.color-select .custom-text').css({'color': '#607586'});
		}, 5);
	});
	///////////////////////////////////////////////////
	// Form CAR
	//////////////////////////////////////////////////
	var options_ajax = {
		beforeSubmit:	function (data) {
			return jQuery("#vehicle-form").valid();
		},
		afterSubmit:	function (data) {
			alert('submit');
		},		
		success: function (data)  {  
			//console.log(data);
			 if (data.status == 'ERROR') {
			 	//alert(data.message);
			 	// if (typeof(data['notice']) == 'undefined') {
			 	// 	validator.showErrors(data.message);
			 	// } else {
			 	// 	alert(data.notice);
			 	// }
			} 
			if (data.status == 'OK') {
			 	window.location = data.redirect_url;
			}    
		},      
		dataType:  'json'       
	};
	var validate_config = {rules :{}};
	jQuery.validator.setDefaults({ignore: []});
    jQuery.validator.addMethod("value-invalid", function(value, element, arg){
	  return arg != value;
	}, "This field is required.");
	jQuery("#vehicle-form").validate(validate_config);

	jQuery("#step_1").click(function(e) {
		e.preventDefault();
		jQuery('#step_2,#step_3').removeClass('active');
		jQuery('.step_2,.step_3,.step_4').hide();
		jQuery('.step_1').show();
	});
	jQuery("#step_2").click(function(e) {
		e.preventDefault();
		jQuery('#step_2').addClass('active');
		jQuery('#step_3,#step_4').removeClass('active');
		jQuery('.step_1,.step_3,.step_4').hide();
		jQuery('.step_2').show();
	});
	jQuery("#step_3").click(function(e) {
		e.preventDefault();
		render_item_location();
		jQuery('#step_3,#step_2').addClass('active');
		jQuery('.step_1,.step_2,.step_4').hide();
		jQuery('.step_3').show();
		jQuery('#step_4').removeClass('active');
		uploader.refresh();
	});
	jQuery("#step_4").click(function(e) {
		e.preventDefault();
		jQuery('#step_4,#step_3,#step_2').addClass('active');
		jQuery('.step_1,.step_2,.step_3').hide();
		jQuery('.step_4').show();
		uploader.refresh();
	});
	jQuery(".step_2 .btn3").click(function(e) {
		e.preventDefault();
		jQuery("#step_1").click();
	});
	jQuery(".step_4 .btn3").click(function(e) {
		e.preventDefault();
		jQuery("#step_3").click();
	});
	jQuery(".step_3 .btn3").click(function(e) {
		e.preventDefault();
		jQuery("#step_2").click();
	});
	jQuery(".step_1 .btn1,.step_3 .btn3").click(function(e) {
		e.preventDefault();
		jQuery("#step_2").click();
	});
	jQuery(".step_2 .btn1,.step_4 .btn3").click(function(e) {
		e.preventDefault();
		jQuery("#step_3").click();
	});
	jQuery(".step_3 .btn1").click(function(e) {
		e.preventDefault();
		jQuery("#step_4").click();
	});
	jQuery(".step_4 .btn1").click(function(e) {
		e.preventDefault();
		if (!jQuery("#vehicle-form").valid()) {
			alert( 'Enter required fields!' );
		} else {
			jQuery('.form_loading').css({'display': 'inline-block'});
			jQuery('#vehicle-form').ajaxForm(options_ajax).submit();
		}
	});


	var get_model = false;
	jQuery('#_manufacturer_id').change(function(e){
		var id = jQuery('#_manufacturer_id').val();
		if ( id > 0 && !get_model) {
			get_model = true;
			jQuery.ajax({
	            url: site_url('/catalog/ajax_get_models/' + id + '/'),
	            success: function(data) {
	            	console.log(data);
	                jQuery('#_model_id').html(jQuery('<option></option>').attr('value', 0).text('Select'));
	                if(data.length > 0) {
	                	jQuery('#_model_alter.active').removeClass('active').attr('value','');
		                jQuery.each(data, function(i, item) {
				           jQuery('#_model_id').append(jQuery('<option></option>').attr('value', item.id).text(item.name));
				        });
				    } else {
				    	jQuery('#_model_id').html('');
				    	jQuery('#_model_alter').addClass('active');
				    }
	                get_model = false;
	            },
	            failed: function(e) {
	            	console.log(e);
	            }
	        });
	    }
        return false;
	});
	// jQuery('#_manufacturer_id').change(function(e){
	// 	// _custom_model_name
	// });

	// var get_transport_type = false;
	// jQuery('#_transport_type_id').change(function(e){
	// 	var tid = jQuery(this).attr('value');
	// 	if (!get_transport_type) {
	// 		get_transport_type = true;
	// 		jQuery.ajax({
	//             url: site_url('/catalog/ajax_get_manufacturer_by_type/' + tid + '/'),
	//             success: function(data) {
	//                 jQuery('#_manufacturer_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
	//                 jQuery('#_model_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
	//                 if(data.length > 0) {
	// 	                jQuery.each(data, function(i, item) {
	// 			           jQuery('#_manufacturer_id').append(jQuery('<option></option>').attr('value', item.alias).text(item.name));
	// 			        });
	// 			    }
	// 		        // manufacturer_switch();
	// 			    //jQuery('#model_id').data('selectik').refreshCS();
	//                 get_transport_type = false;
	//             }
	//         });
	//     }
	// });


	jQuery('#_state_id').change(function(e){

		vehicle_address = jQuery('#_state_id option:selected').text() + ', ' + jQuery('#_region_id option:selected').text();;

		jQuery('#map-item-location').gmap3({
			clear: {
				name:["marker"],
				last: true
			}
		});

		render_item_location();

		return false;
	});

    var get_state = false;
    jQuery('#_region_id').change(function(e) {
        var id = jQuery(this).val();
        if (id > 0 && !get_state) {
            get_state = true;
            jQuery.ajax({
	            url: site_url('/catalog/ajax_get_states/' + id + '/'),
                success: function(data) {
                    jQuery('#_state_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
                    if (data.length > 0) {
                        jQuery.each(data, function(i, item) {
                            jQuery('#_state_id').append(jQuery('<option></option>').attr('value', item.id).text(item.name));
                        });
                    }
                    get_state = false;
                }
            });
        } else if (id == 0) {
            jQuery('#_state_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
        }

		vehicle_address = jQuery('#_region_id option:selected').text();

		jQuery('#map-item-location').gmap3({
			clear: {
				name:["marker"],
				last: true
			}
		});

		render_item_location();

        return false;
    });

	jQuery('.photos_sortable').sortable({
        opacity: 0.6,
        // 'handle': '.item-title'
    });

	car_limit_photos = parseInt(car_limit_photos);
	if ( car_limit_photos == 0 ) car_limit_photos = 10000;
	var maxfiles = car_limit_photos - (jQuery('.foto_wrapper').length - 1 );
    var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button : 'upload-photos',
		max_file_count: maxfiles,
		container: document.getElementById('container'),
		url : site_url( '/profile/vehicles/upload/' ),
		flash_swf_url : './plupload/Moxie.swf',
		silverlight_xap_url : './plupload/js/Moxie.xap',
		resize : {width : 1000, height : 1000, quality : 90},
		filters : {
			max_file_size : '10mb',
			mime_types: [
				{title : "Image files", extensions : "jpg,jpeg,gif,png"}
			]
		},

		init: {
			PostInit: function() {
			},
			FilesAdded: function(up, files) {
				plupload.each(files, function(file) {
					if (up.files.length > maxfiles) {
	                    up.removeFile(file);
	                    jQuery('#upload-photos').hide();
	                }
				});
				uploader.start();
				if (up.files.length == maxfiles) {
					jQuery('#upload-photos').hide();
				}
			},
			Error: function(up, err) {
				console.log( err.code + ": " + err.message );
			},
			FileUploaded: function(up, file, response) {
				response = JSON.parse(response.response);
				if(response.status == 'OK'){
					//document.getElementById('loaded-images').innerHTML += "<div class=\"foto_wrapper\"><input type='hidden' name='photos[]' value='" + response.file_name + "'><span><img src=\"" + response.file_name_url + "\" style=\"max-width:138px;height:103px;\" /></span></div>";
					var data = {'src' : response.file_name_url, 'file_name' : response.file_name, 'is_main' : '', 'photo_value' : '0'};
					if( jQuery(".step_4 .foto_wrapper.item").length == 0 ) {
						data.is_main = 'photo_main';
						data.photo_value = '1';
					}
 					document.getElementById('loaded-images').innerHTML +=  jQuery.nano(jQuery('#empty_foto_wrapper').html(), data);
				} else {
					console.log( response.code + ": " + response.message );
				}
			}
		}
	});
	uploader.init();
	jQuery(".step_4").on("click", ".icon.set_main_image", function(e){
		e.preventDefault();
		jQuery(".step_4 .foto_wrapper.photo_main.item").removeClass('photo_main').find('.actions').find('input').val('0');
		jQuery(this).closest(".actions").find('input').val('1').closest(".foto_wrapper.item").addClass('photo_main');
	});
	jQuery(".step_4").on("click", ".icon.delete_image", function(e){
		e.preventDefault();
		jQuery(this).closest(".foto_wrapper").removeClass('item');
		jQuery(this).closest(".foto_wrapper").hide('slow', function(){
		    jQuery(this).remove();
		});
		if( jQuery(this).closest(".foto_wrapper").hasClass('photo_main') ) {
			jQuery(".step_4 .foto_wrapper.item:first").find(".icon.set_main_image").click();
		}
	});
});

