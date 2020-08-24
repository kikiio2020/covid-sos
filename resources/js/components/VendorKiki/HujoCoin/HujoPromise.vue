<template>
<button @click="send" :disabled="disabled || balance<=0">{{cap}}</button>
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
    		default: 'Promise One Hujo Coin',
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
    			func: 'promiseTo',
    			account: this.activeAccount,
    			to: this.to, 
    			token: 1,
    		});
    		this.cap = 'Sending...';
    		this.disabled = true;
    		this.hujoCoin.methods.promiseTo(
   				this.to, 1
 			).send().then((result, error) => {
 				console.log('result:');
   				console.log(result);
   				console.log('error:');
   				console.log(error);
   				
   				this.cap = 'Sent';
   				this.$emit('sent', result);
   			}).catch((error) => {
   				console.log('got error:');
   				console.log(error);
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
        balance: function() {
        	return this.getContractData({
        		contract: 'HujoCoin',
        		method: 'balanceOf',
        	});
        },
    },
    mounted() {
    	this.$store.dispatch('drizzle/REGISTER_CONTRACT', {
    		contractName: 'HujoCoin',
    		method: 'balanceOf',
    		methodArgs: [this.accountAddress],
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