(function($){
    $.nano = function(template, data) {
        return template.replace(/\{([\w\.]*)\}/g, function (str, key) {
            var keys = key.split("."), value = data[keys.shift()];
            $.each(keys, function () { value = value[this]; });
            return (value === null || value === undefined) ? "" : value;
        });
    };
})(jQuery);
jQuery(function() {

	var validate_config = {
		rules : {
			name: {
				required: true,
				minlength: 3,
				maxlength: 50,
			},
			email: {
				required: true,
				email: true,
			},
			phone_1: {
				minlength: 6,
				maxlength: 15,
			},
			phone_2: {
				minlength: 6,
				maxlength: 15,
			}
		}
	};

	jQuery("#dealer_affiliates_items").on("click", ".submit-save", function(e){
		e.preventDefault();
		var form = jQuery(this).closest('form');
		var validation = form.validate(validate_config);
		form.ajaxForm({
			beforeSubmit: function (data) {
				return form.valid();
			},		
			success: function (data)  {
				if (data.status == 'ERROR') {
					//console.log(data.message);
				 	validation.showErrors( data.message );
				} 
				if (data.status == 'OK') {
					form.closest('.item-content').html(data.content);
				 	alert(data.message);
				}    
			},      
			dataType:  'json'       
		}).submit();
	});

	jQuery("#dealer_affiliates_items").on("click", ".submit-delete", function(e){
		e.preventDefault();
		var form = jQuery(this).closest('form');
		var id = form.find('input[name=affiliate_id]').val();
		if ( id > 0) {
			jQuery.ajax({
	            url: site_url('/profile/settings/dealer_affiliate_acions/' + id + '/'),
	            data: { 'action' : 'delete' },
	            type: "POST",
	            dataType: 'json',
	            success: function(data) {
	            	alert(data.message);
	            }
	        });
	    }
	    form.closest('.affiliate_item').remove();
	    if ( form.closest('.affiliate_item').hasClass( 'main_affiliate' ) ) {
    		jQuery("#dealer_affiliates_items").find('.affiliate_item:first-child' ).addClass( 'main_affiliate' );
    	}
	});

	jQuery("#dealer_affiliates_items").on("click", ".submit-main", function(e){
		e.preventDefault();
		var form = jQuery(this).closest('form');
		var id = form.find('input[name=affiliate_id]').val();
		if ( id > 0) {
			jQuery.ajax({
	            url: site_url('/profile/settings/dealer_affiliate_acions/' + id + '/'),
	            data: { 'action' : 'main' },
	            type: "POST",
	            dataType: 'json',
	            success: function(data) {
	            	alert(data.message);
	            }
	        });
	    }
	    jQuery("#dealer_affiliates_items").find('.affiliate_item' ).removeClass( 'main_affiliate' );
	    form.closest('.affiliate_item').addClass( 'main_affiliate' );
	});

	jQuery('.add_affiliate').click(function(e){
		e.preventDefault();
		var group_container = jQuery('#dealer_affiliates_items');
		var item_count = group_container.find('.affiliate_item').length;
		var max = 0;
		group_container.find('.affiliate_item').each(function(i,n){
	      var check = jQuery(n).attr('data-id');
	      if(check>max) max = check;
	    });
	    max++;
	    item_count++;

		var item = '<div class="settings_form affiliate_item" data-id="{id}">' + jQuery('#empty_affiliate_item').html() + '</div>';
		var data = {'id' : max, 'n' : item_count++};
 		item = jQuery.nano(item, data);
		group_container.append( item );
     	group_container.find('.affiliate_item:last-child .item-title' ).click();
	});
	jQuery("#dealer_affiliates_items").on("click", ".item-title", function(e){
		e.preventDefault();
		jQuery(this).next().toggle();
	});
});