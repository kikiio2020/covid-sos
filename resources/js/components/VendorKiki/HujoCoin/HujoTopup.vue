<template>
<button @click="mint" :disabled="disabled || topupCost<=0">{{caption}}</button>
</template>
<script>
import { createNamespacedHelpers } from 'vuex';
const drizzleHelpers = createNamespacedHelpers('drizzle');
const contractHelpers = createNamespacedHelpers('contracts');

export default {
    components: {},
    props: {},
    data() {
        return {
        	caption: 'Top Up',
        	disabled: false,
        }
    },
    methods: {
    	mint: function () {
    		this.caption = 'Processing...';
    		this.disabled = true;
    		if (this.topupCost <= 0) {
    			this.caption = 'Topped Up';
    			return;
    		}
    		this.hujoCoin.methods.mint().send({
   				value: this.topupCost,
   			}).then((result, error) => {
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
   				this.caption = 'Top Up';
   				this.$emit('errored', error);
   			});
    	},
    },
    computed: {
        ...drizzleHelpers.mapGetters(['drizzleInstance']),
        ...contractHelpers.mapGetters(['getContractData']),
        hujoCoin: function() {
        	return this.drizzleInstance.contracts.HujoCoin;
        },				
        topupCost: function() {
        	return this.getContractData({
        		contract: 'HujoCoin',
        		method: 'topupCost',
        	});
        }
    },
    mounted() {
    	this.$store.dispatch('drizzle/REGISTER_CONTRACT', {
    		contractName: 'HujoCoin',
    		method: 'topupCost',
    		methodArgs: '',
    	});
    	this.$drizzleEvents.$on('drizzle/contractEvent', ({eventName, data}) => {
 			// const { contractName, eventName, data } = payload
 		  	if ('Minted' === eventName) {
 			  	this.caption = 'Topped Up';
 			  	this.$emit('minted', data);
 		  	}
   		});
    },
    created() {
    }
}
</script>