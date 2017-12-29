<template>
    <div>
        <legend>
            Nouvelle dépense
            <button type="button" class="btn btn-default" data-toggle="modal" data-target=".categoriesModal">
                Gérer mes catégories
            </button>
        </legend>
        <form v-on:submit.prevent="storeSpending(spending)" class="form-inline">
            <input type="date" ref="date" name="date" v-model="spending.date" class="form-control">                
       
            <select class="form-control" v-model="spending.user_id" autocomplete="false">
                <option value="" selected>Membre associé</option>
                <option v-for="user in users" :value="user.id">
                    {{ user.name }}
                </option>
            </select>
       
            <select class="form-control" v-model="spending.category_id" autocomplete="false">
                <option value="" selected>Catégorie</option>
                <option v-for="category in categories" :value="category.id">
                    {{ category.title }}
                </option>
            </select>
        
            <input type="text" ref="label" name="label" v-model="spending.label" class="form-control" placeholder="Détail ...">
        
            <input type="number" ref="amount" name="amount" v-model="spending.amount" class="form-control" placeholder="Montant ..." min="0" step="0.01">
       
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>

        <div class="modal fade categoriesModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Catégories</h4>
                    </div>
                    <div class="modal-body">
                        <categories></categories>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            users: {
                type: Array,
                required: true,
                default: function() {
                    return [];
                }
            },
            categories: {
                type: Array,
                required: true,
                default: function() {
                    return [];
                }
            }
        },
        data() {
            let today = (new Date).toISOString().substr(0, 10);
            return {
                today: today,
                spending: {
                    'date': today,
                    'label': '',
                    'amount': '',
                    'user_id': '',
                    'category_id': ''
                }
            };   
        },
        methods: {
            storeSpending(spending) {
                $('.spending').removeClass('active');
                axios.post('api/spendings', spending).then((response) => {
                    this.$parent.fetchData();
                    this.$root.$refs.toastr.s('Dépense ajoutée');
                    this.spending = {
                        'date': this.today,
                        'label': '',
                        'amount': '',
                        'user_id': ''
                    };
                    this.$nextTick(() => this.$refs.label.focus());
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