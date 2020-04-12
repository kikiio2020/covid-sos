<template>
<b-container>
    <b-row>
        <b-col>
            <button-sos v-if="!isResponder" @click="insertModel($event.target)" class="m-3"></button-sos>
            <button-invite class="m-3"></button-invite>
            <button-shoplist class="m-3"></button-shoplist>
        </b-col>
    </b-row>        
    <b-row>
        <b-col>
            <b-tabs content-class="mt-3">
                <b-tab title="My Pledges" :active="isResponder" lazy>
                    <pledges-model-view
                        :is-responder="isResponder"
                        :delivery-options="deliveryOptions"
                        :payment-options="paymentOptions"
                        :user-name="userName"
                    ></pledges-model-view>
                </b-tab>
                <b-tab title="My SOSs" :active="!isResponder" lazy>
                    <need-model-view
                        :is-responder="isResponder"
                        :delivery-options="deliveryOptions"
                        :payment-options="paymentOptions"
                    ></need-model-view>
                </b-tab>
                <b-tab title="History" lazy>
                    <!--<history-model-view
                        :is-responder="isResponder"
                        :delivery-options="deliveryOptions"
                        :payment-options="paymentOptions"
                    ></history-model-view>-->
                </b-tab>
                <b-tab title="Nearby SOSs" :disabled="!isResponder" lazy><p>Nearby View</p></b-tab>
            </b-tabs>
        </b-col>
    </b-row>
</b-container>
</template>
<script>
export default {
    components: {},
    props: [
        'isResponder',
        'deliveryOptions', 
        'paymentOptions',
        'userName'
    ],
    data() {
        return {
        }
    },
    methods: {
    },
    computed: {},
    mounted() {
    }
}
</script>