/**
 * Visual Composer Widgets
 *
 * @package startup
 */

/* =========================================================
 * composer-custom-views.js v1.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer ViewModel objects for shortcodes with custom
 * functionality.
 * ========================================================= */


// jQuery(document).ready(function($) {
// 	jQuery('.page-composer-icon-filter')
// 	  .change( function () {
// 	    var filter = jQuery(this).val();
// 	    var list = jQuery(this).parent().siblings('.at-font-icons-wrapper');
// 	    if(filter) {
// 	      jQuery(list).find("span:not(:Contains(" + filter + "))").parent('a').hide();
// 	      jQuery(list).find("span:Contains(" + filter + ")").parent('a').show();
// 	    } else {
// 	      jQuery(list).find("a").show();
// 	    }
// 	    return false;
// 	  })
// 	.keyup( function () {
// 	    jQuery(this).change();
// 	});


// 	// im_menus_icon_selector();



// 	// jQuery(".at-visual-selector a").click(function(){
// 	// 	jQuery(this).siblings('input').val(jQuery(this).attr('rel'));
// 	// 	jQuery(this).parent('.at-visual-selector').find('.current').removeClass('current');
// 	// 	jQuery(this).addClass('current');
// 	// 	return false;
// 	// })
			// 'top': el.position().top,
			// 'left': el.position().left,


jQuery.fn.iconLabels = function(){
	var el = jQuery(this),
		rel = el.attr('rel'),
		title = el.attr('title'),
		container = jQuery("<div class='tooltip iconTooltip'></div>").animate({
			'opacity': 1
		},800),
		inner = jQuery("<div />").addClass('inner'),
		preview = jQuery("<div />").addClass('preview'),
		prev1 = jQuery('<i class="' + rel + '" style="font-size: 12px; color: #fff"></i> '),
		prev2 = jQuery('<i class="' + rel + '" style="font-size: 18px; color: #efefef"></i> '),
		prev3 = jQuery('<i class="' + rel + '" style="font-size: 26px; color: #e5e5e5"></i> ');
	var icoMoon=rel.match(/^im-/g);
	var fontAwesome=rel.match(/^fa-/g);

	//Reset title
	el.attr('title', '');

	// inner.text( title );	
	jQuery('<div><strong>Icon Preview:</strong></div>').appendTo( preview );

	prev1.appendTo( preview );
	prev2.appendTo( preview );
	prev3.appendTo( preview );

	jQuery('<div><strong>Custom Usage:</strong><pre>&lt;i class="' + rel + '"&gt;&lt;/i&gt;</pre></div>').appendTo( preview );

	if ( icoMoon ) {
		jQuery('<div>Vendor: Ico Moon</div>').appendTo( preview );
	}
	if ( fontAwesome ) {
		jQuery('<div>Vendor: Font Awesome</div>').appendTo( preview );
	}

	preview.appendTo( inner );

	inner.appendTo( container );

	el.hover(
		function() { container.appendTo(this); },
		function() {
			container.remove();
			jQuery(document).find('.iconTooltip').remove();
		}
	);
	
};

jQuery(document).ready(function($) {

	// Set visibility to 0
	var im_visible_icon_selector = 0;
	jQuery('.at_icon_selector').each(
		function(e) {
			jQuery(this).iconLabels();
		}
	);
	jQuery('.at-toggle-icons').click(function() {
		if ( im_visible_icon_selector == 0) {
			jQuery(this).parent().siblings('.at-font-icons-wrapper').slideDown(500);
			im_visible_icon_selector = 1;
		} else {
			jQuery(this).parent().siblings('.at-font-icons-wrapper').slideUp(500);
			im_visible_icon_selector = 0;
		}
		return false;
	});
	jQuery('.page-composer-icon-filter')
	  .on( 'change', function () {
	    var filter = jQuery(this).val(),
	    	list = jQuery(this).parent().parent().find('.at-font-icons-wrapper');

		if ( im_visible_icon_selector == 0) {
			jQuery(this).parent().siblings('.at-font-icons-wrapper').slideDown(500);
			im_visible_icon_selector = 1;
		}
	    if(filter) {
	      jQuery(list).find("span:not(:Contains(" + filter + "))").parent().hide();
	      jQuery(list).find("span:Contains(" + filter + ")").parent().show();
	    } else {
	      jQuery(list).find("a").show();
	    }
	  })
	.keyup( function () {
	    jQuery(this).change();
	});

	jQuery('.at-visual-selector').find('a').each(function() {
		var default_value = jQuery(this).siblings('input').val();
		if(jQuery(this).attr('rel')==default_value){
				jQuery(this).addClass('current');
		}

		// Add current icon to button preview
		if ( default_value.length > 0 ) {
			jQuery(this).parent().parent().siblings('.at-icon-preview').addClass( default_value );
		}

		// Asign curent icon
		jQuery(this).click(function(){
			jQuery(this).siblings('input').val(jQuery(this).attr('rel'));
			jQuery(this).parent('.at-visual-selector').find('.current').removeClass('current');
			jQuery(this).addClass('current');
			jQuery(this).parent().parent().find('.at-icon-preview i')
				.html('<i class="' + jQuery(this).attr('rel') + '" />');
			
			jQuery('.at-font-icons-wrapper').slideUp(500);
			im_visible_icon_selector = 0;
			return false;
		})
	});
});

