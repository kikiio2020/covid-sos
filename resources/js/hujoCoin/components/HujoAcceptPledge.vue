<template>
	<div>
	<div class="d-flex justify-content-center">
	
	<b-card
		header-tag="header"
		style="max-width: 70rem;"
		:header="'Accept pledge from ' + responderName"
	>
		<b-card-text>
			<b-card
				border-variant="primary"
				class="my-5"
				style="max-width: 60rem;"
			>
				<div class="d-flex justify-content-center">
					<div>
						Please reward 
						<span class="font-weight-bold">{{ responderName }}</span>
						with one Hujo Coin at the completion of the request 
						<span class="font-weight-bold">"{{ requestName }}"</span>
						scheduled for
						<span class="font-weight-bold">{{ neededbyDate }}</span>
					</div>
				</div>
			</b-card>
		</b-card-text>
		
		<b-card-text>
			<div class="d-flex justify-content-center">
				
				<!-- User with registered wallet -->
				<b-card
					v-if="isDrizzleInitialized && hujoCoin.id && hujoCoin.crypto_address==activeAccount"
					style="max-width: 60rem;"
					border-variant="secondary"
			        header="Your Hujo Coin account:"
			        header-border-variant="secondary"
				>
					<b-card-text>
						<div class="d-flex justify-content">
							<span class="font-weight-bold pr-md-2">Current Hujo Coin Balance:</span> 
							<hujo-balance></hujo-balance>
						</div>
					</b-card-text>
					
					<b-card-text v-if="txId">
						<div class="d-flex justify-content">
							<span class="font-weight-bold pr-md-2">Transaction ID:</span> 
							<div>
								<input type="text" ref="txIdBox" readonly :value="txId">
								<b-icon-files @click="copyTxId"></b-icon-files>
							</div>
						</div>
					</b-card-text>
					
					<b-card-text align=center>
						<b-toast id="txSentSuccess" title="Accept Pledge Success" variant="success" static no-auto-hide>
					      	Thanks! We will notify {{ responderName }} and you can start the communication
					      	by going to the Request Details in "In Progress" tab.
					    </b-toast>
					    <div class="my-2"><b-spinner v-if="apiSending"></b-spinner></div>
						<hujo-promise 
							v-if="isDrizzleInitialized"
							class="btn btn-success"
							:to="hujoCoinRecipient.crypto_address" 
							@sending="onTxSending"
							@sent="onTxSent"
							@errored="onTxErrored"
						></hujo-promise>
						<button
							v-else
							class="btn btn-success"
							disabled=true
						>Promise One Hujo Coin</button>
					</b-card-text>
					
					<b-card-text><div class="d-flex justify-content">
						One Hujo Coin will be withdrawn and put on hold.
					</div></b-card-text>
				</b-card>

				<!-- User with incorrect wallet ID -->
				<b-card
					v-else-if="isDrizzleInitialized && hujoCoin.id && hujoCoin.crypto_address!=activeAccount"
					style="max-width: 60rem;"
					border-variant="danger"
			        header="Error"
			        header-border-variant="danger"
			        header-text-variant="danger"
				>
					<b-card-text>
						The MetaMask account you are using does not match our record. 
						Please select the correct account and refresh this page.
					</b-card-text>
				</b-card>
				
				<!-- Waiting for Drizzle to start / user sign on to MetaMask -->
				<b-card
					v-else
					style="max-width: 60rem;"
					border-variant="info" 
					header="Info"
				>
					<b-card-text align="center">
						<div align="left">
							<div>Please start and logon to MetaMask on your browser.</div>
							<div>Ensure you have selected the Ethereum mainnet,</div>
							<div>and the digital wallet that you have used to enroll Hujo Coin.</div>
						</div>
						<div class="m-5"><b-spinner></b-spinner></div>
					</b-card-text>
				</b-card>
				
			</div>
		</b-card-text>
	</b-card>
	</div>
	
	</div>
</template>

<script>
import { createNamespacedHelpers } from 'vuex';

import { BIcon, BIconXCircle, BIconCheckCircle, BButton, BIconInfoCircle, BIconFiles } from 'bootstrap-vue';

const drizzleHelpers = createNamespacedHelpers('drizzle');
const accountHelpers = createNamespacedHelpers('accounts');
const contractHelpers = createNamespacedHelpers('contracts');

export default {
    components: {
    },
    props: {
        requestId: 0,
        requestName: '',
        responderName: '',
        neededbyDate:'',
    	hujoCoin: {}, 
    	hujoCoinRecipient: {},
    },
    data() {
        return {
       		txId: '',
       		apiSending: false,
        }
    },
    methods: { 
    	onTxSending(event) {
        	axios.put('/webapi/hujoCoin/logEvent', {
        		event: 'txSending',
        		...event	
        	});
        },
    	onTxSent(event) {
        	this.apiSending = true;
        	this.txId = event.transactionHash;
        	axios.put('/webapi/sosRequest/acceptPledge/' + this.requestId, {
        		'transaction_hash': event.transactionHash,
        	}).then(response => {
        		this.apiSending = false;
        		this.$bvToast.show('txSentSuccess');
        		this.$root.$bvToast.toast('Success', {
                    title: 'Accept Pledge',
                    variant: 'success',
                });	
        	}).catch(error => {
        		this.apiSending = false;
        		this.$root.$bvToast.toast('Action failed.', {
                    title: 'Accept Pledge',
                    variant: 'danger',
                });	
        	});
        },
        onTxErrored(event){
        	axios.put('/webapi/hujoCoin/logEvent', {
        		event: 'txErrored',
        		...event	
        	});
        },
        copyTxId() {
        	this.$refs.txIdBox.select();
        	document.execCommand("copy");
        }
    },
    computed: {
    	...drizzleHelpers.mapGetters(['isDrizzleInitialized']),
    	...accountHelpers.mapGetters(['activeAccount', 'activeBalance']),
    	...contractHelpers.mapGetters(['getContractData']),
    },
    mounted() {
    }
}
</script>
<style>
.circledNumber {
  background: green;
   border-radius: 0.8em;
  -moz-border-radius: 0.8em;
  -webkit-border-radius: 0.8em;
  color: #ffffff;
  display: inline-block;
  font-weight: bold;
  line-height: 1.6em;
  margin-right: 15px;
  text-align: center;
  width: 1.6em; 
}

</style>