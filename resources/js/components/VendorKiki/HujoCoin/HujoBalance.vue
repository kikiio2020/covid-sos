<template>
<span>{{balance}}</span>
</template>
<script>
import { createNamespacedHelpers } from 'vuex';
const contractHelpers = createNamespacedHelpers('contracts');
const accountHelpers = createNamespacedHelpers('accounts');

export default {
    components: {},
    props: {},
    data() {
        return {}
    },
    methods: {
    },
    computed: {
    	...contractHelpers.mapGetters(['getContractData']),
    	...accountHelpers.mapGetters(['activeAccount']),
        
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
    		method: 'balanceOf',
    		methodArgs: [this.activeAccount],
    	});
    }
}
</script>