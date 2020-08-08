<template>
	<div>
	<div class="d-flex justify-content-center">
	
	<b-card
		header-tag="header"
		style="max-width: 70rem;"
		header="Hujo Coin"
	>
		<b-card-text>
			<b-card
				border-variant="primary"
				class="my-5"
				style="max-width: 60rem;"
			>
				<div class="d-flex justify-content-center">
					<div>
						Hujo Coin is a community coin that is based on your locale. 
						You can exchange with anyone within approximately 100km of your home. You may purchase Hujo 
						coin with Ethereum crypto currency via the MetaMask browser plugin. It is less than a few 
						bucks US to start. You can <a href="/aboutHujoCoin">Read More</a> about the coin here.
					</div>
				</div>
			</b-card>
		</b-card-text>
		
		<b-card-text>
			<div class="d-flex justify-content-center">
				
				<!-- User with registered wallet and did not just clicked 'Enroll' -->
				<b-card
					v-if="isDrizzleInitialized && hujoCoin.id && hujoCoin.crypto_address==activeAccount && lastTransactionDate > 0 && !enrolSent && !processing"
					style="max-width: 60rem;"
					border-variant="secondary"
			        header="Your Hujo Coin account:"
			        header-border-variant="secondary"
				>
					<b-card-text>
						<div class="d-flex justify-content">
							<span class="font-weight-bold pr-md-2">Cryptocurrency Wallet:</span> 
							<div>
								<input type="text" ref="walletIdBox" readonly :value="activeAccount">
								<b-icon-files @click="copyWalletId"></b-icon-files>
							</div>
						</div>
					</b-card-text>
					
					<b-card-text>
						<div class="d-flex justify-content">
							<span class="font-weight-bold pr-md-2">Cryptocurrency Balance:</span> 
							<span>{{activeBalanceEth}} ETH</span>
						</div>
					</b-card-text>
					
					<b-card-text>
						<div class="d-flex justify-content">
							<span class="font-weight-bold pr-md-2">Hujo Coin Balance:</span> 
							<hujo-balance></hujo-balance>
						</div>
					</b-card-text>
					
					<b-card-text>
						<div class="d-flex justify-content">
							<span class="font-weight-bold pr-md-2">
								Hujo Coin Topup Amount:
							</span> 
							<hujo-topup-cost></hujo-topup-cost>
						</div>
					</b-card-text>
					
					<b-card-text>
						<div class="d-flex justify-content">
							<span class="font-weight-bold pr-md-2">Enrollment Anniversary:</span> 
							<span>{{enrollmentDate}}</span>
						</div>
					</b-card-text>
					
					<b-card-text align=center>
						<hujo-topup 
							v-if="isDrizzleInitialized"
							class="btn btn-success" 
							@sent="topupSent"
						></hujo-topup>
						<button
							v-else
							class="btn btn-success"
							disabled=true
						>Top Up</button>
						<b-icon-info-circle
							v-b-popover.hover.top="'You may only topup the amount you are taxed'"
						></b-icon-info-circle> 
					</b-card-text>
					
				</b-card>

				<!-- User is not enrolled and did not just clicked 'Enroll' but has a wallet ID already registered -->
				<b-card
					v-else-if="isDrizzleInitialized && !hujoCoin.id && lastTransactionDate > 0 && !enrolSent && !processing"
					style="max-width: 60rem;"
					border-variant="danger"
			        header="Error"
			        header-border-variant="danger"
			        header-text-variant="danger"
				>
					<b-card-text>
						Looks like you might be using someone else's MetaMask account. 
						You must have your own unique one to register.
						Please change your MetaMask account and refresh this page. 
					</b-card-text>
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
				
				<!-- New user without registered wallet -->
				<b-card
					v-else-if="!hujoCoin.id && isDrizzleInitialized"
					title="3 easy steps to get started:"
					style="max-width: 60rem;"
				>
					<b-card-text>
						<div class="d-flex justify-content-between">
							<div class="d-flex justify-content-start pr-md-2">
								<div class="pr-md-2"><h4><b-badge pill variant="success">1</b-badge></h4></div>
								<div>
									Enable and log on to MetaMask on browser.
									Click <a href='https://metamask.io/download.html' target="_blank">here</a> 
									to download if you don't already have it.
								</div>
							</div>
							<h4>
								<b-icon v-if="isDrizzleInitialized" icon="check-circle" class="text-success"></b-icon>
								<b-icon v-else icon="x-circle" class="text-danger"></b-icon>
							</h4>
						</div>
					</b-card-text>
					
					<b-card-text>
						<div class="d-flex justify-content-between">
							<div class="d-flex justify-content-start pr-md-2">
								<div class="pr-md-2"><h4><b-badge pill variant="success">2</b-badge></h4></div>
								<div>
									Make sure you have enough Ethereum in your MetaMask wallet to purchase your initial Hujo Coins.
								</div>
							</div>
							<h4>
								<b-icon v-if="activeBalance>0" icon="check-circle" class="text-success"></b-icon>
								<b-icon v-else icon="x-circle" class="text-danger"></b-icon>
							</h4>
						</div>
					</b-card-text>
					
					<b-card-text>
						<div class="d-flex justify-content-between">
							<div class="d-flex justify-content-start pr-md-2">
								<div class="pr-md-2"><h4><b-badge pill variant="success">3</b-badge></h4></div>
								<div>
									When ready, go ahead and click the 'Enrol' button to purchase your first batch of Hujo Coins with your Ethereum. 
									Follow the instructions on your MetaMask plugin.
								</div>
							</div>
							<h4>
								<b-icon-clock v-if="enrolSent"></b-icon-clock>
								<b-icon v-else icon="x-circle" class="text-danger"></b-icon>
							</h4>
						</div>
					</b-card-text>
					
					<b-card-text align=center>
						<hujo-enrol 
							v-if="isDrizzleInitialized"
							:roundedLong="longLat.longitude"
							:roundedLat="longLat.latitude"
							class="btn btn-success" 
							:disabled="!isDrizzleInitialized || activeBalance<=0"
							@sending="onEnrolSending"
							@sent="onEnrolSent"
							@errored="onEnrolErrored"
						></hujo-enrol>
						<button
							v-else
							class="btn btn-success"
							disabled=true
						>Enroll</button>
					</b-card-text>
					
					<b-card
						v-if="enrolSent || processing"
						class="card-align-center mx-5 mb-5"
						header="Enrollment Sent"
						header-bg-variant="primary"
						header-text-variant="white"
						bg-variant="light"
					>
						<b-card-text>
							<div class="d-flex justify-content-between">
								<div v-if="enrolSent" class="d-flex justify-content-start pr-md-2">
									<div >
										Done! Come back to this page later and you will see your new Hujo Coin balance shows up.
									</div>
								</div>
								<div v-else class="d-flex justify-content-center pr-md-2">
									<div><b-spinner></b-spinner></div>
								</div>
								
							</div>
						</b-card-text>
					</b-card>
					
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
							<div>Refresh this page when you are ready.</div>
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

//TODO Error out when wallet ID doesn't match record 
//TODO Error out when wallet ID already used by someone else  

import { createNamespacedHelpers } from 'vuex';

import { BIcon, BIconXCircle, BIconCheckCircle, BButton, BIconInfoCircle, BIconFiles } from 'bootstrap-vue';

const drizzleHelpers = createNamespacedHelpers('drizzle');
const accountHelpers = createNamespacedHelpers('accounts');
const contractHelpers = createNamespacedHelpers('contracts');

/*
Vue.component('b-icon', BIcon);
Vue.component('b-button', BButton);
*/

export default {
    components: {
    },
    props: {
        user: {},
    	hujoCoin: {}, 
        longLat: {},
    },
    data() {
        return {
       		enrolSent: false,
       		processing: false,
        }
    },
    methods: { 
        onEnrolSending(event) {
        	axios.put('webapi/hujoCoin/logEvent', {
        		event: 'enrolSending',
        		...event	
        	});
        },
    	onEnrolSent(event) {
        	this.processing = true;
        	console.log('enrolSent');
        	console.log(event);
        	
        	axios.post('/webapi/hujoCoin', {
        		'user_id': this.user.id,
        		'crypto_address': this.activeAccount,
        		'transaction_hash': event.transactionHash,
        	}).then(response => {
        		this.enrolSent = true;
        		this.processing = false;
        		this.$root.$bvToast.toast('Success', {
                    title: 'Hujo Coin Enrollment',
                    variant: 'success',
                });	
        	}).catch(error => {
        		this.processing = false;
        		this.$root.$bvToast.toast('Action failed.', {
                    title: 'Hujo Coin Enrollment',
                    variant: 'danger',
                });	
    		});
        	
        },
        onEnrolErrored(event){
        	this.$root.$bvToast.toast('An error has occured while enrolling. Please try refreshing your browser.', {
                title: 'Hujo Coin Enrollment',
                variant: 'danger',
            });
        },
        topupSent(event) {
        	console.log('topupSent');
        	console.log(event);
        	
        	/*axios.post('/webapi/hujoCoin', {
        		'user_id': this.user.id,
        		'crypto_address': this.activeAccount,
        		'transaction_hash': event.transactionHash,
        	});*/
        },
        copyWalletId() {
        	this.$refs.walletIdBox.select();
        	document.execCommand("copy");
        }
    },
    computed: {
    	...drizzleHelpers.mapGetters(['isDrizzleInitialized']),
    	...accountHelpers.mapGetters(['activeAccount', 'activeBalance']),
    	...contractHelpers.mapGetters(['getContractData']),
    	activeBalanceEth: function() {
    		return (this.activeBalance * 0.000000000000000001).toPrecision(4);
    	},
    	enrollmentDate: function() {
    		const date = new Date(this.hujoCoin.created_at);
    		
    		return date.getFullYear() 
    			+ '-' + ('0' + (date.getMonth() + 1)).slice(-2) 
    			+ '-' + ('0' + (date.getDate())).slice(-2); 
    	},
    	lastTransactionDate: function() {
        	return this.getContractData({
        		contract: 'HujoCoin',
        		method: 'getLastTransactionDate',
        	});
        },
    },
    mounted() {
    	
    	console.log(this.hujoCoin);
    	
    	this.$store.dispatch('drizzle/REGISTER_CONTRACT', {
    		contractName: 'HujoCoin',
    		method: 'getLastTransactionDate',
    		methodArgs: '',
    	});
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