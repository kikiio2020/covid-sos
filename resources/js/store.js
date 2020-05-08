import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const homeTabIndex = {
	nearby: 0,
	inProgress: 1,
	pending: 2,
	sos: 3,
	history: 4,
};

const workflowOriginatorTabIndex = {
	sosCreateNewAsk: homeTabIndex.sos,
};

const workflowNewTabIndex = {
	sosCreateNewAsk: homeTabIndex.pending,
};

export const store = new Vuex.Store({
	state: {
		currentWorkflow: '',
		currentHomeTabIndex: 0,
	},

	getters: {
	},

	mutations: {
		startWorkflow: function(state, workflow) {
			state.currentWorkflow = workflow;
			store.commit('setHomeTabIndex', workflowNewTabIndex[workflow]);
		},
		endWorkflow: function(state) {
			state.currentWorkflow = '';
		},
		workflowCancel: function(state) {
			if (state.currentWorkflow){
				state.currentHomeTabIndex = workflowOriginatorTabIndex[state.currentWorkflow];	
			}
			store.commit('endWorkflow');
		},
		setHomeTabIndex: function(state, index) {
			state.currentHomeTabIndex = index; 
		},
	},
	
	actions: {
		myAction(context, data) {
			//TODO 
		}
	},
});