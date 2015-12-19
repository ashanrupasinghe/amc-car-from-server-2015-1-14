var AT_Application = {};
AT_Application.Extend = function(defaults,settings){
    var merged = {};
    for (var attrname in defaults) { merged[attrname] = defaults[attrname]; }
    for (var attrname in settings) { merged[attrname] = settings[attrname]; }
    return merged;
}
AT_Application.ModalBox = function(params, html) {
	var $j = jQuery;
	var defaults = {
		"handler": "append",
		"width": 500,
		"height": 600,
		"background": "#ffffff"
	};

	var destroy = $j("<a />")
		.attr("href","#")
		.css({"float": "right", "text-decoration": "none","font-size": 24 + "px"})
		.addClass("icon icon-cancel-circled2 unprintable")
		.delegate(function() {
		    $j(".at-modal-box").remove();
		    return false;
		},"click");

	var opt = AT_Application.Extend(defaults,params);

    var modal = $j("<div />").addClass("at-modal-box")
    .css({
    	"width": opt.width+"px",
    	"height": opt.height+"px",
    	"left": ($j(window).outerWidth()/2) - (opt.width*1)/2,
    	"top": ($j(window).outerHeight()/2) - (opt.height*1)/2,
    	"background": opt.background
    });
    var modalInner = $j("<div />").addClass("modal-wrapper");
    var modalContainer = $j("<div />").addClass("modal-container container content")
    .css({
    	"width": opt.width+"px",
    	"height": opt.height+"px"
    });

    $j(".at-modal-box").remove();

    modalInner.appendTo(modal);
    modalContainer.append(destroy).append(html).appendTo(modalInner);

    modal.prependTo("body");
}

jQuery(document).ready(function(){
	/*jQuery('.slider').mobilyslider({
		content: '.sliderContent',
		children: 'div',
		transition: 'horizontal',
		animationSpeed: 500,
		autoplay: false,
		autoplaySpeed: 3000,
		pauseOnHover: false,
		bullets: true,
		arrows: false,
		arrowsHide: true,
		prev: 'prev',
		next: 'next',
		animationStart: function(){},
		animationComplete: function(){}
	});*/
	var $ = jQuery;


	$('.video iframe, .video').fitVids();
	$('a.email_link_noreplace').nospam({
		replaceText: false,
		filterLevel: 'normal'
	});
	$('a.email_link_replace').nospam({
		replaceText: true,
		filterLevel: 'normal'
	});

	$(".slider_1").each(function(e){
		var $this = $(this);
		$this.bxSlider(
			$this.data('settings')
	  	);
  	});
  	$('.slider_2').bxSlider({
   	 	slideWidth: 940,
    	minSlides: 1,
    	maxSlides: 1,
    	slideMargin: 0,
    	controls : false
  	});

	// jQuery('.select_1').selectik({
	// 	width: 'auto',
	// 	maxItems: 5,
	// 	customScroll: 1,
	// 	speedAnimation: 100,
	// 	smartPosition: false
	// });
	// jQuery('.select_2').selectik({
	// 	width: 'auto',
	// 	maxItems: 5,
	// 	customScroll: 1,
	// 	speedAnimation: 100,
	// 	smartPosition: false
	// });
	// jQuery('.select_3').selectik({
	// 	width: 'auto',
	// 	maxItems: 5,
	// 	customScroll: 1,
	// 	speedAnimation: 100,
	// 	smartPosition: false
	// });
	// jQuery('.select_4').selectik({
	// 	width: 'auto',
	// 	maxItems: 5,
	// 	customScroll: 1,
	// 	speedAnimation: 100,
	// 	smartPosition: false
	// });
	// jQuery('.select_5').selectik({
	// 	containerClass: 'custom-select sell_select',
	// 	width: 205,
	// 	maxItems: 5,
	// 	customScroll: 1,
	// 	speedAnimation: 100,
	// 	smartPosition: false
	// });

	/*$('.recent_carousel').elastislide({
		imageW 		: 220,
		border		: 0,
		margin		: 20
	});*/

	$(".recent_carousel").each(function(e){
		var $this = $(this);
		$this.bxSlider(
			$this.data('settings')
	  	);
  	});
	$(".select2").each(function(e){
		$(this).select2();
  	});

	/*$('.tabs_carousel').elastislide({
		imageW 		: 150,
		border		: 0,
		margin		: 20
	});*/
	var slider=$('.tabs_carousel').bxSlider({
   	 	slideWidth: 150,
    	minSlides: 1,
    	maxSlides: 4,
    	slideMargin: 20,
    	controls : true,
    	pager : false,
    	infiniteLoop: false
  	});
	$('ul.tabs').delegate('li:not(.current)', 'click', function() {
	    $(this).addClass('current').siblings().removeClass('current').parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
	});
	$(".video_box .preview a").fancybox({
		'width'				: '75%',
		'height'			: '75%',
		'autoScale'			: false,
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic',
		'type'				: 'iframe'
	});
	$("a[rel=car_group],a[rel=zoom_image]").fancybox({
		'autoScale'			: true,
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic'
	});

	var austDay = new Date();
	austDay = new Date(austDay.getFullYear(),austDay.getMonth(), austDay.getDate()+36,austDay.getHours()+7,austDay.getMinutes()+24,austDay.getSeconds()+59);
	$('#counter').countdown({
		until: austDay, 
		format: 'dHMS'
	});

	$('.drop_list .selected span').click(function(e){
		e.preventDefault();
		$(this).parent('.selected').addClass('active');
		var container = $(this).parent('.selected');
		$(document).mouseup(function (e) {
		    if (container.has(e.target).length === 0){
		        container.removeClass('active');
		    }
		});
	});
	$( ".accordion h4" ).click(function(){
		$('.accordion .acc_box').each(function(){
			$(this).removeClass('active').children('div').slideUp(500);
		})
		$(this).siblings().addClass('active').children('div').slideDown(500);
	});
	$('#navigation span').click(function(){
		if($(this).siblings().hasClass('open')){
			$(this).siblings().removeClass('open').children('ul').slideUp('slow',function(){
				$this = $(this);
				setTimeout(function(){$this.attr('style','')},800);
			});
		}
		else{
			$(this).siblings().addClass('open').children('ul').slideDown('slow');
		}
	});
});

/* 
 * No Spam (1.3)
 * by Mike Branski (www.leftrightdesigns.com)
 * mikebranski@gmail.com
 *
 * Copyright (c) 2008 Mike Branski (www.leftrightdesigns.com)
 * Licensed under GPL (www.leftrightdesigns.com/library/jquery/nospam/gpl.txt)
 *
 * NOTE: This script requires jQuery to work.  Download jQuery at www.jquery.com
 *
 * Thanks to Bill on the jQuery mailing list for the double slash idea!
 *
 * CHANGELOG:
 * v 1.3   - Added support for e-mail addresses with multiple dots (.) both before and after the at (@) sign
 * v 1.2.1 - Included GPL license
 * v 1.2   - Finalized name as No Spam (was Protect Email)
 * v 1.1   - Changed switch() to if() statement
 * v 1.0   - Initial release
 *
 */

jQuery.fn.nospam = function(settings) {
	settings = jQuery.extend({
		replaceText: false, 	// optional, accepts true or false
		filterLevel: 'normal' 	// optional, accepts 'low' or 'normal'
	}, settings);
	
	return this.each(function(){
		e = null;
		if(settings.filterLevel == 'low') { // Can be a switch() if more levels added
			if(jQuery(this).is('a[rel]')) {
				e = jQuery(this).attr('rel').replace('//', '@').replace(/\//g, '.');
			} else {
				e = jQuery(this).text().replace('//', '@').replace(/\//g, '.');
			}
		} else { // 'normal'
			if(jQuery(this).is('a[rel]')) {
				e = jQuery(this).attr('rel').split('').reverse().join('').replace('//', '@').replace(/\//g, '.');
			} else {
				e = jQuery(this).text().split('').reverse().join('').replace('//', '@').replace(/\//g, '.');
			}
		}
		if(e) {
			if(jQuery(this).is('a[rel]')) {
				jQuery(this).attr('href', 'mailto:' + e);
				if(settings.replaceText) {
					jQuery(this).text(e);
				}
			} else {
				jQuery(this).text(e);
			}
		}
	});
};
