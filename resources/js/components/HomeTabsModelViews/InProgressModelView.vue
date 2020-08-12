<template>
    <div>
	    <crud-control
	        id="in-progress-model-view"
	        ref="myModelView"
	        table-name=""
	        :initial-values="{ 
	            id: 0, 
	            sos_text: '', 
	            description: '',
	            needed_by: new Date(),
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
	                key: 'type',
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
	        api="/webapi/sosRequest"
	        gridUrlQuery="/inProgressView"
	        :insertable="false"
	        @chatRequest="chatRequest"
	        @completeRequest="completeRequest"
	        @cancelRequest="cancelRequest"
	        @openFullView="openFullView"
	        @record-load-failed="onSosRequestBackendFailed"
	        @record-create-failed="onSosRequestBackendFailed"
	        @record-update-failed="onSosRequestBackendFailed"
	        @record-remove-failed="onSosRequestBackendFailed"
	    >
	    	<!-- :user-name="userName" -->
	    </crud-control>
    	<chat
    		id="chatBox"
    		:user-name="userName"
    		api="/webapi/sosRequest"
    		:model-id="currentRequestForChat.id"
    	></chat>
  	</div>
</template>

<script>
import CrudControl from '@kikiio2020/vue-crud-control';

export default {
    components: {CrudControl},
    props: [
        'isResponder',
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
                    placeholder: "Type",
                    name: "type",
                    id: "type"
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
                    placeholder: "Detail Instructions",
                    name: "detail_instructions",
                    id: "detail_instructions"
                },
            ],
            currentRequestForChat: {
           		chat: '[]'
            },
        }
    },
    methods: {
        insertModel(target) {
            this.$refs.myModelView.insertModel(target);
        },
        chatRequest(row) {
        	this.currentRequestForChat = row;
        	this.$bvModal.show('chatBox');
        },
        completeRequest(row) {
        	const isResponder = this.userId == row.responded_by;
        	const confirmMsg = isResponder ? 
        			'Please confirm request from ' + row.requester + ' is completed.' :
       				'Please confirm your request "' + row.sos_text + '" is completed.'
       				+ (row.is_hujo ? ' (You will be redirected to the Hujo exchange page.)' : '');
        	
        	this.$bvModal.msgBoxConfirm(confirmMsg)
            .then(value => {
            	if (value) {
            		if (!isResponder && row.is_hujo) {
            			//Is Requestor and both party enrolled in Hujo
            			window.location.href = '/hujoPay/' + row.id;
            			
            			return;
            		}
            		
            		//Is responder or either party is enrolled to Hujo
            		axios.put('webapi/sosRequest/completeRequest/' + row.id)
            			.then((response) => {
            				this.$bvModal.msgBoxOk(
           						isResponder ? 
           							'Thanks so much for helping out! We appreciate you being part of our community! Once everyone has signed off this request it will be moved to the History tab and will remain accessible for the next month.':
          							'Thanks for confirming! Once everyone has signed off this request it will be moved to the History tab and will remain accessible for the next month.'
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
        	//TODO Should In Progress requests allowed to be cancelled?
        			
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
        	window.open('/sosRequest/' + data.id + '/inProgress', '_blank');
        },
        onSosRequestBackendFailed(error) {
    		this.$root.$bvToast.toast(error.response.data.message, {
                title: 'In Progress SOS Requests',
                variant: 'danger',
            });
    	},
    },
    computed: {},
    mounted() {
    }
}
</script>