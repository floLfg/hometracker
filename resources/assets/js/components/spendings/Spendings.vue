<template>
    <div>
        <spending-create :users="users" :categories="categories"></spending-create>
        <br><br>
        <legend style="padding-bottom: 5px;">
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
        </legend>
        <table class="table table-striped table-selectable">
            <thead class="center">
                    <tr>
                        <th>Date</th>
                        <th>Membre</th>
                        <th>Catégorie</th>
                        <th>Détail</th>
                        <th>Montant</th>
                        <th></th>
                    </tr>
                </thead>
            <tbody class="center">
                <tr v-for="spending in spendings" v-on:click.stop="editSpending(spending)" :ref="'spending-' + spending.id" class="spending">
                    <td>
                        <span class="on-non-active">
                            {{ new Date(spending.date).toLocaleDateString() }}
                        </span>
                        <span class="on-active">
                            <input type="date" ref="date" name="date" v-model="spending.date" class="form-control" @keyup.enter="updateSpending(spending)">
                        </span>
                    </td>
                    <td>
                        <span class="on-non-active">
                            <span v-if="spending.user" :style="'color:' + spending.user.color">{{ spending.user.name }}</span>
                            <span v-else="spending.user">/</span>
                        </span>
                        <span class="on-active">
                            <select class="form-control" v-model="spending.user_id" value="" @keyup.enter="updateSpending(spending)">
                            <option value="null">Membre associé</option>
                            <option v-for="user in users" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                        </span>
                    </td>
                    <td>
                        <span class="on-non-active">
                            <span v-if="spending.category" :style="'color:' + spending.category.color">
                                {{ spending.category.title }}
                            </span>
                            <span v-else="spending.category">/</span>
                        </span>
                        <span class="on-active">
                            <select class="form-control" v-model="spending.category_id" value="" @keyup.enter="updateSpending(spending)">
                            <option value="null">Catégorie</option>
                            <option v-for="category in categories" :value="category.id">
                                {{ category.title }}
                            </option>
                        </select>
                        </span>
                    </td>
                    <td>
                        <span class="on-non-active">
                            {{ spending.label }}
                        </span>
                        <span class="on-active">
                            <input type="text" ref="label" name="label" v-model="spending.label" class="form-control" @keyup.enter="updateSpending(spending)">
                        </span>
                    </td>
                    <td class="small">
                        <span class="on-non-active">
                            {{ spending.amount }} €
                        </span>
                        <span class="on-active">
                            <input type="number" ref="amount" name="amount" v-model="spending.amount" class="form-control" @keyup.enter="updateSpending(spending)" min="0" step="0.01">
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <button class="btn-delete on-non-active" v-on:click.stop="deleteSpending(spending)">x</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                month: 1,
                spendings: [],
                users: [],
                categories: []
            }
        },
        mounted() {
            var today = new Date();
            this.month = today.getMonth() + 1;
            
            axios.get('api/users').then((response) => {
                this.users = response.data;
            });

            axios.get('api/categories').then((response) => {
                this.categories = response.data;
            });

            this.fetchData();
        },
        methods: {
            fetchData () {
                axios.get('api/spendings?month=' + parseInt(this.month)).then((response) => {
                    this.spendings = response.data;
                });
            },
            editSpending(spending) {
                if (! $(this.$refs['spending-' + spending.id]).hasClass('active')) {
                    $('.spending').removeClass('active');
                    $(this.$refs['spending-' + spending.id]).addClass('active');
                    $(this.$refs.label).focus();
                }
            },
            updateSpending(spending) {
                axios.put('api/spendings/' + spending.id, spending).then((response) => {
                    this.$root.$refs.toastr.removeByType('success');
                    this.$root.$refs.toastr.s('Dépense mise à jour');
                    $('.spending').removeClass('active');
                    this.fetchData();
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
            },
            deleteSpending(spending) {
                $('.spending').removeClass('active');
                if (confirm('Voulez-vous vraiment supprimer cette dépense ?')) {
                    axios.delete('api/spendings/' + spending.id).then((response) => {
                        this.$root.$refs.toastr.removeByType('success');
                        this.$root.$refs.toastr.s('Dépense supprimée');
                        this.fetchData();
                    }).catch(error => {
                        this.$root.$refs.toastr.removeByType('error');
                        this.$root.$refs.toastr.e('Une erreur est survenue');
                    });
                }
            }
        }
    }
</script>