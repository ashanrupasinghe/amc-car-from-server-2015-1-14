jQuery(function() {
	var ajax_options = {
		beforeSubmit:	function (data) {
			jQuery('#options_form .spinner').show();
			return true;
		},
		success: function (data)  {
			jQuery('#options_form .spinner').hide();
			 if (data.status == 'ERROR') {
			 	alert(data.message);
			 	// if (typeof(data['notice']) == 'undefined') {
			 	// 	validator.showErrors(data.message);
			 	// } else {
			 	// 	alert(data.notice);
			 	// }
			} 
			if (data.status == 'OK') {
			 	//window.location = data.redirect_url;
			 	alert(data.message);
			}    
		},      
		dataType:  'json'
	};
	jQuery('#options_form').ajaxForm(ajax_options);
});