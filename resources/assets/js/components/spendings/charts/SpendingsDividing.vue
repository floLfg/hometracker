<template>
    <div>
        <vue-highcharts :options="options" ref="dividingChart"></vue-highcharts>
    </div>
</template>

<script>
    export default {
        props: {
            year: {
                type: String,
                required: true
            },
            month: {
                type: String,
                required: true
            }
        },
        data() {
      		return{
        		options: {
                    title: {text: ''},
                    subtitle: {text: ''},
                    yAxis: {title: {text: ''}},
                    credits: {enabled: false},
                    tooltip: {enabled: false},
                    legend: {enabled: false},
                    exporting: {enabled: false},
                    chart: {
                        type: 'pie',
                        width: 400
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Total: <b>{point.y} €</b>'
                    },
                    plotOptions: {
                        pie: {
                            shadow: false,
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                style: {
                                    color: 'black'
                                }
                            }
                        }
                    },
                    series: []
        		}
      		}
    	},
	    methods: {
	      	fetchData() {
                let params = '?month=' + parseInt(this.month) + '&year=' + parseInt(this.year);
                axios.get('api/charts/dividing' + params).then((response) => {
                    let series = {
                        name: 'Dépenses',
                        colorByPoint: true,
                        innerSize: '60%',
                        data: []
                    };

                    for (let i = 0; i <  response.data.length; i ++) {
                        let data = response.data[i];
                        let title = data.title || 'Inconnu';
                        let color = data.color || '#ccc';
                        series.data.push({
                            name: title,
                            y: data.total,
                            color: color
                        });
                    }

                    let dividingChart = this.$refs.dividingChart;
                    dividingChart.removeSeries();
                    dividingChart.addSeries(series);
                })
                .catch(error => {
                    this.$root.$refs.toastr.removeByType('error');
                    this.$root.$refs.toastr.e('Une erreur s\'est produite');
                	console.log(error);
                });
	      	}
	    }
    }
</script>