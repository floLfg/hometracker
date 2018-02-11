<template>
    <div>
        <form v-on:submit.prevent="addAmount()">
            <p>Ajouter un montant</p>
            <input type="number" ref="amount" name="amount" v-model="amount" class="form-control" placeholder="Montant apporté ..." step="0.01">

            <button type="submit" class="btn btn-primary">
                Ajouter
            </button>
        </form>
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
            return {
                amount: 0
            };   
        },
        methods: {
            addAmount() {
                axios.post('api/savings/'+this.saving.id+'/participations?amount='+this.amount).then((response) => {
                    this.$parent.fetchData();
                    this.$root.$refs.toastr.s('Apport ajouté');
                    this.amount = 0;
                    this.$nextTick(() => this.$refs.amount.focus());
                }).catch(error => {
                    let messages = '';
                    let errors = error.response.data.errors;
                    for (let field in errors) {
                        for (let index in errors[field]) {
                            messages += '<br>';
                            messages += errors[field][index];
                            messages += '<br>';
                        }
                    }
                    this.$root.$refs.toastr.removeByType('error');
                    this.$root.$refs.toastr.e(messages);
                });
            }
        }
    }
</script>