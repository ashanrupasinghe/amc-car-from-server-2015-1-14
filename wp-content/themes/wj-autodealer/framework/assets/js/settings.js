jQuery(function() {
	jQuery('#settings-form').ajaxForm({
		beforeSubmit: function (data) {
			return jQuery('#settings-form').valid();
		},		
		success: function (data)  {
			if (data.status == 'ERROR') {
			 	validation.showErrors( data.message );
			} 
			if (data.status == 'OK') {
			 	alert(data.message);
			}
			jQuery('.form_loading').hide();
		},      
		dataType:  'json'       
	});
	var validate_config = {
		rules : {
			name: {
				required: true,
				minlength: 3,
				maxlength: 50,
			},
			phone_1: {
				minlength: 6,
				maxlength: 15,
			},
			phone_2: {
				minlength: 6,
				maxlength: 15,
			},
			per_page: {
				required: true,
				minlength: 1,
				maxlength: 2,
			},
			hide_number_ads: {
			}
		}
	};
	var validation = jQuery("#settings-form").validate(validate_config);
	jQuery('#settings-form .submit-save').click(function(e){
		e.preventDefault();
		jQuery('.form_loading').css({'display': 'inline-block'});
    	jQuery(this).closest('form').submit();
	});

	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button : 'upload-photo',
		container: document.getElementById('upload-photo-container'),
		url : site_url( '/profile/settings/upload/' ),
		flash_swf_url : './plupload/Moxie.swf',
		silverlight_xap_url : './plupload/js/Moxie.xap',
		resize : {width : 1000, height : 1000, quality : 90},
		filters : {
			max_file_size : '10mb',
			mime_types: [
				{title : "Image file", extensions : "jpg,jpeg,gif,png"}
			]
		},

		init: {
			FilesAdded: function(up, files) {
				jQuery('.photo .loader').show();
				uploader.start();
			},
			Error: function(up, err) {
				//document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
			},
			FileUploaded: function(up, file, response) {
				response = JSON.parse(response.response);
				if(response.status == 'OK'){
					jQuery('.photo .loader').hide();
					jQuery('#profile_photo').attr('src', response.file_name_url);
				} else {
					//document.getElementById('console').innerHTML += "\nError #" + response.code + ": " + response.message;
				}
			}
		}
	});

	uploader.init();

	jQuery('.photo_delete').click(function(e){
		e.preventDefault();
		//var item_id = jQuery(this).data('id');
		jQuery( "#dialog-confirm-delete" ).dialog({
			resizable: false,
			height:170,
			modal: true,
			open: function( event, ui ){
				jQuery('.ui-dialog-buttonset button:first-child').addClass('btn1');
				jQuery('.ui-dialog-buttonset button:last-child').addClass('btn2');
			},
			buttons: {
				"Yes": function() {
					jQuery('.photo .loader').show();
					var self = this;
					jQuery.ajax({
		            	url: site_url('/profile/settings/del_photo'),
						type: 'post',
			            success: function(data) {
			                if (data.status == 'OK'){
								jQuery('#profile_photo').attr('src', data.file_name_url);
			                } else {
			                	alert(data.message);
			                }
			                jQuery( self ).dialog( "close" );
			                jQuery('.photo .loader').hide();
			            },
			            dataType: "json"
			        });
				},
				"No": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	});
	
	
	var validate_config = {
		rules : {
			old_password: {
				required: true,
				minlength: 5,
			},
			new_password: {
				required: true,
				minlength: 5,
			},
			repeat_password: {
				equalTo: "#new_password"
			}
		}
	};
	var change_password = jQuery("#change-password").validate(validate_config);

	jQuery('.submit-change-password').click(function(e){
		e.preventDefault();
		//var item_id = jQuery(this).data('id');
		jQuery( "#dialog-change-password" ).dialog({
			resizable: false,
			height:400,
			width: 335,
			modal: true,
			open: function( event, ui ){
				jQuery('.ui-dialog-buttonset button:first-child').addClass('btn1');
				jQuery('.ui-dialog-buttonset button:last-child').addClass('btn2');
			},
			buttons: {
				"Change": function() {
					if(!jQuery('#change-password').valid())
						return;
					var self = this;
					jQuery.ajax({
		            	url: site_url('/profile/settings/change_password'),
						data: { 'old_password' : jQuery('#old_password').val(), 'new_password' : jQuery('#new_password').val(),'repeat_password' : jQuery('#repeat_password').val()},
			            type: "POST",
			            success: function(data) {
			                if (data.status == 'OK'){
			                	alert(data.message);
								jQuery( self ).dialog( "close" );
			                } else {
			                	alert(data.message);
			                }
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
});