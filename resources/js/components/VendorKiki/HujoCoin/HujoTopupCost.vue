<template>
<span>{{topupCost}}</span>
</template>
<script>
import { createNamespacedHelpers } from 'vuex';
const contractHelpers = createNamespacedHelpers('contracts');

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
    		methodArgs: [],
    	});
    }
}
</script>