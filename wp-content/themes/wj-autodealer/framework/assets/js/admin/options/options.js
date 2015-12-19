(function($) {
    $.nano = function(template, data) {
        return template.replace(/\{([\w\.]*)\}/g, function(str, key) {
            var keys = key.split("."),
                value = data[keys.shift()];
            $.each(keys, function() {
                value = value[this];
            });
            return (value === null || value === undefined) ? "" : value;
        });
    };
})(jQuery);

(function($) {
    var toggle = jQuery('.toggle_true'),
        val;
    toggle.each(function() {
        if (jQuery(this).is('select')) {
            val = jQuery(this).val();
        } else {
            _this = jQuery(this);
            chk = _this.attr('checked');
            if (chk) {
                val = jQuery(this).val();
            }
        }
        var nameMatch = jQuery(this).attr('name').match(/\[[^\]]*/),
            _name = (nameMatch) ? nameMatch[0].replace('[', '') : jQuery(this).attr('name'),
            el = jQuery('*[class^="theme_option_set ' + _name + '_"]'),
            attrID = _name + '_' + val;
        el.each(function() {
            if (jQuery(this).hasClass(attrID)) {
                jQuery(this).css({
                    display: "block"
                });
            } else {
                jQuery(this).css({
                    display: "none"
                });
            }
        });
        jQuery(this).change(function() {
            var _id = jQuery(this).attr('id');
            var nameMatch = jQuery(this).attr('name').match(/\[[^\]]*/),
                _name = (nameMatch) ? nameMatch[0].replace('[', '') : jQuery(this).attr('name'),
                el = jQuery('*[class^="theme_option_set ' + _name + '_"]'),
                attrID = _name + '_' + jQuery(this).val();
            el.each(function() {
                console.log(this);
                if (jQuery(this).hasClass(attrID)) {
                    jQuery(this).css({
                        display: 'block'
                    });
                } else {
                    jQuery(this).css({
                        display: 'none'
                    });
                }
            });
        });
    });
})(jQuery);

jQuery(function() {
    if (jQuery().datetimepicker) {
        jQuery('.datetimepicker').each(function(i, n) {
            var options = {};
            if (typeof(jQuery(n).data('format')) != 'undefined' && jQuery(n).data('format') != '')
                options.format = jQuery(n).data('format');
            if (typeof(jQuery(n).data('min-date')) != 'undefined' && jQuery(n).data('min-date') != '')
                options.minDate = jQuery(n).data('min-date');
            options.defaultSelect = false;
            options.allowBlank = true;
            jQuery(n).datetimepicker(options);
        });
    }
    jQuery('.radio_image img').click(function() {
        jQuery(this).parents('.theme_option').find('img').removeClass('active');
        jQuery(this).addClass('active');
    });
    jQuery(".group_items").on("click", ".item-title", function(e) {
        e.preventDefault();
        jQuery(this).parents('li').find('.menu-item-options').toggle();
    });
    jQuery(".group_items").on("click", ".submit_delete", function(e) {
        e.preventDefault();
        jQuery(this).parents('li').remove();
    });

    // jQuery('.pushtocall').click(function() {
    //     e.preventDefault();
    //     var action = jQuery(this).data('action');
    //     var response = [];
    //     jQuery.ajax({
    //         url: 'admin.php?page=at_theme_install',
    //         type: 'post',
    //         async: false,
    //         data: options,
    //         success: function(data) {
    //             if (data.status == 'NEXT') {
    //                 options.step = data.next_step;
    //                 response = send_step( options );
    //             } else {
    //                 response = data;
    //             }
    //         },
    //         dataType: "json"
    //     });
    //     return response;
    // });

    jQuery('.type_group .submit_add').click(function(e) {
        var group_container = jQuery(this).parents('.type_group');
        var li_count = group_container.find('li._group_item').length;
        var max = 0;
        group_container.find('li._group_item').each(function(i, n) {
            var check = jQuery(n).attr('data-id');
            if (check > max) max = check;
        });
        max++;
        li_count++;

        var item = '<li data-id="{id}" class="_group_item">' + group_container.find('.theme_option .empty_form').html() + '</li>';
        var data = {
            'id': max,
            'n': li_count++
        };
        item = jQuery.nano(item, data);
        group_container.find('ul.group_items').append(item);
        console.log(group_container.find('ul li:last-child .item-title'));
        group_container.find('ul.group_items > li:last-child .item-title').click();

        jQuery("#catalog_selected_items,#catalog_sets_items").sortable({
            update: function(event, ui) {
                if (ui.sender != null) {
                    if (ui.sender.attr('id') == 'catalog_sets_items') {
                        var html = ui.item.html().replace("[sets]", "[value]");
                    } else {
                        var html = ui.item.html().replace("[value]", "[sets]");
                    }
                    ui.item.html(html);
                }
            },
            connectWith: ".catalog_sortable"
        }).disableSelection();
    });
    jQuery('.group_sortable').sortable({
        opacity: 0.6,
        'handle': '.item-title'
    });
    jQuery('.toogle_header').click(function() {
        jQuery(this).toggleClass('active').next().toggle();
    });

    jQuery("#catalog_selected_items,#catalog_sets_items").sortable({
        update: function(event, ui) {
            if (ui.sender != null) {
                if (ui.sender.attr('id') == 'catalog_sets_items') {
                    var html = ui.item.html().replace("[sets]", "[value]");
                } else {
                    var html = ui.item.html().replace("[value]", "[sets]");
                }
                ui.item.html(html);
            }
        },
        connectWith: ".catalog_sortable"
    }).disableSelection();

    if (typeof(wp.media) != 'undefined') {
        // Media Uploader
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
        jQuery('.theme_option .upload_button').click(function(e) {
            var send_attachment_bkp = wp.media.editor.send.attachment,
                button = jQuery(this),
                id = button.attr('data-target-id');

            _custom_media = true;

            wp.media.editor.send.attachment = function(props, attachment) {
                if (_custom_media) {
                    jQuery("#" + id).val(attachment.url);
                    jQuery("#" + id + "-preview .preview").attr("src", attachment.url);
                } else {
                    return _orig_send_attachment.apply(this, [props, attachment]);
                };
            }
            wp.media.editor.open(button);
            return false;
        });
        jQuery('.add_media').on('click', function() {
            _custom_media = false;
        });
    }

    var get_model = false;
    jQuery('#_manufacturer_id').change(function(e) {
        var id = jQuery(this).val();
        if (id > 0 && !get_model) {
            get_model = true;
            jQuery.ajax({
                url: 'admin.php?page=at_reference&get_references=models&manufacturer_id=' + id,
                success: function(data) {
                    jQuery('#_model_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
                    if (data.length > 0) {
                        jQuery.each(data, function(i, item) {
                            jQuery('#_model_id').append(jQuery('<option></option>').attr('value', item.id).text(item.name));
                        });
                    }
                    get_model = false;
                }
            });
        } else if (id == 0) {
            jQuery('#_model_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
        }
        return false;
    });

    var get_state = false;
    jQuery('#_region_id').change(function(e) {
        var id = jQuery(this).val();
        if (id > 0 && !get_state) {
            get_state = true;
            jQuery.ajax({
                url: 'admin.php?page=at_reference&get_references=states&region_id=' + id,
                success: function(data) {
                    jQuery('#_state_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
                    if (data.length > 0) {
                        jQuery.each(data, function(i, item) {
                            jQuery('#_state_id').append(jQuery('<option></option>').attr('value', item.id).text(item.name));
                        });
                    }
                    get_state = false;
                }
            });
        } else if (id == 0) {
            jQuery('#_state_id').html(jQuery('<option></option>').attr('value', 0).text('Any'));
        }
        return false;
    });


    jQuery('.range-input-selector').change(function() {
        var el = jQuery(this),
            val = el.attr("value");

        if (jQuery(this).hasClass('volume')) {
            val = val * 100;
        }
        el.siblings('span.value').text(val);
    });

    if (typeof(typenow) != 'undefined' && typenow == 'car' && typeof(plupload) != 'undefined') {
        jQuery('#publish').click(function(e) {
            var error = false;
            var fields = ['#_manufacturer_id', '#_owner_id', '#_transport_type_id'];
            //fields
            jQuery.each(fields, function(index, value) {
                if (jQuery(value).val() == 0) {
                    error = true;
                    jQuery(value).addClass('error');
                }
            });
            if (error == true) {
                alert('Enter required fields!');
                e.preventDefault();
                setTimeout(function() {
                    jQuery('#publish').removeClass('button-primary-disabled');
                    jQuery('#publishing-action .spinner').hide();
                }, 50);
                return false;
            }
            return true;
        });

        jQuery('.photos_sortable').sortable({
            opacity: 0.6,
            // 'handle': '.item-title'
        });

        var uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'upload-photos',
            //max_file_count: maxfiles,
            //container: document.getElementById('at_car_side_photos_meta_box'),
            url: site_url('/profile/vehicles/upload/'),
            flash_swf_url: './plupload/Moxie.swf',
            silverlight_xap_url: './plupload/js/Moxie.xap',
            resize: {
                width: 1000,
                height: 1000,
                quality: 90
            },
            filters: {
                max_file_size: '10mb',
                mime_types: [{
                    title: "Image files",
                    extensions: "jpg,jpeg,gif,png"
                }]
            },
            init: {
                PostInit: function() {},
                FilesAdded: function(up, files) {
                    uploader.start();
                },
                Error: function(up, err) {
                    console.log(err.code + ": " + err.message);
                },
                FileUploaded: function(up, file, response) {
                    response = JSON.parse(response.response);
                    if (response.status == 'OK') {
                        var data = {
                            'src': response.file_name_url,
                            'file_name': response.file_name,
                            'is_main': '',
                            'photo_value': '0'
                        };
                        if (jQuery("#car_photo_upload .photos .photo_wrapper").length == 0) {
                            data.is_main = 'photo_main';
                            data.photo_value = '1';
                        }
                        jQuery('#car_photo_upload .photos').append(jQuery.nano(jQuery('#empty_photo_wrapper').html(), data));
                        //jQuery('#car_photo_upload .photos').append("<div><input type='hidden' name='at_options[photos][]' value='" + response.file_name + "'><img src=\"" + response.file_name_url + "\" ></div>" );
                    } else {
                        console.log(response.code + ": " + response.message);
                    }
                }
            }
        });
        uploader.init();
        jQuery("#car_photo_upload").on("click", ".icon.set_main_image", function(e) {
            e.preventDefault();
            jQuery("#car_photo_upload .photo_wrapper.photo_main.item").removeClass('photo_main').find('.actions').find('input').val('0');
            jQuery(this).closest(".actions").find('input').val('1').closest(".photo_wrapper.item").addClass('photo_main');
        });
        jQuery("#car_photo_upload").on("click", ".icon.delete_image", function(e) {
            e.preventDefault();
            jQuery(this).closest(".photo_wrapper").removeClass('item');
            jQuery(this).closest(".photo_wrapper").hide('slow', function() {
                jQuery(this).remove();
            });
            if (jQuery(this).closest(".photo_wrapper").hasClass('photo_main')) {
                jQuery("#car_photo_upload .photo_wrapper.item:first").find(".icon.set_main_image").click();
            }
        });
        var get_affiliate = false;
        jQuery("#_owner_id").change(function() {
            var user_id = jQuery(this).val();
            if (user_id > 0 && !get_affiliate) {
                get_affiliate = true;
                jQuery.ajax({
                    url: 'admin.php?page=at_users',
                    type: 'post',
                    data: {
                        'action': 'get_affiliates',
                        'user_id': user_id
                    },
                    success: function(data) {
                        jQuery('#_affiliate_id').html(jQuery('<option></option>').attr('value', 0).text('Default'));
                        if (data.message.length > 0) {
                            jQuery.each(data.message, function(i, item) {
                                jQuery('#_affiliate_id').append(jQuery('<option></option>').attr('value', item.id).text(item.name));
                            });
                        }
                        get_affiliate = false;
                    },
                    dataType: "json"
                });
            } else if (user_id == 0) {
                jQuery('#_affiliate_id').html(jQuery('<option></option>').attr('value', 0).text('Default'));
            }
            return false;
        });
    }
    jQuery("#_manufacturer_id, #_model_id, #_region_id, #_state_id, #select_manufacturer, #select_country").select2();
})
