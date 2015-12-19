jQuery(function() {
	var validate_config = {
		rules : {
			fullname: {
				required: true,
				minlength: 3
			},
			email: {
				required: true,
				email: true
			},
			offer_details: {
			}
		}
	};
	var change_password = jQuery("#add_offer_form").validate(validate_config);

	jQuery('#add_offer').click(function(e){
		e.preventDefault();
		var id = jQuery(this).data('id');
		jQuery( "#dialog_add_offer" ).dialog({
			resizable: false,
			height:360,
			width: 335,
			modal: true,
			open: function( event, ui ){
				jQuery('.ui-dialog-buttonset button:first-child').addClass('btn1');
				jQuery('.ui-dialog-buttonset button:last-child').addClass('btn2');
			},
			buttons: {
				"SEND": function() {
					if(!jQuery('#add_offer_form').valid())
						return;
					var self = this;
					jQuery.ajax({
		            	url: site_url('/catalog/ajax_add_offer/' + id),
						data: { 'fullname' : jQuery('#add_offer_form #fullname').val(), 'email' : jQuery('#add_offer_form #email').val(),'offer_details' : jQuery('#add_offer_form #offer_details').val() },
			            type: "POST",
			            success: function(data) {
			                if (data.status == 'OK'){
			                	alert(data.message);
			                	jQuery('#add_offer_form #fullname').val('');
			                	jQuery('#add_offer_form #email').val('');
			                	jQuery('#add_offer_form #offer_details').val('');
								jQuery( self ).dialog( "close" );
			                } else {
			                	change_password.showErrors(data.message);
			                	//alert(data.message);
			                }
			            },
			            dataType: "json"
			        });
				},
				"CLOSE": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	});
});