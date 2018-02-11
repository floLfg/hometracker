<template>
	<div class="blue-box item-show">
        <div v-show="editing">
            <button class="btn-delete" v-on:click.stop="deleteSaving(item)">
                Supprimer
            </button>
            <form v-on:submit.prevent="updateSaving(item)" v-bind:id="'form-saving-' + item.id">
                <input type="text" ref="title" name="title" v-model="item.title" class="form-control">
                <br>
                <input type="number" min="0" step="0.01" ref="target_amount" name="target_amount" v-model="item.target_amount" class="form-control">
                <button type="submit" class="btn btn-primary form-control">Enregistrer</button>
            </form>
        </div>
        <div v-show="! editing">
            <p class="edit-label">
                <img height="20" src="/img/edit-icon.png" v-on:click="editSaving(item)">
            </p>
            <p>Projet : {{ item.title }}</p>
            <p>Montant visé : {{ item.target_amount }}</p>
            <p>Montant actuel : {{ item.current_amount }}</p>
            <p v-for="user in saving.users">{{ user.name + ' : ' + user.pivot.amount }}</p>
            <hr>
            <participation-create :saving="item"></participation-create>
        </div>
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
                item: this.saving,
                editing: false
            };   
        },
        watch: {
            saving: function (saving) {
                this.item = saving;
            }
        },
        methods: {
            fetchData() {
                this.$parent.fetchData();
            },
            editSaving(saving) {
                if (! this.editing) {
                    this.editing = true;
                    this.$nextTick(() => this.$refs.title.focus());
                }
            },
            updateSaving(saving) {
                axios.put('api/savings/' + saving.id, saving).then((response) => {
                    this.$root.$refs.toastr.removeByType('success');
                    this.$root.$refs.toastr.s('Projet mis à jour');
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
            deleteSaving(saving) {
                if (confirm('Voulez-vous vraiment supprimer ce projet ?')) {
                    axios.delete('api/savings/' + saving.id).then((response) => {
                        this.$root.$refs.toastr.removeByType('success');
                        this.$root.$refs.toastr.s('Projet supprimé');
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
