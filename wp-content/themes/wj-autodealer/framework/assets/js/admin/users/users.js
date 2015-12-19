jQuery(function() {
	jQuery('#action_vehicles_another').click(function(e){
		jQuery('#assign_user').show();
	});
	jQuery('#action_vehicles_archive').click(function(e){
		jQuery('#assign_user').hide();
	});
	jQuery('.user_block').click(function(e){
		e.preventDefault();
		var user_id = jQuery(this).data('id');
		jQuery( "#dialog-confirm-block" ).dialog({
			resizable: false,
			height:280,
			modal: true,
			open: function( event, ui ) {
				jQuery('#dialog-confirm-block #action_vehicles_archive').click();
				if(jQuery('#assign_user_id', '#dialog-confirm-block').val() == null){
					jQuery.ajax({
		            	url: 'admin.php?page=at_users',
						type: 'post',
						data: {'action': 'get_all_users', 'user_id':0},
			            success: function(data) {
			                if (data.status == 'OK'){
			                	var new_options = '';
			                	jQuery.each(data.message, function(key, value) {
									new_options += '<option value="' + key + '">' + value + '</option>';
								});
								jQuery('#assign_user_id', '#dialog-confirm-block').html(new_options);
								jQuery('.spinner', '#dialog-confirm-block').hide();
								jQuery('.popup-form', '#dialog-confirm-block').show();
			                } else {
			                	alert(data.message);
			                }
							jQuery('.theme_controls .spinner').hide();
			            },
			            dataType: "json"
			        });
				}
			},
			buttons: {
				"Block": function() {
					var action_vehicles = jQuery('input[name=action_vehicles]:checked', '#form_block_user').val();
					var assign_user_id = jQuery('#assign_user_id', '#form_block_user').val();

					jQuery( this ).dialog( "close" );
					jQuery('.theme_controls .spinner').show();
					jQuery.ajax({
		            	url: 'admin.php?page=at_users',
						type: 'post',
						data: {'action': 'user_block', 'user_id': user_id, 'action_vehicles': action_vehicles, 'assign_user_id' : assign_user_id },
			            success: function(data) {
			                if (data.status == 'OK'){
			                	jQuery('#user_' + user_id).remove();
								// jQuery('#user_' + user_id).find('.user_block').hide();
								// jQuery('#user_' + user_id).find('.user_unblock').show();
								// jQuery('#user_' + user_id).find('.status').addClass('blocked').html('Blocked');
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
	jQuery('.user_unblock').click(function(e){
		e.preventDefault();
		var user_id = jQuery(this).data('id');
		jQuery( "#dialog-confirm-unblock" ).dialog({
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				"Unblock": function() {
					jQuery( this ).dialog( "close" );
					jQuery('.theme_controls .spinner').show();
					jQuery.ajax({
		            	url: 'admin.php?page=at_users',
						type: 'post',
						data: {'action': 'user_unblock', 'user_id': user_id},
			            success: function(data) {
			                if (data.status == 'OK'){
			                	jQuery('#user_' + user_id).remove();
								// jQuery('#user_' + user_id).find('.user_block').show();
								// jQuery('#user_' + user_id).find('.user_unblock').hide();
								// jQuery('#user_' + user_id).find('.status').removeClass('blocked').html('Actived');
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
	
	//jQuery('.user_change_password').click(function(e){
	jQuery("#dialog-form-user").on("click", ".user_change_password", function(e){
		e.preventDefault();
		var user_id = jQuery(this).data('id');
		jQuery( "#dialog-change-password" ).dialog({
			resizable: false,
			height:140,
			modal: true,
			open: function( event, ui ) {
				jQuery('#password').val( '' );
			},
			buttons: {
				"change": function() {
					var password = jQuery('#password').val();
					if ( password.length < 5 ) {
						alert( 'Password incorrect!' );
						return;
					}
					jQuery( this ).dialog( "close" );
					jQuery('.theme_controls .spinner').show();
					jQuery.ajax({
		            	url: 'admin.php?page=at_users',
						type: 'post',
						data: {'action': 'user_change_password', 'password': password, 'user_id': user_id},
			            success: function(data) {
			                alert(data.message);
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
	jQuery('.user_edit, .user_add').click(function(e){
		e.preventDefault();
		var action = 'get_edit_form';
		var user_id = jQuery(this).data('id');
		if (user_id == 0) var action = 'get_add_form';
		jQuery( "#dialog-form-user" ).dialog({
			resizable: true,
			//height:140,
			modal: true,
			open: function( event, ui ) {
				var self = this;
				jQuery(this).html('<div class="spinner popup-modal">Loading form...</div>');
				jQuery.ajax({
	            	url: 'admin.php?page=at_users',
					type: 'post',
					data: {'action': action, 'user_id': user_id},
		            success: function(data) {
		                if (data.status == 'OK'){
		                	jQuery(self).html(data.message);
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
					var ajax_options = {
						beforeSubmit:	function (data) {
							return true;
						},
						success: function (data)  {
							if (data.status == 'OK'){
								jQuery( self ).dialog( "close" );
								location.href=location.href;
			                } else {
			                	alert(data.message);
			                }
			                jQuery(this).find('.spinner').hide();
						},      
						dataType:  'json'
					};
					jQuery('#add-edit-user').ajaxForm(ajax_options);
					jQuery('#add-edit-user').submit();
				},
				"Cancel": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	});
})