
window.TinyMCE = window.tinymce = require('tinymce');
require('./commons/multilingual');
require('./facilitador_tinymce');
window.facilitadorTinyMCE = require('./facilitador_tinymce_config');
require('./facilitador_ace_editor');

Vue.component('admin-menu', require('./components/admin_menu.vue').default);

var admin_menu = new Vue({
    el: '#adminmenu',
});

require('./commons/app');

$(document).ready(function () {

    var appContainer = $(".app-container"),
        fadedOverlay = $('.fadetoblack'),
        hamburger = $('.hamburger');

    $('.side-menu').perfectScrollbar();

    $('#facilitador-loader').fadeOut();

    $(".hamburger, .navbar-expand-toggle").on('click', function () {
        appContainer.toggleClass("expanded");
        $(this).toggleClass('is-active');
        if ($(this).hasClass('is-active')) {
            window.localStorage.setItem('facilitador.stickySidebar', true);
        } else {
            window.localStorage.setItem('facilitador.stickySidebar', false);
        }
    });

    $('select.select2').select2({width: '100%'});
    $('select.select2-ajax').each(function() {
        $(this).select2({
            width: '100%',
            ajax: {
                url: $(this).data('get-items-route'),
                data: function (params) {
                    var query = {
                        search: params.term,
                        type: $(this).data('get-items-field'),
                        method: $(this).data('method'),
                        id: $(this).data('id'),
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(this).on('select2:select',function(e){
            var data = e.params.data;
            if (data.id == '') {
                // "None" was selected. Clear all selected options
                $(this).val([]).trigger('change');
            } else {
                $(e.currentTarget).find("option[value='" + data.id + "']").attr('selected','selected');
            }
        });

        $(this).on('select2:unselect',function(e){
            var data = e.params.data;
            $(e.currentTarget).find("option[value='" + data.id + "']").attr('selected',false);
        });
    });
    $('select.select2-taggable').select2({
        width: '100%',
        tags: true,
        createTag: function(params) {
            var term = $.trim(params.term);

            if (term === '') {
                return null;
            }

            return {
                id: term,
                text: term,
                newTag: true
            }
        }
    }).on('select2:selecting', function(e) {
        var $el = $(this);
        var route = $el.data('route');
        var label = $el.data('label');
        var errorMessage = $el.data('error-message');
        var newTag = e.params.args.data.newTag;

        if (!newTag) return;

        $el.select2('close');

        $.post(route, {
            [label]: e.params.args.data.text,
            _tagging: true,
        }).done(function(data) {
            var newOption = new Option(e.params.args.data.text, data.data.id, false, true);
            $el.append(newOption).trigger('change');
        }).fail(function(error) {
            toastr.error(errorMessage);
        });

        return false;
    }).on('select2:select', function (e) {
        if (e.params.data.id == '') {
            // "None" was selected. Clear all selected options
            $(this).val([]).trigger('change');
        }
    });


    $(".side-menu .nav .dropdown").on('show.bs.collapse', function () {
        return $(".side-menu .nav .dropdown .collapse").collapse('hide');
    });

    $('.panel-collapse').on('hide.bs.collapse', function(e) {
        var target = $(e.target);
        if (!target.is('a')) {
            target = target.parent();
        }
        if (!target.hasClass('collapsed')) {
            return;
        }
        e.stopPropagation();
        e.preventDefault();
    });

    $(document).on('click', '.panel-heading a.panel-action[data-toggle="panel-collapse"]', function (e) {
        e.preventDefault();
        var $this = $(this);

        // Toggle Collapse
        if (!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.removeClass('facilitador-angle-up').addClass('facilitador-angle-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.removeClass('facilitador-angle-down').addClass('facilitador-angle-up');
        }
    });

    //Toggle fullscreen
    $(document).on('click', '.panel-heading a.panel-action[data-toggle="panel-fullscreen"]', function (e) {
        e.preventDefault();
        var $this = $(this);
        if (!$this.hasClass('facilitador-resize-full')) {
            $this.removeClass('facilitador-resize-small').addClass('facilitador-resize-full');
        } else {
            $this.removeClass('facilitador-resize-full').addClass('facilitador-resize-small');
        }
        $this.closest('.panel').toggleClass('is-fullscreen');
    });

});
