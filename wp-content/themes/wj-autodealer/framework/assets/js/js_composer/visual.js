	jQuery('.at-visual-selector').find('a').each(function() {

		default_value = jQuery(this).siblings('input').val();

		if(jQuery(this).attr('rel')==default_value){
				jQuery(this).addClass('current');
				jQuery(this).append('<div class="selector-tick"></div>');
			}

			jQuery(this).click(function(){

				jQuery(this).siblings('input').val(jQuery(this).attr('rel'));
				jQuery(this).parent('.at-visual-selector').find('.current').removeClass('current');
				jQuery(this).parent('.at-visual-selector').find('.selector-tick').remove();
				jQuery(this).addClass('current');
				jQuery(this).append('<div class="selector-tick"></div>');
				return false;
			});
	});
