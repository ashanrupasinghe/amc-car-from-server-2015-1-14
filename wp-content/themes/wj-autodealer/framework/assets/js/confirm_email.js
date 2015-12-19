jQuery(function() {
	var validate_config = {
		rules : {
			code: {
				required: true,
				minlength: 40,
				maxlength: 40,
			},
		}
	};
	var validation = jQuery(".confirm-form").validate(validate_config);
	jQuery('.confirm-form').ajaxForm({
		beforeSubmit: function (data) {
			return jQuery('.confirm-form').valid();
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
    jQuery('.confirm-form a.confirm').click(function(e){
    	e.preventDefault();
    	jQuery('.confirm-form').submit();
    });

    jQuery('.confirm-form a.send_mail_again').click(function(e){
    	e.preventDefault();
    	jQuery.ajax({
        	url: site_url('/auth/confirm_email'),
			data: { 'action' : 'send_again' },
            type: "POST",
            success: function(data) {
                if (data.status == 'OK'){
                	alert(data.message);
                } else {
                	validation.showErrors(data.message);
                }
            },
            dataType: "json"
        });
    });

});