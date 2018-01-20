<template>
	<div class="blue-box user-show" v-on:click="editUser(item)" style="border-bottom: 30px solid;padding-bottom: 15px;" :style="'border-color:'+user.color">
        <div v-show="editing">
            <form v-on:submit.prevent="updateUser(item)" v-bind:id="'form-user-' + item.id">
                <input type="text" ref="name" name="name" v-model="item.name" class="form-control">
                <br>
                <input type="text" ref="email" name="email" v-model="item.email" class="form-control">
                <br>
                <input type="color" ref="color" name="color" v-model="user.color" class="form-control">
                <button v-show="false" type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        <div v-show="! editing">
            <button class="btn-delete" v-on:click.stop="deleteUser(item)">X</button>
            <p class="name">{{ item.name }}</p>
            <p class="email">{{ item.email }}</p>
        </div>
	</div>
</template>

<script>
    export default {
    	props: {
    		user: {
                type: Object,
                required: true
            }
    	},
        data() {
            return {
                item: {
                    name: '',
                    email: ''
                },
                editing: false
            };   
        },
        created() {
            this.item = this.user;
        },
        methods: {
            editUser(user) {
                if (! this.editing) {
                    this.editing = true;
                    this.$nextTick(() => this.$refs.name.focus());
                }
            },
            updateUser(user) {
                axios.put('api/users/' + user.id, user).then((response) => {
                    this.$root.$refs.toastr.removeByType('success');
                    this.$root.$refs.toastr.s('Membre mis à jour');
                    this.item = response.data;
                    this.editing = false;
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
            deleteUser(user) {
                if (confirm('Voulez-vous vraiment supprimer ce membre ?')) {
                    axios.delete('api/users/' + user.id).then((response) => {
                        this.$root.$refs.toastr.removeByType('success');
                        this.$root.$refs.toastr.s('Membre supprimé');
                        this.$parent.fetchData();
                    }).catch(error => {
                        this.$root.$refs.toastr.removeByType('error');
                        this.$root.$refs.toastr.e('Une erreur est survenue');
                    });
                }
            }
        }
    }
</script>
