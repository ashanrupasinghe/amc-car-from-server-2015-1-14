jQuery(function() {
	jQuery('#select_country').change(function(){
		if(jQuery(this).val() > 0){
			var id = jQuery(this).val();
			location.href = 'admin.php?page=at_reference&tab=states&region_id=' + id ;
		}
	});
})