<template>
    <div>
        <spending-create :users="users" :categories="categories"></spending-create>
        <br><br>
        
        <select v-model="month" class="front-select" v-on:change="fetchData">
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
        <select v-model="year" class="front-select" v-on:change="fetchData">
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
        </select>
        <a class="prev-month" href="#" v-on:click.prevent="prevMonth()">
            <img src="/img/arrow-left.png">
        </a>
        <a class="next-month" href="#" v-on:click.prevent="nextMonth()">
            <img src="/img/arrow-right.png">
        </a>
        <span class="month-total">
            (Total : <b>{{ total }} </b>€)
        </span>
        <table class="table table-striped table-selectable">
            <thead class="center">
                <tr class="orders">
                    <th v-on:click.stop="sortBy('date')" :class="sortKey == 'date' ? 'active' : ''">
                        Date
                        <img v-show="showDateSortIcon" src="/img/sort_icon.png" height="12">
                        <img v-show="showDateSortUpIcon" src="/img/sort_up_icon.png" height="12">
                        <img v-show="showDateSortDownIcon" src="/img/sort_down_icon.png" height="12">
                    </th>
                    <th v-on:click.stop="sortBy('user_id')" :class="sortKey == 'member_id' ? 'active' : ''">
                        Membre
                        <img v-show="showUserSortIcon" src="/img/sort_icon.png" height="12">
                        <img v-show="showUserSortUpIcon" src="/img/sort_up_icon.png" height="12">
                        <img v-show="showUserSortDownIcon" src="/img/sort_down_icon.png" height="12">
                    </th>
                    <th v-on:click.stop="sortBy('category_id')" :class="sortKey == 'category_id' ? 'active' : ''">
                        Catégorie
                        <img v-show="showCategorySortIcon" src="/img/sort_icon.png" height="12">
                        <img v-show="showCategorySortUpIcon" src="/img/sort_up_icon.png" height="12">
                        <img v-show="showCategorySortDownIcon" src="/img/sort_down_icon.png" height="12">
                    </th>
                    <th>Détail</th>
                    <th v-on:click.stop="sortBy('amount')" :class="sortKey == 'amount' ? 'active' : ''">
                        Montant
                        <img v-show="showAmountSortIcon" src="/img/sort_icon.png" height="12">
                        <img v-show="showAmountSortUpIcon" src="/img/sort_up_icon.png" height="12">
                        <img v-show="showAmountSortDownIcon" src="/img/sort_down_icon.png" height="12">
                    </th>
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

                        <button class="btn-primary on-active" v-on:click.stop="updateSpending(spending)" style="margin-top: 4px;">✓</button>
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
                sortKey: 'date',
                reverse: false,

                month: 1,
                year: 2018,
                total: 0,
                spendings: [],
                users: [],
                categories: []
            }
        },
        computed: {
            showDateSortIcon: function () {
                return this.sortKey != 'date';
            },
            showDateSortUpIcon: function () {
                return this.sortKey == 'date' && ! this.reverse;
            },
            showDateSortDownIcon: function () {
                return this.sortKey == 'date' && this.reverse;
            },
            showUserSortIcon: function () {
                return this.sortKey != 'user_id';
            },
            showUserSortUpIcon: function () {
                return this.sortKey == 'user_id' && ! this.reverse;
            },
            showUserSortDownIcon: function () {
                return this.sortKey == 'user_id' && this.reverse;
            },
            showCategorySortIcon: function () {
                return this.sortKey != 'category_id';
            },
            showCategorySortUpIcon: function () {
                return this.sortKey == 'category_id' && ! this.reverse;
            },
            showCategorySortDownIcon: function () {
                return this.sortKey == 'category_id' && this.reverse;
            },
            showAmountSortIcon: function () {
                return this.sortKey != 'amount';
            },
            showAmountSortUpIcon: function () {
                return this.sortKey == 'amount' && ! this.reverse;
            },
            showAmountSortDownIcon: function () {
                return this.sortKey == 'amount' && this.reverse;
            }
        },
        mounted() {
            var today = new Date();
            this.month = today.getMonth() + 1;
            this.year = today.getFullYear();
            
            axios.get('api/users').then((response) => {
                this.users = response.data;
            });

            this.fetchData();
        },
        methods: {
            fetchData() {
                let params = '?month='+parseInt(this.month)+'&year='+parseInt(this.year);

                axios.get('api/spendings' + params).then((response) => {
                    this.spendings = response.data.spendings;
                    this.total = response.data.total
                });

                axios.get('api/categories').then((response) => {
                    this.categories = response.data;
                });
            },
            prevMonth() {
                if (this.month == 1) {
                    this.month = 12;
                    this.year --;
                } else {
                    this.month --;
                }

                this.fetchData();
            },
            nextMonth() {
                if (this.month == 12) {
                    this.month = 1;
                    this.year ++;
                } else {
                    this.month ++;
                }

                this.fetchData();
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
            },
            sortBy: function(sortKey) {
                this.reverse = (this.sortKey == sortKey) ? ! this.reverse : false;
                this.sortKey = sortKey;
                let reverse = this.reverse;
                this.spendings = this.spendings.sort(function(a, b) {
                    let valueA, valueB;

                    if (sortKey == 'user_id') {
                        valueA = a['user']['name'];
                        valueB = b['user']['name'];
                    } else if (sortKey == 'category_id') {
                        valueA = a['category']['title'];
                        valueB = b['category']['title'];
                    } else {
                        valueA = a[sortKey];
                        valueB = b[sortKey];
                    }

                    if (valueA < valueB) {
                        return (reverse) ? -1 : 1;
                    } else if (valueA > valueB) {
                        return (reverse) ? 1 : -1;
                    } else {
                        return 0;
                    }
                });
            },
        }
    }
</script>