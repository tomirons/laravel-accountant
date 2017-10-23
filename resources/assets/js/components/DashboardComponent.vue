<script type="text/ecmascript-6">
    import { Line } from 'vue-chartjs';
    import FilterRange from '../components/FilterComponent.vue';

    export default {
        components: {
            FilterRange,
            'line-chart': {
                extends: Line,
                props: ['data', 'options', 'dollar'],
                watch: {
                    data: function () {
                        if (this._chart) {
                            this._chart.destroy();
                        }

                        var options;

                        if (this.dollar) {
                            options = {
                                legend: {
                                    display: false
                                },
                                tooltips: {
                                    callbacks: {
                                        label: function (tooltipItems, data) {
                                            return "$" + tooltipItems.yLabel.toFixed(2);
                                        }
                                    }
                                }
                            };
                        } else {
                            options = {
                                legend: {
                                    display: false
                                }
                            };
                        }

                        this.renderChart(this.data, options)
                    }
                }
            }
        },
        data() {
            return {
                data: null
            }
        },
        methods: {
            setData: function (data) {
                this.data = data;
            }
        }
    }
</script>

<template>
    <div>
        <filter-range></filter-range>
        <div class="row" v-if="data" v-cloak>
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <h3 class="panel-title">Gross Volume
                            <span class="stat">${{ data.charges.total }}</span>
                        </h3>
                        <line-chart :data="data.charts.gross" :dollar="true" :height="200"></line-chart>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <h3 class="panel-title">Successful Payments
                            <span class="stat">{{ data.charges.successful }}</span>
                        </h3>
                        <line-chart :data="data.charts.payments" :height="200"></line-chart>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <h3 class="panel-title">Total Refunded
                            <span class="stat">${{ data.charges.refunded }}</span>
                        </h3>
                        <line-chart :data="data.charts.refunded" :dollar="true" :height="200"></line-chart>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <h3 class="panel-title">New Customers
                            <span class="stat">{{ data.customers.count }}</span>
                        </h3>
                        <line-chart :data="data.charts.customers" :height="200"></line-chart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>