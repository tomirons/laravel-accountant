require('./bootstrap')

import datePicker from 'vue-bootstrap-datetimepicker';
import { Line } from 'vue-chartjs';

var dashboard = new Vue({
    el: '#dashboard',
    data: {
        data: null,
        filter: {
            start: null,
            end: null,
            show: false,
            max: moment().format('MM/DD/YYYY')
        }
    },
    components: {
        datePicker,
        'line-chart': {
            extends: Line,
            props: ['data', 'options'],
            watch: {
                data: function () {
                    if (this._chart) {
                        this._chart.destroy();
                    }

                    this.renderChart(this.data, {
                        legend: {
                            display: false
                        }
                    })
                }
            }
        }
    },
    methods: {
        applyFilter: function () {
            axios.post('/accountant/data', {
                start: this.filter.start.unix(),
                end: this.filter.end.unix()
            })
                .then(function (response) {
                    dashboard.setData(response.data);
                    dashboard.toggleFilter();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        setData: function (data) {
            this.data = data;
        },
        setFilters: function (filters) {
            this.filter.start = moment(filters.start);
            this.filter.end = moment(filters.end);
        },
        toggleFilter: function () {
            this.filter.show = !this.filter.show;
        }
    },
    created: function () {
        this.setFilters(filterData);
    },
    watch: {
        'filter.end': function () {
            this.applyFilter();
        },
        'filter.start': function () {
            this.applyFilter();
        }
    }
});

$(function() {
    $(".clickable").click(function() {
        window.location = $(this).data("href");
    });
    $('[data-toggle="tooltip"]').tooltip();
});