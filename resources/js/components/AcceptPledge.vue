<template>
	<b-card 
		class="card-align-center"
		header="Confirm Accepting Pledge"
	>
		<b-card-text class="p-5">
			Please click the button below to accept the pledge from 
			<span class="font-weight-bold">{{ responderName }}</span>
			for your request
			<span class="font-weight-bold">"{{ requestName }}"</span>
			scheduled for 
			<span class="font-weight-bold">{{ neededbyDate }}</span>
		</b-card-text>

		<b-card 
			v-if="accepted"
			class="card-align-center mx-5 mb-5"
			header="Pledge Accepted"
			header-bg-variant="primary"
			header-text-variant="white"
			bg-variant="light"
		>
			<b-card-text>
				Great! You have accepted the pledge. You can start communicating with 
					{{ responderName }}
					by going to the "In Progress" tab on the main page and then the Actions drop-down. 
			</b-card-text>
		</b-card>

		<div class="d-flex justify-content-center">
			<div>
				<b-button @click="accept" variant="primary" :disabled="processing || accepted">
					<b-spinner v-if="processing"></b-spinner>
					<b-icon-check-circle v-else-if="accepted"></b-icon-check-circle>
					<span v-else>Accept</span>
				</b-button>
			</div>
		</div>
		
	</b-card>  
</template>
<script>
export default {
    components: {},
    props: ['requestId', 'requestName', 'responderName', 'neededbyDate'],
    data() {
        return {
        	accepted: false,
        	processing: false,
        }
    },
    methods: {
    	accept() {
    		this.processing = true;
    		axios.put('/webapi/sosRequest/acceptPledge/' + this.requestId)
   			.then(response => {
   				this.processing=false;
   				this.accepted = true;
   				this.$root.$bvToast.toast('Success!', {
                    title: 'Accept Pledge',
                    variant: 'success',
                });
   				this.$bvToast.show('success-msg');
   			})
   			.catch(error => {
                this.$root.$bvToast.toast('Action Failed', {
                    title: 'Pledging',
                    variant: 'danger',
                });
   			})
    	}
    },
    computed: {},
    mounted() {
    }
}
</script>