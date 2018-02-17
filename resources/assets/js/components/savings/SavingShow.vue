<template>
	<div class="blue-box item-show saving">
        <div v-show="editing">
            <button class="btn-delete" v-on:click.stop="deleteSaving(item)">
                Supprimer
            </button>
            <form v-on:submit.prevent="updateSaving(item)" v-bind:id="'form-saving-' + item.id">
                <label for="title">Projet :</label>
                <br>
                <input type="text" ref="title" name="title" v-model="item.title" class="form-control">
                <br><br>
                <label for="target_amount">Montant visé :</label>
                <br>
                <input type="number" min="0" step="0.01" ref="target_amount" name="target_amount" v-model="item.target_amount" class="form-control">
                <button type="submit" class="btn btn-primary form-control">Enregistrer</button>
            </form>
        </div>
        <div v-show="! editing">
            <p class="edit-label">
                <img height="20" src="/img/edit-icon.png" v-on:click="editSaving(item)">
            </p>
            <h2>{{ item.title }}</h2>
            <h3>
                Cagnotte : {{ item.current_amount }} / {{ item.target_amount }}
                ({{ ((item.current_amount/item.target_amount)*100).toPrecision(3) }} %)
            </h3>
            <div class="row">
                <div class="col-md-8">
                    <saving-situation :saving="item"></saving-situation>
                </div>
                <div class="col-md-4">
                    <participation-create :saving="item"></participation-create>
                </div>
            </div>
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
