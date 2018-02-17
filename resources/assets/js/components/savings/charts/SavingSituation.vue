<template>
    <div>
        <vue-highcharts :options="options" ref="savingSituationChart"></vue-highcharts>
    </div>
</template>

<script>
    export default {
        props: {
            saving: {
                type: Object,
                required: true
            }
        },
        data() {
      		return{
        		options: {
                    title: {text: ''},
                    credits: {enabled: false},
                    xAxis: {categories: [this.saving.title]},
                    chart: {
                        type: 'column',
                        width: 380
                    },
                    yAxis: {
                        min: 0,
                        max: this.saving.target_amount,
                        title: {text: ''},
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: 'gray'
                            }
                        }
                    },
                    legend: {
                        align: 'right',
                        verticalAlign: 'bottom',
                        floating: false,
                        backgroundColor: 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y} <b>/</b> {point.stackTotal}<br>Objectif: ' + this.saving.target_amount
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {enabled: false}
                        }
                    },
                    series: []
        		}
      		}
    	},
        mounted() {
            this.fetchData();
        },
        watch: {
            saving: function (saving) {
                this.fetchData();
            }
        },
        methods: {
            fetchData() {
                let chart = this.$refs.savingSituationChart;
                chart.removeSeries();
                for (let i = 0; i <  this.saving.users.length; i ++) {
                    let user = this.saving.users[i];
                    chart.addSeries({
                        name: user.name,
                        data: [user.pivot.amount],
                        color: user.color
                    });
                }
            }
        }
    }
</script>