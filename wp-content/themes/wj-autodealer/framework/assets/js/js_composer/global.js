/**
 * Visual Composer Widgets
 *
 * @package AutoDiler
 */

/* =========================================================
 * composer-custom-views.js v1.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer ViewModel objects for shortcodes with custom
 * functionality.
 * ========================================================= */


(function($) {
	//jQuery('body')'select[name=manufacturer_id]').change(function(e){
	var get_model = false;
	jQuery( "body" ).on( "change", "select[name=manufacturer_id]", function() {
		var id = jQuery(this).val();
		if ( id > 0 && !get_model) {
			get_model = true;
			jQuery.ajax({
	            url: 'admin.php?page=at_reference&get_references=models&manufacturer_id=' + id,
	            success: function(data) {
	                jQuery('select[name=model_id]').html(jQuery('<option></option>').attr('value', 0).text('Any'));
	                if(data.length > 0) {
		                jQuery.each(data, function(i, item) {
				           jQuery('select[name=model_id]').append(jQuery('<option></option>').attr('value', item.id).text(item.name));
				        });
				    }
	                get_model = false;
	            }
	        });
	    } else if( id == 0) {
	    	jQuery('select[name=model_id]').html(jQuery('<option></option>').attr('value', 0).text('Any'));
	    }
        return false;
	});

})(window.jQuery);
