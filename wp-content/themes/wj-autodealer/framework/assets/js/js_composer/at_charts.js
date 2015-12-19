/**
 * Charts Based on Raphael Vector Graphics
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
        function im_chart() {
            if ($.exists('.at-chart')) {
                $(window).on("load", function () {
                    $('.at-chart').each(function () {
                        var $this, $parent_width, $chart_size;
                        $this = $(this);
                        $parent_width = $(this).parent().width();
                        $chart_size = $this.attr('data-barSize');
                        if ($parent_width < $chart_size) {
                            $chart_size = $parent_width;
                            $this.css('line-height', $chart_size);
                            $this.find('i').css({
                                'line-height': $chart_size + 'px',
                                'font-size': ($chart_size / 3)
                            });
                        }
                        if (!$this.hasClass('chart-animated')) {
                            $this.easyPieChart({
                                animate: 1300,
                                lineCap: 'round',
                                lineWidth: $this.attr('data-lineWidth'),
                                size: $chart_size,
                                barColor: $this.attr('data-barColor'),
                                trackColor: $this.attr('data-trackColor'),
                                scaleColor: 'transparent',
                                onStep: function (value) {
                                    this.$el.find('.chart-percent span').text(Math.ceil(value));
                                }
                            });
                        }
                    });
                });
            }
        }

        //Activate onLoad
        jQuery(document).ready(function () {
            im_chart();
        });

        //Activate on scroll
        jQuery(window).scroll(function () {
            im_chart();
        });
    });
})(jQuery);
