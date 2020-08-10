<template>
<b-container>
    <b-row><b-col class="text-left">
        <crud-control
            id="nearby-model-view"
            ref="myModelView"
            table-name=""
            :initial-values="{ 
                id: 0, 
                name: '', 
                description: '',
                needed_by: new Date(),
            }"
            :grid-fields="[
                {
                    key: 'needed_by',
                    sortable: true,
                },
                {
                    key: 'name', 
                    sortable: true,
                },
                {
                	key: 'creator.name',
                	label: 'Sender',
                	sortable: true,
                },
                {
                	key: 'type', 
                    sortable: true,
                },
                {
                    key: 'distance_km', 
                    label: 'KM',
                    sortable: true,
                },
                {
                	key: 'actions',
                	label: '',
                }
                
            ]"
            :actions="[
	        	'details',
	        	{
	        		label: 'Pledge',
	        		event: 'pledgeRequest',
	        		fontawesome: 'fas fa-hands-helping',
	        	},
	       	]"
            :modal-fields="modalFields"
            api="/webapi/sosRequest"
            gridUrlQuery="/nearbyView"
            :insertable="false"
            :table-properties="tableProperties"
			@pledgeRequest="pledgeRequest"     
			@record-load-failed="onSosRequestBackendFailed"
	        @record-create-failed="onSosRequestBackendFailed"
	        @record-update-failed="onSosRequestBackendFailed"
	        @record-remove-failed="onSosRequestBackendFailed"       
        >
        </crud-control>
    </b-col></b-row>
</b-container>
</template>

<script>
import CrudControl from '@kikiio2020/vue-crud-control';

export default {
    components: {CrudControl},
    props: [
        'isResponder',
        'deliveryOptions', 
        'paymentOptions',
        'userId',
    ],
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const minNeededByDate = new Date(today);
        const maxNeededByDate = new Date(today);
        maxNeededByDate.setDate(maxNeededByDate.getDate() + 14);

        return {
            tableProperties: {
                    //selectable: true,
                    //selectMode: "single",
            },
            modalFields: [
                {   
                    fieldType: "model-read-only-field",
                    placeholder: "Request",
                    name: "name",
                    id: "name"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Description",
                    name: "description",
                    id: "description"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Needed By",
                    name: "needed_by",
                    id: "needed_by",
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Delivery Option",
                    id: "delivery_option_txt",
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Payment Option",
                    id: "payment_option_txt",
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Special Instructions",
                    name: "special_instructions",
                    id: "special_instructions"
                },
            ],
        }
    },
    methods: {
        pledgeRequest(request) {
            const confirmMsg = 'Make a pledge to "' + request.name + '" for ' + request['creator.name'] + '?';
            const hujoConfirmMsg = (request.hujo ? ' You will receive one Hujo Coin at completion.' : '');
        	this.$bvModal.msgBoxConfirm(
                confirmMsg + hujoConfirmMsg, 
                {
                    title: 'Confirm Pledge',
                    headerBgVariant: 'primary',
                    headerTextVariant: 'white',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'primary',
                    okTitle: 'YES',
                    cancelVariant: 'secondary',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                }
            ).then(confirm => {
                if (confirm) {
                    axios.put(
                        '/webapi/sosRequest/pledgeRequest/' + request.id
                        /*, 
                        {
                            id: request.id,
                            status: 1,
                            responded_by: this.userId
                        }*/
                    ).then(response => {
                        this.$root.$bvToast.toast('Pledge sent', {
                            title: 'Thank you!',
                            variant: 'success',
                        });
                        this.$bvModal.msgBoxOk(
                            'Thanks for helping out! Your pledge has been sent. Please wait for a response.',
                            {
                                title: 'Pledge Sent',
                                headerBgVariant: 'primary',
                                headerTextVariant: 'white',
                                size: 'sm',
                                buttonSize: 'sm',
                                footerClass: 'p-2',
                                hideHeaderClose: false,
                                centered: true
                            }
                        ).then((confirm) =>{
                            this.$emit('nearbyNewPledged', {
                                id: request.id,
                            });
                        }).catch(err => {
                            // An error occurred
                       	});
                    }).catch(error => {
                        var errMsg = 'Pledging failed';
                    	if ('request_expired' == error.response.data.message) {
                        	errMsg = 'The SOS Request is expired'; 
                        }
                        this.$root.$bvToast.toast(errMsg, {
                            title: 'Pledging',
                            variant: 'danger',
                        });
                    });       
                }
            }).catch(err => {
                // An error occurred
            })
        },
        onSosRequestBackendFailed(error) {
    		this.$root.$bvToast.toast(errMsg ? errMsg : error.response.data.message, {
                title: 'Nearby SOS Requests',
                variant: 'danger',
            });
    	},
    },
    computed: {
        rowsSelected: function() {
            if (this.$refs.myModelView && this.$refs.myModelView.rowsSelected ) {
                return this.$refs.myModelView.rowsSelected;
            } else {
                return {};
            }
        },
    },
    mounted() {
    }
}
</script>