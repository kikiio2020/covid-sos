<template>
<button @click="join" :disabled="disabled || balance>0 || topupCost>0">{{caption}}</button>
</template>
<script>
import { createNamespacedHelpers } from 'vuex';
const drizzleHelpers = createNamespacedHelpers('drizzle');
const contractHelpers = createNamespacedHelpers('contracts');
const accountHelpers = createNamespacedHelpers('accounts');

export default {
    components: {},
    props: {
    	roundedLong: Number,
    	roundedLat: Number,
    },
    data() {
        return {
        	caption: 'Enroll',
        	disabled: false,
        }
    },
    methods: {
    	join: function () {
    		this.caption = 'Processing...';
    		this.$emit('sending', {
    			func: 'mintEnrol',
    			account: this.activeAccount,
    			roundedLong: Math.round(this.roundedLong), 
    			roundedLat: Math.round(this.roundedLat),
    			value: this.enrolCost,
    		});
    		this.disabled = true;
    		this.hujoCoin.methods.mintEnrol(
    			Math.round(this.roundedLong), Math.round(this.roundedLat)
 			).send({
   				value: this.enrolCost,
   			}).then((result, error) => {
   				console.log('result:');
   				console.log(result);
   				console.log('error:');
   				console.log(error);
   				
   				this.caption = 'Sent';
   				this.$emit('sent', result);
   			}).catch((error) => {
   				console.log('got error:');
   				console.log(error);
   				
   				this.disabled = false;
   				this.caption = 'Enroll';
   				this.$emit('errored', error);
   			});
    	},
    },
    computed: {
        ...drizzleHelpers.mapGetters(['drizzleInstance']),
        ...contractHelpers.mapGetters(['getContractData']),
        ...accountHelpers.mapGetters(['activeAccount']),
        hujoCoin: function() {
        	return this.drizzleInstance.contracts.HujoCoin;
        },		
        enrolCost: function() {
        	return this.getContractData({
        		contract: 'HujoCoin',
        		method: 'enrolCost',
        	});
        },
        topupCost: function() {
        	return this.getContractData({
        		contract: 'HujoCoin',
        		method: 'topupCost',
        	});
        },
        balance: function() {
        	return this.getContractData({
        		contract: 'HujoCoin',
        		method: 'balanceOf',
        	});
        }
    },
    mounted() {
    	this.$store.dispatch('drizzle/REGISTER_CONTRACT', {
    		contractName: 'HujoCoin',
    		method: 'enrolCost',
    		methodArgs: '',
    	});
    	this.$store.dispatch('drizzle/REGISTER_CONTRACT', {
    		contractName: 'HujoCoin',
    		method: 'balanceOf',
    		methodArgs: [this.activeAccount],
    	});
    	this.$store.dispatch('drizzle/REGISTER_CONTRACT', {
    		contractName: 'HujoCoin',
    		method: 'topupCost',
    		methodArgs: '',
    	});
    	this.$drizzleEvents.$on('drizzle/contractEvent', ({eventName, data}) => {
 			// const { contractName, eventName, data } = payload
 		  	if ('Minted' === eventName) {
 			  	this.caption = 'Enrolled';
 			  	this.$emit('minted', data);
 		  	}
   		});
    },
    created() {
    }
}
</script>