jQuery(function() {
	jQuery('.login-form').ajaxForm({
		beforeSubmit: function (data) {
			return jQuery('.login-form.active').valid();
		},		
		success: function (data)  {
			if (data.status == 'ERROR') {
			 	window['validation-' + jQuery('.login-form.active').attr('id')].showErrors(data.message);
			} 
			if (data.status == 'OK') {
			 	window.location.href = data.redirect_url;
			}    
		},      
		dataType:  'json'       
	});
    jQuery('form a.login').click(function(e){
    	e.preventDefault();
    	jQuery('.login-form').removeClass('active');
    	jQuery(this).closest('form').addClass('active').submit();
    });
    var validate_config = {
		rules : {
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 5,
			}
		}
	};
	window['validation-login-form-page'] = jQuery("#login-form-page").validate(validate_config);
	window['validation-login-form-popup'] = jQuery("#login-form-popup").validate(validate_config);

    jQuery('.registration-form').ajaxForm({
		beforeSubmit: function (data) {
			return jQuery('.registration-form.active').valid();
		},		
		success: function (data)  {
			 if (data.status == 'ERROR') {
			 	window['validation-' + jQuery('.registration-form.active').attr('id')].showErrors(data.message);
			} 
			if (data.status == 'OK') {
			 	window.location.href = data.redirect_url;
			}    
		},      
		dataType:  'json'       
	});
	window['validation-registration-form-page'] = jQuery("#registration-form-page").validate({
		rules : {
			name: {
				required: true,
				minlength: 3
			},
			email: {
				required: true,
				email: true
			},
			pass: {
				required: true,
				minlength: 5
			},
			pass_again: {
				equalTo: "#registration-form-page #pass"
			}
		}
	});
	window['validation-registration-form-popup'] = jQuery("#registration-form-popup").validate({
		rules : {
			name: {
				required: true,
				minlength: 3
			},
			email: {
				required: true,
				email: true
			},
			pass: {
				required: true,
				minlength: 5
			},
			pass_again: {
				equalTo: "#registration-form-popup #pass"
			}
		}
	});
    jQuery('form a.registration').click(function(e){
    	e.preventDefault();
    	jQuery('.registration-form').removeClass('active');
    	jQuery(this).closest('form').addClass('active').submit();
    });

	jQuery('.registration-open-popup').click(function(e){
		e.preventDefault();
		jQuery('#popup-login').hide();
		jQuery('#popup-registration').show();
	});
	jQuery('#login').click(function(e){
    	e.preventDefault();
		jQuery('#popup-login').show();
	});
	jQuery('.popup .close').click(function(e){
		e.preventDefault();
		jQuery(this).parent().hide();
	});
});