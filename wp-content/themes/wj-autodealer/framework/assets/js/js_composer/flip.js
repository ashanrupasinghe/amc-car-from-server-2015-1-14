!function($) {
    $('#wpb-elements-list-modal .flip-input-text').click(function(e){
        e.preventDefault();
        var $input = $(this).prev().find('.my_param_field'),
            text = $input.val();
        $input.val(text.split("").reverse().join(""));
    });
}(window.jQuery);