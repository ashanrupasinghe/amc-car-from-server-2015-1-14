jQuery(function() {
	jQuery('.item_delete').click(function(e){
		e.preventDefault();
		var item_id = jQuery(this).data('id');
		jQuery( "#dialog-confirm-delete" ).dialog({
			resizable: false,
			height:170,
			modal: true,
			buttons: {
				"Delete": function() {
					var tab = jQuery( this ).data('tab');
					jQuery( this ).dialog( "close" );
					jQuery('.theme_controls .spinner').show();
					jQuery.ajax({
		            	url: 'admin.php?page=at_reference',
						type: 'post',
						data: {'action': 'item_delete', 'tab' : tab , 'item_id': item_id },
			            success: function(data) {
			                if (data.status == 'OK'){
								jQuery('#item_' + item_id).remove();
			                } else {
			                	alert(data.message);
			                }
							jQuery('.theme_controls .spinner').hide();
			            },
			            dataType: "json"
			        });
				},
				"Cancel": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	});

	jQuery('.item_edit, .item_add').click(function(e){
		e.preventDefault();
		var action = 'get_edit_form';
		var item_id = jQuery(this).data('id');
		if ( typeof(item_id) == 'undefined' ) {
			item_id = 0;
			var action = 'get_add_form';
			manufacturer_id = jQuery('#select_manufacturer').val();
			region_id = jQuery('#select_country').val();
			if ( typeof( manufacturer_id ) != 'undefined' ) {
				if ( manufacturer_id == 0 ) {
					alert( 'Select manufacturer!' );
					return;
				} else {
					item_id = manufacturer_id;
				}
			}
			if ( typeof( region_id ) != 'undefined' ) {
				if ( region_id == 0 ) {
					alert( 'Select region/country!' );
					return;
				} else {
					item_id = region_id;
				}
			}
		}

		jQuery( "#dialog-form-reference" ).dialog({
			resizable: true,
			//height:140,
			modal: true,
			open: function( event, ui ) {
				var self = this;
				jQuery(this).html('<div class="spinner popup-modal">Loading form...</div>');
				jQuery.ajax({
	            	url: 'admin.php?page=at_reference',
					type: 'post',
					data: {'action': action, 'item_id': item_id, 'tab' : jQuery(this).data('tab') },
		            success: function(data) {
		                if (data.status == 'OK'){
		                	jQuery(self).html(data.message);
		                	if( jQuery(self).data('tab') == 'colors' ) {
			                	jQuery('#add-edit-reference #alias').ColorPicker({
									onSubmit: function(hsb, hex, rgb, el) {
										jQuery(el).val(hex);
										jQuery(el).ColorPickerHide();
									},
									onBeforeShow: function () {
										jQuery(this).ColorPickerSetColor(this.value);
									}
								})
								.bind('keyup', function(){
									jQuery(this).ColorPickerSetColor(this.value);
								});
							}
		                } else {
		                	alert(data.message);
		                }
		            },
		            dataType: "json"
		        });
			},
			buttons: {
				"Save": function() {
					var self = this;
					jQuery(this).find('.spinner').show();
					var form_data = { 'name': '', 'alias': '' };
					var tab = jQuery('#add-edit-reference').find('input[name=tab]').val();
					var ajax_options = {
						beforeSubmit:	function (data) {
							form_data.name = jQuery('#add-edit-reference').find('input[name=name]').val();
							if( tab == 'transport_types') {
								form_data.alias = jQuery('#add-edit-reference').find('select[name=alias]').val();
							} else {
								form_data.alias = jQuery('#add-edit-reference').find('input[name=alias]').val();
							}
							if ( tab == 'colors' ) {
								form_data.alias = '#' + form_data.alias;
							}
							return true;
						},
						success: function (data)  {
							if (data.status == 'OK'){
								jQuery( self ).dialog( "close" );
								if ( item_id == 0 || action == 'get_add_form' ) location.href=location.href;
								else {
									jQuery('#item_' + item_id).find('div[rel=name]').html( form_data.name );
									if( tab == 'transport_types') {
										jQuery('#item_' + item_id).find('div[rel=alias]').html( '<i class="'+form_data.alias+'"></i>' );
									} else {
										jQuery('#item_' + item_id).find('div[rel=alias]').html( form_data.alias );
									}
								}
			                } else {
			                	alert(data.message);
			                }
			                jQuery(this).find('.spinner').hide();
						},      
						dataType:  'json'
					};
					jQuery('#add-edit-reference').ajaxForm(ajax_options);
					jQuery('#add-edit-reference').submit();
				},
				"Cancel": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	});
	jQuery("body").on("keypress", "#add-edit-reference", function(e){
		if(e.keyCode == 13) return false;
	});


	jQuery('#sortable-table').sortable({
		items: "> div.theme-table-body-row",
		axis: 'y',
	    update: function (event, ui) {
	        var post = jQuery(this).sortable('serialize');
	        var tab = jQuery("#dialog-confirm-delete").data('tab');
	        post += '&action=save_sort&tab=' + tab;
	        jQuery.ajax({
	            data: post,
	            type: 'POST',
	            url: 'admin.php?page=at_reference&tab=transport_types'
	        });
	    }
	}).disableSelection();

	//dialog-form-reference
});