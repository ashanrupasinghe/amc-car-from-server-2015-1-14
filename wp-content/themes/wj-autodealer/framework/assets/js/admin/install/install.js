jQuery(function() {
    jQuery("#install_form").validate();

    function send_step( options ) {
        var response = [];
        jQuery.ajax({
            url: 'admin.php?page=at_theme_install',
            type: 'post',
            async: false,
            data: options,
            success: function(data) {
                if (data.status == 'NEXT') {
                    options.step = data.next_step;
                    response = send_step( options );
                } else {
                    response = data;
                }
            },
            dataType: "json"
        });
        return response;
    }

    var start_install = false;
	jQuery('#install_submit').click(function(e){
		e.preventDefault();
        if ( start_install ) return;
        if (!jQuery("#install_form").valid()){
            return false;
        }
		jQuery('#spinner_install').show();
        var test_data = 0;
        if( jQuery('#test_data').attr('checked') ){
            test_data = 1;
        }
        start_install = true;

        var options = {'action': 'install', 'step' : 'check_environment' , 'name': jQuery('#at_name').val(), 'email': jQuery('#at_email').val(), 'password': jQuery('#at_password').val(), 'test_data': test_data };
        var response = send_step( options );
        console.log(response);
        if ( response.status == 'OK' ){
            jQuery('.step_1').hide();
            jQuery('.step_2').show();
            jQuery('#step_2').addClass( 'active' );
            jQuery('body,html').animate({
                scrollTop: '140px'
            }, 800);
        } else {
            alert(response.message);
        }
        jQuery('#spinner_install').hide();
        start_install = false;
		
   //      jQuery.ajax({
   //      	url: 'admin.php?page=at_theme_install',
			// type: 'post',
			// data: {'action': 'install', 'name': jQuery('#name').val(), 'email': jQuery('#email').val(), 'password': jQuery('#password').val(), 'test_data': test_data },
   //          success: function(data) {
   //               if (data.status == 'OK'){
   //                  jQuery('.step_1').hide();
   //                  jQuery('.step_2').show();
   //                  jQuery('#step_2').addClass( 'active' );
   //                  jQuery('body,html').animate({
   //                      scrollTop: '140px'
   //                  }, 800);
   //              } else {
   //              	alert(data.message);
   //              }
			// 	jQuery('#spinner_install').hide();
   //              start_install = false;
   //          },
   //          dataType: "json"
   //      });
	});
    jQuery('.checked_img img').click(function(e){
        e.preventDefault();
        jQuery('.checked_img img').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('#site_type').val( jQuery(this).data('id') );
    });

    var install_complete = false;
    jQuery('#install_complete').click(function(e){
        e.preventDefault();
        if ( install_complete ) return;
        install_complete = true;
        jQuery.ajax({
            url: 'admin.php?page=at_theme_install',
            type: 'post',
            data: {'action': 'complete', 'site_type': jQuery('#site_type').val() },
            success: function(data) {
                if (data.status == 'OK'){
                    location.href = 'admin.php?page=at_site_options_general';
                } else {
                    alert(data.message);
                }
                jQuery('#spinner_install').hide();
                install_complete = false;
            },
            dataType: "json"
        });
    });
});