/* =========================================================
 * composer-custom-views.js v1.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer ViewModel objects for shortcodes with custom
 * functionality.
 * ========================================================= */


(function ($) {
    var Shortcodes = vc.shortcodes;

    // window.VcAccordionView = vc.shortcode_view.extend({
    //     adding_new_tab:false,
    //     events:{
    //         'click .add_tab':'addTab',
    //         'click > .controls .column_delete':'deleteShortcode',
    //         'click > .controls .column_edit':'editElement',
    //         'click > .controls .column_clone':'clone'
    //     },
    //     render:function () {
    //         window.VcAccordionView.__super__.render.call(this);
    //         this.$content.sortable({
    //             axis:"y",
    //             handle:"h3",
    //             stop:function (event, ui) {
    //                 // IE doesn't register the blur when sorting
    //                 // so trigger focusout handlers to remove .ui-state-focus
    //                 ui.item.prev().triggerHandler("focusout");
    //                 $(this).find('> .wpb_sortable').each(function () {
    //                     var shortcode = $(this).data('model');
    //                     shortcode.save({'order':$(this).index()}); // Optimize
    //                 });
    //             }
    //         });
    //         return this;
    //     },
    //     changeShortcodeParams:function (model) {
    //         window.VcAccordionView.__super__.changeShortcodeParams.call(this, model);
    //         var collapsible = _.isString(this.model.get('params').collapsible) && this.model.get('params').collapsible === 'yes' ? true : false;
    //         if (this.$content.hasClass('ui-accordion')) {
    //             this.$content.accordion("option", "collapsible", collapsible);
    //         }
    //     },
    //     changedContent:function (view) {
    //         if (this.$content.hasClass('ui-accordion')) this.$content.accordion('destroy');
    //         var collapsible = _.isString(this.model.get('params').collapsible) && this.model.get('params').collapsible === 'yes' ? true : false;
    //         this.$content.accordion({
    //             header:"h3",
    //             navigation:false,
    //             autoHeight:true,
    //             heightStyle: "content",
    //             collapsible:collapsible,
    //             active:this.adding_new_tab === false && view.model.get('cloned') !== true ? 0 : view.$el.index()
    //         });
    //         this.adding_new_tab = false;
    //     },
    //     addTab:function (e) {
    //         this.adding_new_tab = true;
    //         e.preventDefault();
    //         vc.shortcodes.create({shortcode:'vc_accordion_tab', params:{title:window.i18nLocale.section}, parent_id:this.model.id});
    //     },
    //     _loadDefaults:function () {
    //         window.VcAccordionView.__super__._loadDefaults.call(this);
    //     }
    // });


    window.VcImHero = window.vc.shortcode_view.extend({
        events:{
            'click > [data-element_type] > .controls .column_add':'createRow',
            'click > [data-element_type] > .controls .column_edit':'editElement',
            'click > [data-element_type] > .controls .column_clone':'clone',
            'click > [data-element_type] > .controls .column_delete':'deleteShortcode',
            'click > [data-element_type] > .wpb_element_wrapper > .empty_container':'addToEmpty'
        },
        setContent:function () {
            this.$content = this.$el.find('> [data-element_type] > .wpb_element_wrapper > .vc_container_for_children');
        },
        changeShortcodeParams:function (model) {
            var params = model.get('params');
            window.VcImHero.__super__.changeShortcodeParams.call(this, model);
            if (_.isObject(params) && _.isString(params.title)) {
                this.$el.find('> h3 .tab-label').text(params.title);
            }
        },
        // dropButton:function (event, ui) {
        //     // if (ui.draggable.is('#wpb-add-new-element')) {
        //     //     new vc.element_block_view({model:{position_to_add:'end'}}).show(this);
        //     // } else if (ui.draggable.is('#wpb-add-new-row')) {
        //         this.createRow();
        //     // }
        // },
        setEmpty:function () {
            $('> [data-element_type]', this.$el).addClass('empty_column');
            this.createRow();
            this.$content.addClass('empty_container');
        },
        unsetEmpty:function () {
            $('> [data-element_type]', this.$el).removeClass('empty_column');
            this.$content.removeClass('empty_container');
        },
        /**
         * Create row
         */
        createRow:function (e) {
            e.preventDefault();
            var row = Shortcodes.create({shortcode:'vc_row_inner', parent_id:this.model.id});
            Shortcodes.create({shortcode:'vc_column_inner', params:{width:'1/1'}, parent_id:row.id });
            return row;
        }
    });

})(window.jQuery);