<template>
<div>
<!-- <home-buttons-bar
    v-on:SosClick="onSosClick"
></home-buttons-bar>
 -->
<b-container>
    <b-row>
        <b-col>
            <b-tabs content-class="mt-3" v-model="currentHomeTabIndex">
                <!-- :active="isResponder" :disabled="!isResponder"  -->
                <b-tab title="Nearby Needs!" lazy ref="nearbyView">
                    <nearby-model-view
                        :is-responder="isResponder"
                        :user-id="userId"
                        ref="nearbyModelView"
                        @nearbyNewPledged="newPledged"
                    ></nearby-model-view>
                </b-tab>
                <b-tab title="In Progress" lazy ref="inProgressView">
                    <in-progress-model-view
                        :is-responder="isResponder"
                        :delivery-options="deliveryOptions"
                        :payment-options="paymentOptions"
                        :user-id="userId"
                        :user-name="userName"
                        ref="inProgressModelView"
                    ></in-progress-model-view>
                </b-tab>
                <!-- :active="!isResponder" -->
                <b-tab title="Pendings" lazy ref="pendingView">
                    <pending-model-view
                        :is-responder="isResponder"
                        ref="pendingModelView"
                    ></pending-model-view>
                </b-tab>
                <b-tab title="Manage my SOSs" lazy ref="sosView">
                    <sos-model-view
                        :is-responder="isResponder"
                        :delivery-options="deliveryOptions"
                        :payment-options="paymentOptions"
                        ref="sosModelView"
                        @sosCreatesNewRequest="newRequest($event)"
                    ></sos-model-view>
                </b-tab>
                <b-tab title="History" lazy ref="historyView">
                    <history-model-view></history-model-view>
                </b-tab>
            </b-tabs>
        </b-col>
    </b-row>
</b-container>
<!-- 
<b-modal id="confirm-create-sos-modal" size="sm" centered hide-footer>
    <template v-slot:modal-title>
        Choose option:
    </template>
    <b-container>
        <b-row><b-col class="text-center">
            <b-button class="m-3"  @click="newSos()" variant="primary">Create New SOS</b-button>
            <b-button class="m-3"  @click="existingSos()" variant="primary">Use Previously Sent SOS</b-button>
        </b-col></b-row>
    </b-container>
</b-modal>
 -->
</div>
</template>
<script>
import {homeTabIndex} from '../store';

export default {
    components: {},
    props: [
        'isResponder',
        'deliveryOptions', 
        'paymentOptions',
        'userId',
        'userName',
        'currentTabIndex'
    ],
    data() {
        return {}
    },
    methods: {
        newRequest: function (data) {
            this.$store.commit('startWorkflow', 'sosCreateNewRequest');
        	this.$nextTick(function () {
                this.$refs.pendingModelView.insertModel({}, data);
            })
        },
        newPledged:function () {
        	this.$store.commit('setHomeTabIndex', homeTabIndex.inProgress);
        },
    },
    computed: {
    	currentHomeTabIndex: {
    		get () {
    			return this.$store.state.currentHomeTabIndex;
    		},
    		set (value) {
    			this.$store.commit('setHomeTabIndex', value);
    		}
    	}
    },
	created() {
    	this.$store.dispatch('loadSosArray');	
    },
    mounted() {
    	this.currentHomeTabIndex = this.currentTabIndex;
    },
}
</script>