<template>
    <form v-on:submit.prevent="storeCategory(category)" class="flex-container">
        
        <input type="text" ref="title" name="title" v-model="category.title" class="form-control" placeholder="Titre ...">

        <input type="color" ref="color" name="color" v-model="category.color" class="form-control" placeholder="Couleur ..." style="width: 100px">
                    
        <button type="submit" class="btn btn-primary">Ajouter</button>
                    
    </form>
</template>

<script>
    export default {
        data() {
            return {
                category: {
                    'title': '',
                    'color': '#92cae0'
                }
            };   
        },
        methods: {
            storeCategory(category) {
                $('.category').removeClass('active');
                axios.post('api/categories', category).then((response) => {
                    this.$parent.fetchData();
                    this.$root.$refs.toastr.s('Catégorie ajoutée');
                    this.category = {
                        'title': '',
                        'color': '#92cae0'
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