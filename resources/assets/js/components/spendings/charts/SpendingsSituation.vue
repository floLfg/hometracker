<template>
    <div>
        <vue-highcharts :options="options" ref="situationChart"></vue-highcharts>
        <h4 style="text-align: center;">Total : {{ total.toFixed(2) }} €</h4>
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
                total: 0,
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
                        height: 300
                    },
                    tooltip: {
                        pointFormat: 'Part : <b>{point.percentage:.1f}%</b><br>Total : <b>{point.y} €</b>'
                    },
                    plotOptions: {
                        pie: {
                            startAngle: -90,
                            endAngle: 90,
                            center: ['50%', '75%']
                        }
                    },
                    series: []
        		}
      		}
    	},
	    methods: {
	      	fetchData() {
                let params = '?month=' + parseInt(this.month) + '&year=' + parseInt(this.year);
                axios.get('api/charts/situation' + params).then((response) => {
                    this.total = 0;
                    let series = {
                        name: 'Dépenses',
                        innerSize: '50%',
                        data: []
                    };

                    for (let i = 0; i <  response.data.length; i ++) {
                        let data = response.data[i];
                        let title = data.name || 'Commun';
                        let color = data.color || '#ccc';
                        series.data.push({
                            name: title,
                            y: data.total,
                            color: color
                        });
                        this.total += data.total;
                    }

                    let situationChart = this.$refs.situationChart;
                    situationChart.removeSeries();
                    situationChart.addSeries(series);
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