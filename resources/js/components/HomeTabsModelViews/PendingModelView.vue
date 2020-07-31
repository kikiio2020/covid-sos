<template>
    <div>
    <crud-control
        id="pending-model-view"
        ref="myModelView"
        table-name=""
        :initial-values="{ 
            id: 0, 
            sos_id: null,
            needed_by: null,
            special_instruction: '',
        }"
        :grid-fields="[
            {
                key: 'needed_by',
                sortable: true,
            },
            {
                key: 'sos_text', 
                label: 'Name',
                sortable: true,
            },
            {
                key: 'responder_name',
                label: 'Taker', 
                sortable: true,
            },
            'actions',
        ]"
        :actions="actions"
        :modal-fields="modalFields"
        api="/webapi/sosRequest"
        gridUrlQuery="/pendingsView"
        :insertable="!isResponder"
        @modal-close="actionCancel"
        @async-returns="actionCompletes"
        @cancelPledge="cancelPledge"
        @cancelRequest="cancelRequest"
        @acceptPledge="acceptPledge"
        @rejectPledge="rejectPledge"
        
    ></crud-control>
    </div>
</template>

<script>
import CrudControl from '@kikiio2020/vue-crud-control';

export default {
    components: {CrudControl},
    props: [
        'isResponder',
    ],
    data() {
        if (this.isResponder) {
        	var actions = [
        		{
        			label: 'Cancel',
            		event: 'cancelPledge',
            		icon: 'x-circle',
        		}
        	];
        } else {
        	var actions = [
        		{
        			label: 'Accept',
            		event: 'acceptPledge',
            		icon: 'emoji-smile',
            		disabled(row) {
	        			return !Boolean(row.item.responded_by); 
        			}, 
        		},
        		{
        			label: 'Reject',
            		event: 'rejectPledge',
            		icon: 'emoji-frown',
            		disabled(row) {
            			return !Boolean(row.item.responded_by); 
        			},
        		},
        		{
        			label: 'Cancel',
            		event: 'cancelRequest',
            		icon: 'x-circle',
        		},
        	];
        }
    	
    	return {
        	actions: actions
        }
    },
    methods: {
        insertModel(target, modalRecordData) {
            this.$refs.myModelView.insertModel(target, modalRecordData);
        },
        onSave() {
        },
        actionCompletes: function() {
        	this.$store.commit('endWorkflow');
        },
        actionCancel: function() {
        	this.$store.commit('workflowCancel');
        },
        cancelPledge: function(row) {
        	const confirmMsg = 'Are you sure you want to cancel your pledge to the request?';
        	this.$bvModal.msgBoxConfirm(confirmMsg)
	            .then(value => {
	            	if (value) {
	            		axios.put('webapi/sosRequest/cancelPledge/' + row.id)
            			.then((response) => {
            				this.$root.$bvToast.toast('Pledge Cancelled', {
                                title: 'Success',
                                variant: 'success',
                            });
            				this.$bvModal.msgBoxOk(
	                            'Thank you. Your pledge is now removed.',
	                            {
	                                title: 'Pledge Cancelled',
	                                headerBgVariant: 'primary',
	                                headerTextVariant: 'white',
	                                size: 'sm',
	                                buttonSize: 'sm',
	                                footerClass: 'p-2',
	                                hideHeaderClose: false,
	                                centered: true
	                            }
                        	).then(response => {
                        		this.$refs.myModelView.loadData();
                        	});
                        }).catch(err => {
                        	this.$root.$bvToast.toast('Action failed.', {
                                title: 'Cancel Pledge',
                                variant: 'danger',
                            });
                       	});
	            	}
	            })
	            .catch(err => {
	                //console.log(err);
	            });
        },
        cancelRequest: function(row) {
        	const confirmMsg = 'Are you sure you want to cancel your request?';
        	this.$bvModal.msgBoxConfirm(confirmMsg)
	            .then(value => {
	            	if (value) {
	            		axios.put('webapi/sosRequest/cancelRequest/' + row.id)
            			.then((response) => {
            				this.$root.$bvToast.toast('Request Cancelled', {
                                title: 'Success',
                                variant: 'success',
                            });
            				this.$bvModal.msgBoxOk(
	                            'Thank you. Your request is now removed.',
	                            {
	                                title: 'Request Cancelled',
	                                headerBgVariant: 'primary',
	                                headerTextVariant: 'white',
	                                size: 'sm',
	                                buttonSize: 'sm',
	                                footerClass: 'p-2',
	                                hideHeaderClose: false,
	                                centered: true
	                            }
                        	).then(response => {
                        		this.$refs.myModelView.loadData();
                        	});
                        }).catch(err => {
                        	this.$root.$bvToast.toast('Action failed.', {
                                title: 'Cancel Request',
                                variant: 'danger',
                            });
                       	});
	            	}
	            })
	            .catch(err => {
	                //console.log(err);
	            });
        },
		acceptPledge: function(row) {
			const confirmMsg = 'Please confirm you are accepting the pledge from ' + row.responder_name + ':';
        	this.$bvModal.msgBoxConfirm(confirmMsg)
	            .then(value => {
	            	if (value) {
	            		axios.put('webapi/sosRequest/acceptPledge/' + row.id)
            			.then((response) => {
            				this.$root.$bvToast.toast('Pledge Accepted', {
                                title: 'Success',
                                variant: 'success',
                            });
            				this.$bvModal.msgBoxOk(
	                            'Thanks for confirming! You can start communicating with ' 
	                            + row.responder_name 
	                            + ' by going to the request Details in "In Progress" tab.',
	                            {
	                                title: 'Pledge Accepted',
	                                headerBgVariant: 'primary',
	                                headerTextVariant: 'white',
	                                size: 'sm',
	                                buttonSize: 'sm',
	                                footerClass: 'p-2',
	                                hideHeaderClose: false,
	                                centered: true
	                            }
                        	).then((confirm) => {
                            	this.$emit('pendingPledgeAccepted', {id: row.id});
                        	});
                        }).catch(err => {
                        	this.$root.$bvToast.toast('Action failed.', {
                                title: 'Accept Pledge',
                                variant: 'danger',
                            });
                       	});
	            	}
	            })
	            .catch(err => {
	                //console.log(err);
	            });
        },
        rejectPledge: function(row) {
        	const confirmMsg = 'Are you sure you want to turn down the pledge from ' + row.responder_name + '?';
        	this.$bvModal.msgBoxConfirm(confirmMsg)
	            .then(value => {
	            	if (value) {
	            		axios.put('webapi/sosRequest/rejectPledge/' + row.id)
            			.then((response) => {
            				this.$root.$bvToast.toast('Pledge Rejected', {
                                title: 'Success',
                                variant: 'success',
                            });
            				this.$bvModal.msgBoxOk(
	                            'Thank you. You have turned  down the pledge from ' 
	                            + row.responder_name 
	                            + '. Your request is now back waiting for other potential helpers.',
	                            {
	                                title: 'Pledge Rejected',
	                                headerBgVariant: 'primary',
	                                headerTextVariant: 'white',
	                                size: 'sm',
	                                buttonSize: 'sm',
	                                footerClass: 'p-2',
	                                hideHeaderClose: false,
	                                centered: true
	                            }
                        	).then(response => {
                        		this.$refs.myModelView.loadData();
                        	});
                        }).catch(err => {
                        	this.$root.$bvToast.toast('Action failed.', {
                                title: 'Reject Pledge',
                                variant: 'danger',
                            });
                       	});
	            	}
	            })
	            .catch(err => {
	                //console.log(err);
	            });
        },
    },
    computed: {
    	sosArray: function() {
    		return this.$store.state.sosArray.map(sos => {
    			return {
    				value: sos.id,
    				text: sos.name,
    			}
    		})
    	},
    	
    	modalFields: function() {
    		const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const minNeededByDate = new Date(today);
            minNeededByDate.setDate(minNeededByDate.getDate() + 7);
            const maxNeededByDate = new Date(today);
            maxNeededByDate.setMonth(maxNeededByDate.getMonth() + 1);
            
    		return [
                {
                    fieldType: "model-select-field",
                    caption: "SOS",
                    name: "SOS",
                    placeholder: "SOS *",
                    id: "sos_id",
                    required: true,
                    rules: "required",
                    options: this.sosArray,
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-datepicker",
                    caption: "Needed By",
                    placeholder: "Needed By Date:",
                    name: "Needed By Date",
                    id: "needed_by",
                    required: true,
                    rules: "required",
                    min: minNeededByDate,
                    max: maxNeededByDate,
                    locale: 'en-US',
                    labelHelp: '',
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Special Instructions",
                    placeholder: "Special Instructions",
                    name: "special_instruction",
                    id: "special_instruction",
                    class: "my-3 mr-3",
                },
            ];
    	}
    
    },
    mounted() {
    }
}
</script>