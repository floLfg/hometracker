<template>
	<div>
        <vue-highcharts :options="options" ref="lineCharts"></vue-highcharts>
	</div>
</template>

<script>
    export default {
        data() {
      		return{
        		options: {
                    title: {text: ''},
                    subtitle: {text: ''},
                    credits: {enabled: false},
		          	chart: {
                        type: 'line',
                        height: 250
                    },
                    xAxis: {
            			categories: []
          			},
          			yAxis: {
            			title: {
             				text: 'Dépenses'
            			},
            			labels: {
              				formatter: function () {
                				return this.value + ' €';
              				}
            			}
          			},
          			series: []
        		}
      		}
    	},
	    mounted() {
	    	this.fetchData();
	    },
	    methods: {
	      	fetchData() {
                axios.get('api/charts/spendings').then((response) => {
                    let lineCharts = this.$refs.lineCharts;
                    console.log(lineCharts);
                    lineCharts.delegateMethod('update', {
                        xAxis: {
                            categories: response.data.categories
                        }
                    });
                    let serie = {
                        name: 'Dépenses',
                        marker: {symbol: 'circle'},
                        data: response.data.data
                    };
                    
                    lineCharts.removeSeries();
                    lineCharts.addSeries(serie);
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