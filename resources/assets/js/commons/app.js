window.jQuery = window.$ = $ = require('jquery');
window.Vue = require('vue');
window.perfectScrollbar = require('perfect-scrollbar/jquery')($);
window.Cropper = require('cropperjs');
window.Cropper = 'default' in window.Cropper ? window.Cropper['default'] : window.Cropper;
window.toastr = require('toastr');
window.DataTable = require('datatables');
require('datatables-bootstrap3-plugin/media/js/datatables-bootstrap3');
window.EasyMDE = require('easymde');
require('dropzone');
require('jquery-match-height');
require('bootstrap-toggle');
require('nestable2');
require('bootstrap');
require('bootstrap-switch');
require('select2');
require('eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker');
var brace = require('brace');
require('brace/mode/json');
require('brace/theme/github');
require('./slugify');
window.TinyMCE = window.tinymce = require('tinymce');
require('./multilingual');

window.helpers = require('./helpers.js');


// Videos
// import Vue from 'vue'
// import VueVideoPlayer from 'vue-video-player'
// // require videojs style
// import 'video.js/dist/video-js.css'
// // import 'vue-video-player/src/custom-theme.css'
// Vue.use(VueVideoPlayer, /* {
//   options: global default options,
//   events: global videojs events
// } */)
import VueCoreVideoPlayer from 'vue-core-video-player'
//...
Vue.use(VueCoreVideoPlayer)


jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});

$(document).ready(function () {


    $('.match-height').matchHeight();

    $('.datatable').DataTable({
        "dom": '<"top"fl<"clear">>rt<"bottom"ip<"clear">>'
    });

    $('.datepicker').datetimepicker();

    // Save shortcut
    $(document).keydown(function (e) {
        if ((e.metaKey || e.ctrlKey) && e.keyCode == 83) { /*ctrl+s or command+s*/
            $(".btn.save").click();
            e.preventDefault();
            return false;
        }
    });

    /********** MARKDOWN EDITOR **********/

    $('textarea.easymde').each(function () {
        var easymde = new EasyMDE({
            element: this
        });
        easymde.render();
    });

    /********** END MARKDOWN EDITOR **********/

});
