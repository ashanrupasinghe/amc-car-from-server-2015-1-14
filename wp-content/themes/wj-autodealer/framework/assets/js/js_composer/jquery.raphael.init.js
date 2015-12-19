/*
 * Raphael 1.5.2 - JavaScript Vector Library Init
 *
 */

var o = {
    init: function(){
        this.diagram();
    },
    random: function(l, u){
        return Math.floor((Math.random()*(u-l+1))+l);
    },
    diagram: function(){
        var t = jQuery('#im_skill_diagram'), 
                dimension = t.attr('data-dimension'),
                radius = (dimension/2),
                center_color = t.attr('data-circle-color'),
                default_text_color = t.attr('data-default-text-color'),
                default_text = t.attr('data-default-text');
        var r = Raphael('im_skill_diagram', dimension, dimension),
            rad = 72,
            defaultText = default_text,
            speed = 250;
        
        r.circle(radius, radius, 80).attr({ stroke: 'none', fill: center_color });
        
        var title = r.text(radius, radius, defaultText).attr({
            font: "22px helvetica",
            fill: default_text_color
        }).toFront();
        
        r.customAttributes.arc = function(value, color, rad){
            var v = 3.6*value,
                alpha = v == 360 ? 359.99 : v,
                random = o.random(91, 240),
                a = (random-alpha) * Math.PI/180,
                b = random * Math.PI/180,
                sx = radius + rad * Math.cos(b),
                sy = radius - rad * Math.sin(b),
                x = radius + rad * Math.cos(a),
                y = radius - rad * Math.sin(a),
                path = [['M', sx, sy], ['A', rad, rad, 0, +(alpha > 180), 1, x, y]];
            return { path: path, stroke: color }
        }

        
        
        jQuery('.at-skill-chart').find('.at-meter-arch').each(function(i){
            var t = jQuery(this), 
                color = t.find('.color').val(),
                value = t.find('.percent').val(),
                text = t.find('.name').val();
            
            rad += 27;  
            var z = r.path().attr({ arc: [value, color, rad], 'stroke-width': 28 });
            
            z.mouseover(function(){
                this.animate({ 'stroke-width': 50, opacity: 0.9, }, 800, 'elastic');
                if(Raphael.type != 'VML') //solves IE problem
                this.toFront();
                title.stop().animate({ opacity: 0 }, speed, '>', function(){
                    this.attr({ text: text + '\n' + value + '%' }).animate({ opacity: 1 }, speed, '<');
                });
            }).mouseout(function(){
                this.stop().animate({ 'stroke-width': 28, opacity: 1 }, speed*4, 'elastic');
                title.stop().animate({ opacity: 0 }, speed, '>', function(){
                    title.attr({ text: defaultText }).animate({ opacity: 1 }, speed, '<');
                }); 
            });
        });
        
    }
}

jQuery(function(){ 
	if(jQuery('.at_skill_diagram').length > 0) {
		o.init();
	}
});