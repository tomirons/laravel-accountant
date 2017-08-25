window._ = require('lodash');

window.$ = window.jQuery = require('jquery');

require('bootstrap-sass');

require('chartjs');

$(document).ready(function($) {
    $(".clickable").click(function() {
        window.location = $(this).data("href");
    });
});