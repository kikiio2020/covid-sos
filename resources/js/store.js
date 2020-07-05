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
	sosCreateNewRequest: homeTabIndex.sos,
};

const workflowNewTabIndex = {
	sosCreateNewRequest: homeTabIndex.pending,
};

export const store = new Vuex.Store({
	state: {
		currentWorkflow: '',
		currentHomeTabIndex: 0,
		sosArray: [],
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
			store.dispatch('cacheHomeTabIndex', index);
		},
		setSosArray: function(state, sosArray) {
			state.sosArray = sosArray;
		},
		appendSosArray: function(state, sosItem) {
			state.sosArray.push(sosItem);
		},
	},
	
	actions: {
		cacheHomeTabIndex(context, index) {
			axios.put('/webapi/user/updateHomeTabIndexCache', {
        		index: index,
        	});
		},
		loadSosArray(context) {
			axios.get('/webapi/sos/sosView').then((response) => {
				context.commit('setSosArray', response.data.data);
			}); 
		}
	},
});