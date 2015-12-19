/**
 * Visual Composer Range Post Type
 *
 * @package startup
 */

jQuery(document).ready(function($) {
	jQuery('.range-input-selector').change( function() {
		var el = jQuery(this),
			val = el.attr("value");

		if ( jQuery(this).hasClass('volume') ) {
			val = val * 100;
		}
		el.siblings('span.value').text( val );
	});
});

