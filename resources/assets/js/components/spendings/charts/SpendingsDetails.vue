<template>
	<div>
        <select v-model="month" class="front-select" v-on:change="fetchData">
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
        <select v-model="year" class="front-select" v-on:change="fetchData">
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
        </select>

        <div class="flex-container center">
            <spendings-dividing :year="year" :month="month" ref="spendingsDividing"></spendings-dividing>  
            <spendings-situation :year="year" :month="month" ref="spendingsSituation"></spendings-situation>  
        </div>
	</div>
</template>

<script>
    export default {
    	data() {
    		return {
	    		month: '1',
	            year: '2018'
    		};
    	},
    	mounted() {
            var today = new Date();
            this.month = (today.getMonth() + 1).toString();
            this.year = today.getFullYear().toString();
            this.fetchData();
	    },
	    methods: {
	    	fetchData() {
	    		this.$nextTick(function() {
	    			this.$refs.spendingsDividing.fetchData();
	    			this.$refs.spendingsSituation.fetchData();
	    		});
	    	}
	    }
    }
</script>
