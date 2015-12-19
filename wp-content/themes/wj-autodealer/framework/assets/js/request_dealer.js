jQuery(function() {
	jQuery('.dialog_request_dealer').click(function(e){
		e.preventDefault();
		//var item_id = jQuery(this).data('id');
		jQuery( "#dialog-request-dealer" ).dialog({
			resizable: false,
			width:310,
			modal: true,
			open: function( event, ui ){
				jQuery('.ui-dialog-buttonset button:first-child').addClass('btn1');
				jQuery('.ui-dialog-buttonset button:last-child').addClass('btn2');
			},
			buttons: {
				"Send": function() {
					var self = this;
					jQuery.ajax({
		            	url: site_url('/profile/settings/want_be_dealer'),
		            	data: { 'comment' : jQuery('.want_be_dealer_comment').val()  },
						type: 'post',
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
				"Close": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	});
});