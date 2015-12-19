jQuery(function() {
    var republish = false;
    jQuery('.car_republish').click(function(e) {
        e.preventDefault();
        var id = jQuery(this).data('id');
        if (id > 0 && republish != id) {
            republish = id;
            jQuery.ajax({
                url: site_url('/profile/vehicles/'),
                type: 'post',
                data: {
                    'action': 'car_republish',
                    'car_id': id
                },
                success: function(data) {
                    if (data.status == 'OK') {
                        jQuery('#car-' + id).find('.date').text(data.message);
                    } else {
                        alert(data.message);
                    }
                    republish = false;
                },
                dataType: "json"
            });
        }
    });

    var promoteTop = false;
    jQuery('.promote_top').click(function(e) {
        e.preventDefault();
        var id = jQuery(this).data('id');
        if (id > 0 && promoteTop != id) {
            promoteTop = id;
            jQuery.ajax({
                url: site_url('/profile/vehicles/'),
                type: 'post',
                data: {
                    'action': 'promote_top',
                    'car_id': id
                },
                success: function(data) {
                    if (data.status == 'OK') {
                        jQuery('#car-' + id).find('.date').text(data.message);
                        window.location.replace(data.redirect);
                    } else {
                        alert(data.message);
                    }
                    promoteTop = false;
                },
                dataType: "json"
            });
        }
    });

    var remove = false;
    jQuery('.car_archive').click(function(e) {
        e.preventDefault();
        var id = jQuery(this).data('id');
        if (id > 0 && remove != id) {
            remove = id;
            jQuery.ajax({
                url: site_url('/profile/vehicles/'),
                type: 'post',
                data: {
                    'action': 'car_archive',
                    'car_id': id
                },
                success: function(data) {
                    if (data.status == 'OK') {
                        jQuery('#car-' + id).hide('slow', function() {
                            jQuery('#car-' + id).remove();
                        });
                    } else {
                        alert(data.message);
                    }
                    remove = false;
                },
                dataType: "json"
            });
        }
    });

    var add_best_offer = false;
    jQuery('.car').on("click", ".car_add_best_offer", function(e) {
        e.preventDefault();
        var id = jQuery(this).data('id');
        if (id > 0 && add_best_offer != id) {
            add_best_offer = id;
            jQuery.ajax({
                url: site_url('/profile/vehicles/'),
                type: 'post',
                data: {
                    'action': 'car_add_best_offer',
                    'car_id': id
                },
                success: function(data) {
                    if (data.status == 'OK') {
                        jQuery('#car-' + id).find('.car_add_best_offer').removeClass('car_add_best_offer').addClass('car_remove_best_offer').html(data.message);
                    } else {
                        alert(data.message);
                    }
                    add_best_offer = false;
                },
                dataType: "json"
            });
        }
    });

    var remove_best_offer = false;
    jQuery('.car').on("click", ".car_remove_best_offer", function(e) {
        e.preventDefault();
        var id = jQuery(this).data('id');
        if (id > 0 && remove_best_offer != id) {
            remove_best_offer = id;
            jQuery.ajax({
                url: site_url('/profile/vehicles/'),
                type: 'post',
                data: {
                    'action': 'car_remove_best_offer',
                    'car_id': id
                },
                success: function(data) {
                    if (data.status == 'OK') {
                        jQuery('#car-' + id).find('.car_remove_best_offer').removeClass('car_remove_best_offer').addClass('car_add_best_offer').html(data.message);
                    } else {
                        alert(data.message);
                    }
                    remove_best_offer = false;
                },
                dataType: "json"
            });
        }
    });

    var publish = false;
    jQuery('.car_publish').click(function(e) {
        e.preventDefault();
        var id = jQuery(this).data('id');
        if (id > 0 && publish != id) {
            publish = id;
            jQuery.ajax({
                url: site_url('/profile/vehicles/archive'),
                type: 'post',
                data: {
                    'action': 'car_publish',
                    'car_id': id
                },
                success: function(data) {
                    if (data.status == 'OK') {
                        jQuery('#car-' + id).hide('slow', function() {
                            jQuery('#car-' + id).remove();
                        });
                    } else {
                        alert(data.message);
                    }
                    publish = false;
                },
                dataType: "json"
            });
        }
    });
    jQuery('#select_all_cars').click(function() {
        jQuery(".select_car").attr('checked', this.checked);
    });
    jQuery('#apply_actions').click(function(e) {
        e.preventDefault();
        //jQuery( ".select_car:checked" ).length;
        var action = jQuery('#select_action_car').val();
        if (action == 0) {
            alert('Select action');
            return;
        }
        jQuery(".select_car:checked").each(function(i) {
            jQuery('#car-' + jQuery(this).val()).find('.' + action).click();
        });
    });
});