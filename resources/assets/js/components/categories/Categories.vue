<template>
    <div>
        <category-create></category-create>
        <br><br>
        <table class="table table-striped table-selectable">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Couleur</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories" v-on:click.stop="editCategory(category)" :ref="'category-' + category.id" class="category">
                    <td>
                        <span class="on-non-active">
                            {{ category.title }}
                        </span>
                        <span class="on-active">
                            <input type="text" ref="title" name="title" v-model="category.title" class="form-control" @keyup.enter="updateCategory(category)">
                        </span>
                    </td>
                    <td class="small">
                        <span class="on-non-active">
                            <p :style="'color:' + category.color">{{ category.color }}</p>
                        </span>
                        <span class="on-active">
                            <input type="color" ref="color" name="color" v-model="category.color" class="form-control" @keyup.enter="updateCategory(category)">
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <button class="btn-delete on-non-active" v-on:click.stop="deleteCategory(category)">x</button>
                        <button class="btn btn-primary on-active" v-on:click.stop="updateCategory(category)">Enregistrer</button>
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
                categories: []
            }
        },
        mounted() {
            this.fetchData();
        },
        methods: {
            fetchData () {
                axios.get('api/categories').then((response) => {
                    this.categories = response.data;
                });
            },
            editCategory(category) {
                if (! $(this.$refs['category-' + category.id]).hasClass('active')) {
                    $('.category').removeClass('active');
                    $(this.$refs['category-' + category.id]).addClass('active');
                    $(this.$refs.title).focus();
                }
            },
            updateCategory(category) {
                axios.put('api/categories/' + category.id, category).then((response) => {
                    this.$root.$refs.toastr.removeByType('success');
                    this.$root.$refs.toastr.s('Catégorie mise à jour');
                    $('.category').removeClass('active');
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
            deleteCategory(category) {
                $('.category').removeClass('active');
                if (confirm('Voulez-vous vraiment supprimer cette catégorie ?')) {
                    axios.delete('api/categories/' + category.id).then((response) => {
                        this.$root.$refs.toastr.removeByType('success');
                        this.$root.$refs.toastr.s('Catégorie supprimée');
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