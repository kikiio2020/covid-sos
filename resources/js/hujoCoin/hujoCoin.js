require('../bootstrap');

window.Vue = require('vue');

import Vue from 'vue';
import 'es6-promise/auto';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import PortalVue from 'portal-vue';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import '@fortawesome/fontawesome-free/js/fontawesome';
import '@fortawesome/fontawesome-free/js/solid';
import '@fortawesome/fontawesome-free/js/regular';
import '@fortawesome/fontawesome-free/js/brands';

import {store} from './hujoCoinStore';

const files = require.context('./components/', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//app.blade
Vue.component('welcome-alert', require('../components/AlertMessages/WelcomeAlert.vue').default);
Vue.component('hujo-enrol-invite', require('../components/AlertMessages/HujoEnrolInvite.vue').default);
//hujo coin
Vue.component('hujo-enrol', require('../components/VendorKiki/HujoCoin/HujoEnrol.vue').default);
Vue.component('hujo-topup', require('../components/VendorKiki/HujoCoin/HujoTopup.vue').default);
Vue.component('hujo-balance', require('../components/VendorKiki/HujoCoin/HujoBalance.vue').default);
Vue.component('hujo-topup-cost', require('../components/VendorKiki/HujoCoin/HujoTopupCost.vue').default);
Vue.component('hujo-promise', require('../components/VendorKiki/HujoCoin/HujoPromise.vue').default);
Vue.component('hujo-send', require('../components/VendorKiki/HujoCoin/HujoSend.vue').default);

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(PortalVue);

const app = new Vue({
    el: '#app',
    store,
    data: {
    },
    methods:{
    },
    mounted() {
    }
});
