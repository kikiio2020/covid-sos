<template>
<div>
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
                    key: 'hujo', 
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
<b-modal
	id="pledge-confirm-box"
	title="Confirm Pledge"
	header-bg-variant="primary"
	header-text-variant="white"
	size="sm"
	button-size="sm"
	ok-variant="primary"
	cancel-variant="secondary"
	cancel-title="NO"
	footer-class="p-2"
	:hideHeader-close="false"
	:centered="true"
	:busy="pledgeProcessing"
	@ok="onPledgeRequestConfirmOK"
>
	{{ pledgeConfirmMsg }}
	<template v-slot:modal-ok>
		<b-spinner small v-if="pledgeProcessing"></b-spinner>
		<span v-else>YES</span>
	</template>
</b-modal>
</div>
</template>

<script>
import CrudControl from '@kikiio2020/vue-crud-control';

export default {
    components: {CrudControl},
    props: [
        'isResponder',
        'isHujo',
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
                    placeholder: "Category",
                    name: "type",
                    id: "type"
                },
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
                    placeholder: "Detail Instructions",
                    name: "detail_instructions",
                    id: "detail_instructions"
                },
            ],
            pledgeProcessing: false,
            pledgeConfirmMsg: '',
            toPledgeRequest: null,
        }
    },
    methods: {
    	pledgeRequest(request) {
            const confirmMsg = 'Make a pledge to "' + request.name + '" for ' + request['creator.name'] + '?';
            const hujoConfirmMsg = (request.hujo == 'Y' && this.isHujo ? ' You will receive one Hujo Coin at completion.' : '');
            this.pledgeConfirmMsg = confirmMsg + hujoConfirmMsg;
            this.toPledgeRequest = request;
            this.$bvModal.show('pledge-confirm-box');
            
            return;
        },
		onPledgeRequestConfirmOK(event) {
    		event.preventDefault();
    		this.pledgeProcessing = true;
    		axios.put(
                    '/webapi/sosRequest/pledgeRequest/' + this.toPledgeRequest.id
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
                            id: this.toPledgeRequest.id,
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
                }).then(response => {
                	this.$bvModal.hide('pledge-confirm-box');
                	this.pledgeProcessing = false;
                });   	
    	},
        onSosRequestBackendFailed(error) {
    		this.$root.$bvToast.toast(error.response.data.message, {
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