<template>
    <div class="blue-box">
        <legend>
            Nouveau projet d'économies
        </legend>
        <form v-on:submit.prevent="storeSaving(saving)">
            <input type="text" ref="title" name="title" v-model="saving.title" class="form-control" placeholder="Projet ...">
            <br>
            <label for="target_amount">Montant visé :</label>
            <br>
            <input type="number" ref="target_amount" name="target_amount" v-model="saving.target_amount" class="form-control" placeholder="Montant visé ..." min="0" step="0.01">
            <br>
            <button type="submit" class="btn btn-primary pull-right">
                Créer
            </button>
            <div class="clearer"></div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                saving: {
                    'title': '',
                    'target_amount': 0
                }
            };   
        },
        methods: {
            storeSaving(saving) {
                $('.saving').removeClass('active');
                axios.post('api/savings', saving).then((response) => {
                    this.$parent.fetchData();
                    this.$root.$refs.toastr.s('Projet ajouté');
                    this.saving = {
                        'title': '',
                        'target_amount': 0
                    };
                    this.$nextTick(() => this.$refs.title.focus());
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