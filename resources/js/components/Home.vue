<template>
<div>
<home-buttons-bar
    v-on:SosClick="onSosClick"
></home-buttons-bar>
<b-container>
    <b-row>
        <b-col>
            <b-tabs content-class="mt-3">
                <b-tab title="Nearby Needs!" :active="isResponder" :disabled="!isResponder" lazy ref="nearbyAsksView">
                    <nearby-asks-model-view
                        :is-responder="isResponder"
                        :user-id="userId"
                        ref="nearbyAsksModelView"
                        @nearbyNewPledged="newPledged($event)"
                    ></nearby-asks-model-view>
                </b-tab>
                <b-tab title="In Progress" lazy ref="inProgressAsksView">
                    <in-progress-asks-model-view
                        :is-responder="isResponder"
                        :delivery-options="deliveryOptions"
                        :payment-options="paymentOptions"
                        :user-name="userName"
                        ref="asksInProgressModelView"
                    ></in-progress-asks-model-view>
                </b-tab>
                <b-tab title="Pendings" :active="!isResponder" lazy ref="pendingAsksView">
                    <pending-asks-model-view
                        :is-responder="isResponder"
                        :sos-options="sosOptions"
                        ref="asksPendingModelView"
                    ></pending-asks-model-view>
                </b-tab>
                <b-tab title="Manage my SOSs" lazy ref="sosView">
                    <sos-model-view
                        :is-responder="isResponder"
                        :delivery-options="deliveryOptions"
                        :payment-options="paymentOptions"
                        ref="sosModelView"
                        @sosCreatesNewAsk="newAsk($event)"
                    ></sos-model-view>
                </b-tab>
                <b-tab title="History" lazy>
                    <!--<history-model-view
                        :is-responder="isResponder"
                        :delivery-options="deliveryOptions"
                        :payment-options="paymentOptions"
                    ></history-model-view>-->
                </b-tab>
            </b-tabs>
        </b-col>
    </b-row>
</b-container>
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
</div>
</template>
<script>
window.bus = new Vue();
export default {
    components: {},
    props: [
        'isResponder',
        'deliveryOptions', 
        'paymentOptions',
        'userId',
        'userName',
        'sosOptions'
    ],
    data() {
        return {
        }
    },
    methods: {
        onSosClick: function() {
            if (this.sosOptions.length) {
                this.$bvModal.show('confirm-create-sos-modal');
            }
        },
        newSos: function() {
            this.$refs.sosView.activate(function(){});
            this.$nextTick(function () {
                this.$refs.sosModelView.insertModel();
            })
            this.$bvModal.hide('confirm-create-sos-modal');
        },
        existingSos: function(){
            this.$bvModal.hide('confirm-create-sos-modal');
        },
        newAsk: function(data) {
            this.$refs.pendingAsksView.activate(function(){});
            this.$nextTick(function () {
                this.$refs.asksPendingModelView.insertModel({}, data);
            })
        },
        newPledged:function(data) {
            this.$refs.inProgressAsksView.activate(function(){});
        },
    },
    computed: {},
    mounted() {
    },
}
</script>