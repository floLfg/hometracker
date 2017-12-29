<template>
	<div class="blue-box">
        <form v-on:submit.prevent="storeUser(user)" style="display: flex;">

            <input type="text" ref="name" name="name" v-model="user.name" class="form-control" placeholder= "Nom ..." style="flex: 0 1 100%;margin: 0 5px;">
            <input type="text" ref="email" name="email" v-model="user.email" class="form-control" placeholder="Email ..." style="flex: 0 1 100%;margin: 0 5px;">
            <input type="color" ref="color" name="color" v-model="user.color" class="form-control" style="flex: 0 1 100%;margin: 0 5px;">
            <input type="password" ref="password" name="password" v-model="user.password" class="form-control" placeholder="Mot de passe ...">

            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
	</div>
</template>

<script>
    export default {
        data() {
            return {
                user: {
                    name: '',
                    email: '',
                    password: '',
                    color: '#eeeeee'
                }
            };   
        },
        methods: {
            storeUser(user) {
                axios.post('api/users', user).then((response) => {
                    this.$parent.fetchData();
                    this.$root.$refs.toastr.s('Membre ajouté');
                    this.$nextTick(() => this.$refs.name.focus())
                    $('input').val('');
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
