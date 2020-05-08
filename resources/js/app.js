require('./bootstrap');

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

import {store} from './store';

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

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
    	/*this.$bvToast.toast(
    		'Main App Mounted!', 
    		{
		        title: 'BootstrapVue Toast'
			}
		);*/
    }
});
