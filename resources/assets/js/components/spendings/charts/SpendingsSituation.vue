<template>
	<div>
		<legend style="border: none;text-align: center;">
            <select v-model="month" style="border: none; outline: none;" v-on:change="fetchData">
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

            <select v-model="year" style="border: none; outline: none;" v-on:change="fetchData">
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
            </select>
        </legend>

        <vue-highcharts :options="options" ref="situationChart"></vue-highcharts>

        <h4 style="text-align: center;">Total : {{ total.toFixed(2) }} €</h4>
	</div>
</template>

<script>
    export default {
        data() {
      		return{
                total: 0,
                month: 1,
                year: 2017,
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
            this.year = today.getFullYear();
            this.fetchData();
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