require('./bootstrap')

Vue.component('dashboard-component', require('./components/DashboardComponent.vue'));

const app = new Vue({
    el: '#app'
});

$(function() {
    $(".clickable").click(function() {
        window.location = $(this).data("href");
    });
    $('[data-toggle="tooltip"]').tooltip();
});