<template>
<button @click="send" :disabled="disabled || !hasPromise">{{cap}}</button>
</template>
<script>
import { createNamespacedHelpers } from 'vuex';
const drizzleHelpers = createNamespacedHelpers('drizzle');
const accountHelpers = createNamespacedHelpers('accounts');
const contractHelpers = createNamespacedHelpers('contracts');

export default {
    components: {},
    props: {
    	to: String, 
    	caption: {
    		type: String,
    		default: 'Release One Hujo Coin',
    	},
    },
    data() {
    	return {
    		cap: this.caption,
    		disabled: false,
    	} 
    },
    methods: {
    	send: function () {
    		this.$emit('sending', {
    			func: 'transferFrom',
    			account: this.activeAccount,
    			from: this.accountAddress, 
    			to: this.to, 
    			token: 1,
    		});
    		this.cap = 'Sending...';
    		this.disabled = true;
    		this.hujoCoin.methods.transferFrom(
   				this.accountAddress, this.to, 1
 			).send().then((result, error) => {
   				this.cap = 'Sent';
   				this.$emit('sent', result);
   			}).catch((error) => {
   				this.disabled = false;
   				this.cap = this.caption;
   				this.$emit('errored', error);
   			});
    	},
    },
    computed: {
    	...drizzleHelpers.mapGetters(['drizzleInstance']),
    	...accountHelpers.mapGetters(['activeAccount']),
    	...contractHelpers.mapGetters(['getContractData']),
    	
    	hujoCoin: function() {
        	return this.drizzleInstance.contracts.HujoCoin;
        },
        accountAddress: function() {
        	return this.activeAccount;
        },
        hasPromise: function() {
        	return this.getContractData({
        		contract: 'HujoCoin',
        		method: 'hasPromise',
        	});
        },
    },
    mounted() {
    	this.$store.dispatch('drizzle/REGISTER_CONTRACT', {
    		contractName: 'HujoCoin',
    		method: 'hasPromise',
    		methodArgs: [this.to, 1],
    	});
    	this.$drizzleEvents.$on('drizzle/contractEvent', ({eventName, data}) => {
 			// const { contractName, eventName, data } = payload
 		  	if ('Transfer' === eventName) {
 			  	this.cap = 'Sent';
 			  	this.disabled = false;
 			  	this.$emit('sent', data);
 		  	}
   		});
    },
    created() {
    }
}
</script>