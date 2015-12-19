jQuery('.at-composer-toggle').each(function(){

		default_value = jQuery(this).find('input').val();

		if(default_value == 'true'){
			jQuery(this).addClass('on');
		} else {
			jQuery(this).addClass('off');
		}

		jQuery(this).click(function() {

			if(jQuery(this).hasClass('on')) {											   
																		   
					jQuery(this).removeClass('on').addClass('off');
					jQuery(this).find('input').val('false');

			} else {

					jQuery(this).removeClass('off').addClass('on');
					jQuery(this).find('input').val('true');
								
			}
		});
});

jQuery('.at-toggle-button').each(function(){

		default_value = jQuery(this).find('input').val();

		if(default_value == 'true'){
			jQuery(this).addClass('on');
		} else {
			jQuery(this).addClass('off');
		}

		jQuery(this).click(function() {

			if(jQuery(this).hasClass('on')) {											   
																		   
					jQuery(this).removeClass('on').addClass('off');
					jQuery(this).find('input').val('false');

			} else {

					jQuery(this).removeClass('off').addClass('on');
					jQuery(this).find('input').val('true');
								
			}
		});
});
