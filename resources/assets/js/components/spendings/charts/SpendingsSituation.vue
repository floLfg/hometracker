<template>
	<div>
		<legend style="border: none;">
            <select v-model="month" style="border: none; outline: none; margin: auto;display: block;" v-on:change="fetchData">
                <option value="1">Janvier</option>
                <option value="2">Février</option>
                <option value="3">Mars</option>
                <option value="4">Avril</option>
                <option value="5">Mai</option>
                <option value="6">Juin</option>
                <option value="7">Juillet</option>
                <option value="8">Août</option>
                <option value="9">Septembre</option>
                <option value="10">Octobre</option>
                <option value="11">Novembre</option>
                <option value="12">Décembre</option>
            </select>
        </legend>

        <vue-highcharts :options="options" ref="situationChart"></vue-highcharts>
	</div>
</template>

<script>
    export default {
        data() {
      		return{
                month: 1,
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
	    mounted() {
            var today = new Date();
            this.month = today.getMonth() + 1;
            this.fetchData();
	    },
	    methods: {
	      	fetchData() {
                axios.get('api/charts/situation?month=' + this.month).then((response) => {

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