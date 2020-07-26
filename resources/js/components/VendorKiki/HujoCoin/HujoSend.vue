<template>
<button @click="send" :disabled="disabled || balance<=0">{{caption}}</button>
</template>
<script>
import { createNamespacedHelpers } from 'vuex';
const drizzleHelpers = createNamespacedHelpers('drizzle');
const accountHelpers = createNamespacedHelpers('accounts');
const contractHelpers = createNamespacedHelpers('contracts');

export default {
    components: {},
    props: ['to'],
    data() {
    	return {
    		caption: 'Send',
    		disabled: false,
    	} 
    },
    methods: {
    	send: function () {
    		this.caption = 'Sending...';
    		this.disabled = true;
    		this.hujoCoin.methods.transferFrom(
   				this.accountAddress, this.to, 1
 			).send().then((result, error) => {
 				console.log('result:');
   				console.log(result);
   				console.log('error:');
   				console.log(error);
   				
   				this.caption = 'Pending';
   				this.$emit('sent', result);
   			}).catch((error) => {
   				console.log('got error:');
   				console.log(error);
   				this.disabled = false;
   				this.caption = 'Send';   				
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
 			  	this.caption = 'Sent';
 			  	this.disabled = false;
 			  	this.$emit('sent', data);
 		  	}
   		});
    },
    created() {
    }
}
</script>