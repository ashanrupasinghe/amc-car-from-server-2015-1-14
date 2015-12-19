jQuery(function() {
	var validate_config = {
		rules : {
			email: {
				required: true,
				email: true
			},
		}
	};
	var validation = jQuery(".recovery-form").validate(validate_config);
	jQuery('.recovery-form').ajaxForm({
		beforeSubmit: function (data) {
			return jQuery('.recovery-form').valid();
		},		
		success: function (data)  {
			if (data.status == 'ERROR') {
			 	validation.showErrors(data.message);
			} 
			if (data.status == 'OK') {
				alert( data.message);
			 	window.location.href = data.redirect_url;
			}    
		},      
		dataType:  'json'       
	});
    jQuery('.recovery-form a.recovery').click(function(e){
    	e.preventDefault();
    	jQuery('.recovery-form').submit();
    });

   	var validate_config = {
		rules : {
			hash: {
				required: true,
				minlength: 40,
				maxlength: 40,
			},
			new_password: {
				required: true,
				minlength: 5,
			},
			password_again: {
				equalTo: "#new_password"
			}
		}
	};

	var validation_pass = jQuery(".recovery-pass-form").validate(validate_config);
	jQuery('.recovery-pass-form').ajaxForm({
		beforeSubmit: function (data) {
			return jQuery('.recovery-pass-form').valid();
		},		
		success: function (data)  {
			if (data.status == 'PASS') {
				jQuery('#passwords').show();
				jQuery('#passwords input').removeAttr( 'disabled' );
			}
			if (data.status == 'ERROR') {
			 	validation_pass.showErrors(data.message);
			} 
			if (data.status == 'OK') {
				alert( data.message);
			 	window.location.href = data.redirect_url;
			}    
		},      
		dataType:  'json'       
	});
    jQuery('.recovery-pass-form a.recovery').click(function(e){
    	e.preventDefault();
    	jQuery('.recovery-pass-form').submit();
    });

});