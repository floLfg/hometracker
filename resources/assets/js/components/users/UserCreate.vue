<template>
	<div class="blue-box">
        <form v-on:submit.prevent="storeUser(user)">
            <input type="text" ref="name" name="name" v-model="user.name" class="form-control" placeholder= "Nom ...">
            <br>
            <input type="text" ref="email" name="email" v-model="user.email" class="form-control" placeholder="Email ...">
            <br>
            <input type="password" ref="password" name="password" v-model="user.password" class="form-control" placeholder="Mot de passe ...">
            <br>
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
                    password: ''
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
