<template>
	<b-button 
		:variant="variant" 
		@click="completeRequest"
		:disabled="disabled"
	>{{text}}</b-button>
</template>
<script>
export default {
    components: {},
    props: ['askId'],
    data() {
        return {
        	text: 'Complete',
        	disabled: false,
        	variant: 'primary',
        }
    },
    methods: {
        completeRequest() {
        	this.disabled = true;
        	this.text = '...';
        	this.$bvModal.msgBoxConfirm('Please confirm Complete Request.')
        		.then(confirm => {
	       			if (confirm) {
	       				axios.put('/webapi/ask/completeRequest/' + this.askId, [])
	                  		.then(() => {
	                  			this.$root.$bvToast.toast('Confirm Complete Request', {
	                               title: 'Thanks for confirming!',
	                               variant: 'success',
	                           	});
	                  			this.text = 'Completed';
	                  			this.variant = 'secondary';
	                  		})	
	       			} else {
	       				this.disabled = false;
	       				this.text = 'Complete';
       				}
        		})
        },
    },
    computed: {},
    mounted() {}
}
</script>