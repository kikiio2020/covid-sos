<template>
    <div>
	    <crud-control
	        id="asks-in-progress-model-view"
	        ref="myModelView"
	        table-name=""
	        :initial-values="{ 
	            id: 0, 
	            sos_text: '', 
	            description: '',
	            needed_by: new Date(),
	            vendor_name: '',
	            vendor_address: '',
	            delivery_option: null,
	            payment_option: null,
	            special_instructions: null,
	            chat: null,
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
	            	key: 'responder',
	                sortable: true,
	            },
	            'actions',
	        ]"
	        :actions="[
	        	'details',
	        	{
	        		label: 'Chat',
	        		event: 'chatRequest',
	        		icon: 'chat-dots',
	        	},
	        	{
	        		label: 'Complete',
	        		event: 'completeRequest',
	        		icon: 'check',
	        		disabled(row) {
	        			const data = row.item;
	        			
	        			return (
		        				userId == data.responded_by 
		        				&& null !== data.responder_approved 
	        				) ||
	        				(
	        					userId == data.user_id 
		        				&& null !== data.user_approved
	        				)
        			}, 
	        	},
	        	{
	        		label: 'Cancel',
	        		event: 'cancelRequest',
	        		icon: 'x',
	        	},
	       	]"
	       	:buttons="[
	       		{
	       			id:'1',
	       			variant: 'primary',
	                content: 'Full View',
	                event: 'openFullView'
	       		}
	       	]"
	        :modal-fields="modalFields"
	        api="/webapi/ask"
	        gridUrlQuery="/inProgressView"
	        :insertable="false"
	        @chatRequest="chatRequest"
	        @completeRequest="completeRequest"
	        @cancelRequest="cancelRequest"
	        @openFullView="openFullView"
	    >
	    	<!-- :user-name="userName" -->
	    </crud-control>
    	<chat
    		id="chatBox"
    		:user-name="userName"
    		api="/webapi/ask"
    		:model-id="currentAskForChat.id"
    	></chat>
  	</div>
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
        'userName',
    ],
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const minNeededByDate = new Date(today);
        const maxNeededByDate = new Date(today);
        maxNeededByDate.setDate(maxNeededByDate.getDate() + 14);

        return {
            modalFields: [
                {   
                    fieldType: "model-read-only-field",
                    placeholder: "Request",
                    name: "sos_text",
                    id: "sos_text"
                },
                {   
                    fieldType: "model-read-only-field",
                    placeholder: "Request Description",
                    name: "sos_description",
                    id: "sos_description"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Needed By",
                    name: "needed_by",
                    id: "needed_by",
                    
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Vendor",
                    name: "vendor_name",
                    id: "vendor_name"
                },
                {
                    fieldType: "model-gmap-link-field",
                    placeholder: "Vendor Address",
                    name: "vendor_address",
                    id: "vendor_address"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Delivery",
                    name: "delivery_option",
                    id: "delivery_option"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Special Instructions",
                    name: "special_instructions",
                    id: "special_instructions"
                },
            ],
            currentAskForChat: {
           		chat: '[]'
            },
        }
    },
    methods: {
        insertModel(target) {
            this.$refs.myModelView.insertModel(target);
        },
        chatRequest(row) {
        	this.currentAskForChat = row;
        	this.$bvModal.show('chatBox');
        },
        completeRequest(row) {
        	const isResponder = this.userId == row.responded_by;
        	const confirmMsg = isResponder ? 
        			'Please confirm request from ' + row.requester + ' is completed: ' :
       				'Please confirm your request ' + row.sos_text + ' is completed: ';
        	this.$bvModal.msgBoxConfirm(confirmMsg)
            .then(value => {
            	if (value) {
            		axios.put('webapi/ask/completeRequest/' + row.id)
            			.then((response) => {
            				this.$bvModal.msgBoxOk(
           						isResponder ? 
           							'Thank you so much for helping out! We appreciate you being our community! Once the requestor has also signed off this request will be moved to the History tab. It will remain accessible there for one month. Thank you!':
          							'Thank you for confirming! We will notify your helper and will move this task to the History tab once everyone has approved. It will remain there for one month. Thank you!'
         					);
            				this.$refs.myModelView.loadData();
            			}).catch((error) => {
            				console.log(error);
            				this.$bvModal.msgBoxOk('Update failed. Please try again later.');
            			});
            	}
            })
            .catch(err => {
                console.log(err);
            });
        },
        cancelRequest(row) {
        	const confirmMsg = this.userId == row.responded_by ? 
        			'Are you sure you want to cancel your pledge to ' + row.requester + '?' :
       				'Are you sure you want to cancel your request for ' + row.sos_text + '?';
        	this.$bvModal.msgBoxConfirm(confirmMsg)
	            .then(value => {
	            	if (value) {
	            		this.$refs.myModelView.removeModel(row.id);	
	            	}
	            })
	            .catch(err => {
	                console.log(err);
	            });
        },
        openFullView(data) {
        	window.open('/ask/' + data.id + '/inProgress', '_blank');
        },
        
    },
    computed: {},
    mounted() {
    }
}
</script>