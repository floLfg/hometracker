<template>
    <div>
        <div class="flex-container center full-width">
            <saving-show
                v-for="saving in savings"
                :key="saving.id"
                :saving="saving"
            >
            </saving-show>
        </div>
        <br><br>
        <div class="flex-container center">
            <saving-create></saving-create>
        </div> 
    </div>
</template>

<script>
    export default {
        data() {
            return {
                savings: []
            }
        },
        mounted() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                axios.get('api/savings').then((response) => {
                    this.savings = response.data;
                    this.$emit('update:savings');
                });
            }
        }
    }
</script>