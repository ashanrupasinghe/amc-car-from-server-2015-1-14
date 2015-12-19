jQuery(function() {
	jQuery('#select_manufacturer').change(function(){
		if(jQuery(this).val() > 0){
			var id = jQuery(this).val();
			location.href = 'admin.php?page=at_reference&tab=models&manufacturer_id=' + id ;
		}
	});

})