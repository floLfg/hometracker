<template>
	<div>
		<legend style="border: none;">
            <select v-model="year" style="border: none; outline: none; margin: auto; display: block;" v-on:change="fetchData">
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
            </select>
        </legend>

        <vue-highcharts :options="options" ref="lineCharts"></vue-highcharts>
	</div>
</template>

<script>
    export default {
        data() {
      		return{
                year: 2017,
        		options: {
                    title: {text: ''},
                    subtitle: {text: ''},
                    credits: {enabled: false},
		          	chart: {
                        type: 'line',
                        height: 250
                    },
                    xAxis: {
            			categories: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Dec']
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
        created() {
            var today = new Date();
            this.year = today.getFullYear();
        },
	    mounted() {
	    	this.fetchData();
	    },
	    methods: {
	      	fetchData() {
                axios.get('api/charts/spendings?year=' + this.year).then((response) => {
                    let serie = {
					  	name: 'Dépenses',
					  	marker: {symbol: 'circle'},
					  	data: response.data
					};
                    let lineCharts = this.$refs.lineCharts;
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