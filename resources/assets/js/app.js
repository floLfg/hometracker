
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('vue-toastr/dist/vue-toastr.css');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import Toastr from 'vue-toastr';
import VueHighcharts from 'vue2-highcharts'

Vue.use(VueRouter);

Vue.component('vue-toastr',Toastr);
Vue.component('VueHighcharts', VueHighcharts);
Vue.component('RouterNav', require('./components/RouterNav.vue'));

Vue.component('Home', require('./components/Home.vue'));

Vue.component('Users', require('./components/users/Users.vue'));
Vue.component('UserShow', require('./components/users/UserShow.vue'));
Vue.component('UserCreate', require('./components/users/UserCreate.vue'));

Vue.component('Spendings', require('./components/spendings/Spendings.vue'));
Vue.component('SpendingCreate', require('./components/spendings/SpendingCreate.vue'));
Vue.component('SpendingsCharts', require('./components/spendings/charts/SpendingsCharts.vue'));
Vue.component('SpendingsTotal', require('./components/spendings/charts/SpendingsTotal.vue'));
Vue.component('SpendingsDetails', require('./components/spendings/charts/SpendingsDetails.vue'));
Vue.component('SpendingsDividing', require('./components/spendings/charts/SpendingsDividing.vue'));
Vue.component('SpendingsSituation', require('./components/spendings/charts/SpendingsSituation.vue'));

Vue.component('Categories', require('./components/categories/Categories.vue'));
Vue.component('CategoryCreate', require('./components/categories/CategoryCreate.vue'));

Vue.component('Savings', require('./components/savings/Savings.vue'));
Vue.component('SavingShow', require('./components/savings/SavingShow.vue'));
Vue.component('SavingCreate', require('./components/savings/SavingCreate.vue'));
Vue.component('SavingSituation', require('./components/savings/charts/SavingSituation.vue'));

Vue.component('ParticipationCreate', require('./components/participations/ParticipationCreate.vue'));

const router = new VueRouter({
    mode: 'history',
  	routes: [
  		{
            name: 'home',
            path: '/home',
            alias: '/',
    		component: Vue.component('Home')
    	},
        {
            name: 'spendings',
            path: '/spendings',
            component: Vue.component('Spendings')
        },
        {
            name: 'synthesis',
            path: '/synthesis',
            component: Vue.component('SpendingsCharts')
        },
        {
            name: 'savings',
            path: '/savings',
            component: Vue.component('Savings')
        },
        {
            name: 'users',
            path: '/users',
            component: Vue.component('Users')
        }
  	]
});

const app = new Vue({
    el: '#app',
    router: router
});

app.$refs.toastr.defaultProgressBar = false;