/* =========================================================
 * video section features
 * =========================================================
 * Copyright 2013 IrishMiss
 *
 * Visual composer ViewModel objects for shortcodes with custom
 * functionality.
 * ========================================================= */

jQuery(document).ready(function(){
    // Let's play with video width
    function im_video_background_size() {
        var jQuerywidth, jQueryheight;
        var ratio = 1.7777;
        if(jQuery('.boxed_layout').length > 0) {
            jQuerywidth = jQuery('.boxed_layout.page-body').width();
        } else {
            jQuerywidth = jQuery(window).width();
        }

        if( jQuery(window).width() < 767) {
            jQueryheight = jQuery('.at-video-section').parent().parent().height();
            jQuerywidth = parseInt(jQueryheight*ratio) + 'px';
            jQuery('.at-video-section .at-video-color-mask, .at-video-section .image-overlay, iframe.hero-video, .at-hero-video, .at-video-section video, .at-video-section .mejs-overlay, .at-video-section .mejs-container').css({width : jQuerywidth, height : jQueryheight});
            jQuery('.at-section-video').css('width', jQuerywidth, 'height', jQueryheight);
            jQuery('.at-video-section video, .at-video-section object').attr({'width' : jQuerywidth, 'height' : jQueryheight});

        } else {
            jQuery('.at-video-section .at-video-color-mask, .at-video-section .image-overlay, iframe.hero-video, .at-video-section video, .at-video-section .mejs-overlay, .at-section-video .mejs-container').css({width : jQuerywidth, height : parseInt(jQuerywidth/ratio)});
            jQuery('.at-section-video').css('width', jQuerywidth);
            jQuery('.at-video-section video, .at-video-section object').attr({'width' : jQuerywidth, 'height' : parseInt(jQuerywidth/ratio)});
        }


    }
    // Fullwidth youtube
    if(jQuery('iframe.hero-video').length > 0 ) {
        jQuery(window).on("debouncedresize resize", function () {
            im_video_background_size();
        });
        im_video_background_size();
    }
    // Call mediaelement
    if(jQuery('.at-video-section video').length > 0 ) {
        irishCall.meVisible('ready');
        irishCall.meVisible('scroll');
        if( jQuery(window).width() < 767) {
            jQuery('.at-video-section video').mediaelementplayer();
        }
        jQuery(window).on("debouncedresize resize", function () {
            im_video_background_size();
        });
        im_video_background_size();
    }
});

// })(window.jQuery);
