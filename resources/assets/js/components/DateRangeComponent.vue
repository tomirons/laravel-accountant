<script type="text/ecmascript-6">
    import moment from 'moment';
    import datePicker from 'vue-bootstrap-datetimepicker';

    export default {
        components: {datePicker},
        data() {
            return {
                start: null,
                end: null,
                show: false,
                max: moment()
            }
        },
        created() {
            this.$http.get('/accountant/filters')
                .then(response => {
                    this.start = moment(response.data.start);
                    this.end = moment(response.data.end);
                });
        },
        methods: {
            applyFilter: function () {
                if (this.end.isBefore(this.start)) {
                    $.notify({
                        message: "End date <strong>must</strong> be after the start date, therefore, the end date has been reset."
                    },{
                        type: 'danger'
                    });
                    this.end = moment();
                    return;
                }

                this.$http.post('/accountant/data', {
                    start: this.start.unix(),
                    end: this.end.unix()
                })
                .then(response => {
                    this.setData(response.data);
                    this.toggle();
                });
            },
            setData: function (data) {
                this.$parent.setData(data);
            },
            toggle: function () {
                this.show = !this.show;
            }
        },
        watch: {
            'end': function () {
                this.applyFilter();
            },
            'start': function () {
                this.applyFilter();
            }
        }
    }
</script>

<template>
    <div class="row data-actions" v-cloak>
        <div class="col-sm-4 col-sm-offset-8" v-if="show">
            <div class="col-sm-11">
                <div class="input-group">
                    <date-picker class="text-center" v-model="start" :config="{ format: 'MM/DD/YYYY', maxDate: max }"></date-picker>
                    <span class="input-group-addon middle">to</span>
                    <date-picker class="text-center" v-model="end" :config="{ format: 'MM/DD/YYYY', maxDate: max }"></date-picker>
                </div>
            </div>
            <button class="btn btn-clear" v-on:click="toggle()"><i class="fa fa-close"></i></button>
        </div>
        <div class="col-sm-5 col-sm-offset-7" v-else-if="start && end">
            <div class="pull-right">
                <span class="filtered-range hidden-sm hidden-xs">Show between <strong>{{ start.format('MM/DD/YYYY') }}</strong> and <strong>{{ end.format('MM/DD/YYYY') }}</strong></span>
                <button class="btn btn-clear" v-on:click="toggle()"><i class="fa fa-filter"></i></button>
                <a href="/accountant/refresh" class="btn btn-clear"><i class="fa fa-refresh"></i></a>
            </div>
        </div>
    </div>
</template>