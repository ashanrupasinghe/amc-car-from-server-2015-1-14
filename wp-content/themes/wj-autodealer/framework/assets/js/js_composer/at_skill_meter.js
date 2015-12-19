/**
 * Skill Meter
 * 
 * @package startup
 */

(function ($) {

    "use strict";

    $.exists = function (selector) {
        return ($(selector).length > 0);
    };

    $(document).ready(function () {

        // Register method
        function im_skill_meter() {    
                if ($.exists('.at-skill-meter')) {
                    jQuery(".at-skill-meter .progress-outer:in-viewport").each(function () {
                        var jQuerythis;
                        jQuerythis = jQuery(this);
                        if (!jQuerythis.hasClass('scroll-animated')) {
                            jQuerythis.addClass('scroll-animated');
                            jQuerythis.animate({
                                width: jQuery(this).attr("data-width") + '%'
                            }, { duration: 2000, easing: 'easeInOutCirc' });
                        }
                    });
                }
        }

        //Activate onLoad
        jQuery(document).ready(function () {
            im_skill_meter();
        });

        //Activate on scroll
        jQuery(window).scroll(function () {
            im_skill_meter();
        });
    });
})(jQuery);
