import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import drizzleVuePlugin from '@drizzle/vue-plugin';
import drizzleOptions from './drizzleOptions';

export const store = new Vuex.Store({
	state: {
	},
	getters: {
	},
	mutations: {
	},
	actions: {
	},
});

Vue.use(drizzleVuePlugin, { store, drizzleOptions });