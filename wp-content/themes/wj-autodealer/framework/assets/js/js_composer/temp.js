alert(1);


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


// (function($) {
// 	//if ( typeof vc !== "undefined" ) {
// 	    var Shortcodes = vc.shortcodes;
// 	    window.ImTracksView = vc.shortcode_view.extend({
// 	        new_tab_adding:false,
// 	        events:{
// 	            'click .add_tab':'addTab',
// 	            'click > .controls .column_delete':'deleteShortcode',
// 	            'click > .controls .column_edit':'editElement',
// 	            'click > .controls .column_clone':'clone'
// 	        },
// 	        initialize:function (params) {
// 	            window.ImTracksView.__super__.initialize.call(this, params);
// 	            _.bindAll(this, 'stopSorting');
// 	        },
// 	        render:function () {
// 	            window.ImTracksView.__super__.render.call(this);
// 	            this.$tabs = this.$el.find('.wpb_tracks_holder');
// 	            this.createAddTabButton();
// 	            return this;
// 	        },
// 	        ready:function (e) {
// 	            window.ImTracksView.__super__.ready.call(this, e);
// 	        },
// 	        createAddTabButton:function () {
// 	            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
// 	            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
// 	            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>').appendTo(this.$tabs.find(".tracks_controls"));
// 	        },
// 	        addTab:function (e) {
// 	            e.preventDefault();
// 	            this.new_tab_adding = true;
// 	            var tab_title = this.model.get('shortcode') === 'vc_tour' ? window.i18nLocale.slide : window.i18nLocale.tab,
// 	                tabs_count = this.$tabs.find('[data-element_type=im_track]').length,
// 	                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
// 	            vc.shortcodes.create({shortcode:'im_track', params:{title:tab_title, tab_id:tab_id}, parent_id:this.model.id});
// 	            return false;
// 	        },
// 	        stopSorting:function (event, ui) {
// 	            var shortcode;
// 	            this.$tabs.find('ul.tracks_controls li:not(.add_tab_block)').each(function (index) {
// 	                var href = $(this).find('a').attr('href').replace("#", "");
// 	                $('#' + href).appendTo(this.$tabs);
// 	                shortcode = vc.shortcodes.get($('[id=' + $(this).attr('aria-controls') + ']').data('model-id'));
// 	                vc.storage.lock();
// 	                shortcode.save({'order':$(this).index()}); // Optimize
// 	            });
// 	            shortcode.save();
// 	        },
// 	        changedContent:function (view) {
// 	            var params = view.model.get('params');
// 	            if (!this.$tabs.hasClass('ui-tabs')) {
// 	                this.$tabs.tabs({
// 	                    select:function (event, ui) {
// 	                        if ($(ui.tab).hasClass('add_tab')) {
// 	                            return false;
// 	                        }
// 	                        return true;
// 	                    }
// 	                });
// 	                this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs);
// 	                this.$tabs.find(".ui-tabs-nav").sortable({
// 	                    axis:(this.$tabs.closest('[data-element_type]').data('element_type') == 'vc_tour' ? 'y' : 'x'),
// 	                    stop:this.stopSorting,
// 	                    items:"> li:not(.add_tab_block)"
// 	                });
// 	            }
// 	            if (view.model.get('cloned') == true) {
// 	                var cloned_from = view.model.get('cloned_from'),
// 	                    $after_tab = $('[href=#tab-' + cloned_from.params.tab_id + ']', this.$content).parent(),
// 	                    $new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertAfter($after_tab);
// 	                this.$tabs.tabs('refresh');
// 	                this.$tabs.tabs("option", 'active', $new_tab.index());
// 	            } else {
// 	                $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>")
// 	                    .insertBefore(this.$add_button);
// 	                this.$tabs.tabs('refresh');
// 	                this.$tabs.tabs("option", "active", this.new_tab_adding ? $('.ui-tabs-nav li', this.$content).length - 2 : 0);

// 	            }
// 	            this.new_tab_adding = false;
// 	        },
// 	        cloneModel:function (model, parent_id, save_order) {
// 	            var shortcodes_to_resort = [],
// 	                new_order = _.isBoolean(save_order) && save_order == true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
// 	                model_clone,
// 	                new_params = _.extend({}, model.get('params'));
// 	            if (model.get('shortcode') === 'im_track') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.tabs('length') + '-' + Math.floor(Math.random() * 11)});
// 	            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'im_track' ? false : true), cloned_from:model.toJSON(), params:new_params});
// 	            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
// 	                this.cloneModel(shortcode, model_clone.get('id'), true);
// 	            }, this);
// 	            return model_clone;
// 	        }
// 	    });

// 	    window.ImTrackView = window.VcColumnView.extend({
// 	        render:function () {
// 	            var params = this.model.get('params');
// 	            window.ImTrackView.__super__.render.call(this);
// 	            this.id = 'tab-' + params.tab_id;
// 	            this.$el.attr('id', this.id);
// 	            return this;
// 	        },
// 	        ready:function (e) {
// 	            window.ImTrackView.__super__.ready.call(this, e);
// 	            this.$tabs = this.$el.closest('.wpb_tracks_holder');
// 	            var params = this.model.get('params');
// 	            return this;
// 	        },
// 	        changeShortcodeParams:function (model) {
// 	            var params = model.get('params');
// 	            window.VcAccordionTabView.__super__.changeShortcodeParams.call(this, model);
// 	            if (_.isObject(params) && _.isString(params.title) && _.isString(params.tab_id)) {
// 	                $('.ui-tabs-nav [href=#tab-' + params.tab_id + ']').text(params.title);
// 	            }
// 	        },
// 	        deleteShortcode:function (e) {
// 	            if (_.isObject(e)) e.preventDefault();
// 	            var answer = confirm(window.i18nLocale.press_ok_to_delete_section);
// 	            if (answer !== true) return false;
// 	            this.model.destroy();
// 	            var params = this.model.get('params'),
// 	                current_tab_index = $('[href=#tab-' + params.tab_id + ']', this.$tabs).parent().index();
// 	            $('[href=#tab-' + params.tab_id + ']').parent().remove();
// 	            this.$tabs.tabs('refresh');
// 	            if (current_tab_index < this.$tabs.find('.ui-tabs-nav li').length) {
// 	                this.$tabs.tabs("option", "active", current_tab_index);
// 	            } else {
// 	                this.$tabs.tabs("option", "active", 1);
// 	            }
// 	        },
// 	        cloneModel:function (model, parent_id, save_order) {
// 	            var shortcodes_to_resort = [],
// 	                new_order = _.isBoolean(save_order) && save_order == true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
// 	                new_params = _.extend({}, model.get('params'));
// 	            if (model.get('shortcode') === 'im_track') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=im_track]').length + '-' + Math.floor(Math.random() * 11)});
// 	            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
// 	            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
// 	                this.cloneModel(shortcode, model_clone.id, true);
// 	            }, this);
// 	            return model_clone;
// 	        }
// 	    });
// 	    /**
// 	     * Old version of tabs for Wordpress 3.5
// 	     * @deprecated
// 	     * @type {*}
// 	     */
// 	    window.ImTracksView35 = vc.shortcode_view.extend({
// 	        new_tab_adding:false,
// 	        events:{
// 	            'click .add_tab':'addTab',
// 	            'click > .controls .column_delete':'deleteShortcode',
// 	            'click > .controls .column_edit':'editElement',
// 	            'click > .controls .column_clone':'clone'
// 	        },
// 	        initialize:function (params) {
// 	            window.ImTracksView.__super__.initialize.call(this, params);
// 	            _.bindAll(this, 'stopSorting');
// 	        },
// 	        render:function () {
// 	            window.ImTracksView.__super__.render.call(this);
// 	            this.$tabs = this.$el.find('.wpb_tracks_holder');
// 	            this.createAddTabButton();
// 	            return this;
// 	        },
// 	        ready:function (e) {
// 	            window.ImTracksView.__super__.ready.call(this, e);
// 	        },
// 	        createAddTabButton:function () {
// 	            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
// 	            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
// 	            this.$tabs.find(".tracks_controls").append('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>');
// 	        },
// 	        addTab:function (e) {
// 	            e.preventDefault();
// 	            this.new_tab_adding = true;
// 	            var tab_title = this.model.get('shortcode') === 'vc_tour' ? window.i18nLocale.slide : window.i18nLocale.tab,
// 	                tabs_count = this.$tabs.tabs("length"),
// 	                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
// 	            vc.shortcodes.create({shortcode:'im_track', params:{title:tab_title, tab_id:tab_id}, parent_id:this.model.id});
// 	            return false;
// 	        },
// 	        stopSorting:function (event, ui) {
// 	            var shortcode;
// 	            this.$tabs.find('ul.tracks_controls li:not(.add_tab_block)').each(function (index) {
// 	                var href = $(this).find('a').attr('href').replace("#", "");
// 	                $('#' + href).appendTo(this.$tabs);
// 	                shortcode = vc.shortcodes.get($('[id=' + $(this).attr('aria-controls') + ']').data('model-id'));
// 	                vc.storage.lock();
// 	                shortcode.save({'order':$(this).index()}); // Optimize
// 	            });
// 	            shortcode.save();
// 	        },
// 	        changedContent:function (view) {
// 	            var params = view.model.get('params');
// 	            if (!this.$tabs.hasClass('ui-tabs')) {
// 	                this.$tabs.tabs({
// 	                    select:function (event, ui) {
// 	                        if ($(ui.tab).hasClass('add_tab')) {
// 	                            return false;
// 	                        }
// 	                        return true;
// 	                    }
// 	                });
// 	                this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs);
// 	                this.$tabs.find(".ui-tabs-nav").sortable({
// 	                    axis:(this.$tabs.closest('[data-element_type]').data('element_type') == 'vc_tour' ? 'y' : 'x'),
// 	                    stop:this.stopSorting,
// 	                    items:"> li:not(.add_tab_block)"
// 	                });
// 	            }
// 	            if (view.model.get('cloned') == true) {
// 	                var cloned_from = view.model.get('cloned_from');
// 	                var index = $('#tab-' + cloned_from.params.tab_id).index();
// 	                this.$tabs.tabs("add", "#tab-" + params.tab_id, params.title, index - 1);
// 	                this.$tabs.tabs("select", index - 1);
// 	            } else {
// 	                this.$tabs.tabs("add", "#tab-" + params.tab_id, params.title, this.$tabs.tabs("length") - 1);
// 	                this.$tabs.tabs("select", this.new_tab_adding ? (this.$tabs.tabs("length") - 2) : 0);
// 	            }

// 	            this.new_tab_adding = false;
// 	        },
// 	        cloneModel:function (model, parent_id, save_order) {
// 	            var shortcodes_to_resort = [],
// 	                new_order = _.isBoolean(save_order) && save_order == true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
// 	                model_clone,
// 	                new_params = _.extend({}, model.get('params'));
// 	            if (model.get('shortcode') === 'im_track') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.tabs('length') + '-' + Math.floor(Math.random() * 11)});
// 	            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'im_track' ? false : true), cloned_from:model.toJSON(), params:new_params});
// 	            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
// 	                this.cloneModel(shortcode, model_clone.get('id'), true);
// 	            }, this);
// 	            return model_clone;
// 	        }
// 	    });
// 	    /**
// 	     * Old version of tab for Wordpress 3.5
// 	     * @deprecated
// 	     * @type {*}
// 	     */
// 	    window.ImTrackView35 = window.VcColumnView.extend({
// 	        render:function () {
// 	            var params = this.model.get('params');
// 	            window.ImTrackView.__super__.render.call(this);
// 	            this.id = 'tab-' + params.tab_id;
// 	            this.$el.attr('id', this.id);
// 	            return this;
// 	        },
// 	        ready:function (e) {
// 	            window.ImTrackView.__super__.ready.call(this, e);
// 	            this.$tabs = this.$el.closest('.wpb_tracks_holder');
// 	            var params = this.model.get('params');
// 	            return this;
// 	        },
// 	        changeShortcodeParams:function (model) {
// 	            var params = model.get('params');
// 	            window.VcAccordionTabView.__super__.changeShortcodeParams.call(this, model);
// 	            if (_.isObject(params) && _.isString(params.title) && _.isString(params.tab_id)) {
// 	                $('.ui-tabs-nav [href=#tab-' + params.tab_id + ']').text(params.title);
// 	            }
// 	        },
// 	        deleteShortcode:function (e) {
// 	            if (_.isObject(e)) e.preventDefault();
// 	            var answer = confirm(window.i18nLocale.press_ok_to_delete_section);
// 	            if (answer !== true) return false;
// 	            this.model.destroy();
// 	            var params = this.model.get('params');
// 	            this.$tabs.tabs("remove", $('[href=#tab-' + params.tab_id + ']').parent().index());
// 	        },
// 	        cloneModel:function (model, parent_id, save_order) {
// 	            var shortcodes_to_resort = [],
// 	                new_order = _.isBoolean(save_order) && save_order == true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
// 	                new_params = _.extend({}, model.get('params'));
// 	            if (model.get('shortcode') === 'im_track') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.tabs('length') + '-' + Math.floor(Math.random() * 11)});
// 	            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
// 	            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
// 	                this.cloneModel(shortcode, model_clone.id, true);
// 	            }, this);
// 	            return model_clone;
// 	        }
// 	    });
// 	    /**
// 	     * Append tab_id tempalate filters
// 	     */

// 	    vc.addTemplateFilter(function (string) {
// 	        var random_id = VCS4() + '-' + VCS4();
// 	        return string.replace(/tab\_id\=\"([^\"]+)\"/g, 'tab_id="$1' + random_id + '"');
// 	    });	//}
// })(window.jQuery);

	function wpbAccordionGenerateShortcodeCallBack(current_top_level, inner_element_count) {
	    var tab_title = current_top_level.find(".im-accordion-group:eq("+inner_element_count+") h3 a").text();
	    var tab_icon = current_top_level.find(".im-accordion-group:eq("+inner_element_count+") h3 i").attr('class');
	    if(tab_icon == undefined) {tab_icon = ''}
	    output = '[vc_accordion_tab icon="'+tab_icon+'" title="'+tab_title+'"] %inner_shortcodes [/vc_accordion_tab]';
	    return output;
	}
