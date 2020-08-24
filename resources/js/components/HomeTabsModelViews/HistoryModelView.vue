<template>
    <crud-control
       	id="history-model-view"
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
            user_approved: new Date(),
			responder_approved: new Date(),
        }"
        :grid-fields="[
            {
                key: 'fulfilled_date',
                label: 'Fulfilled',
                sortable: true,
            },
            {
                key: 'sos_text',
                label: 'Name', 
                sortable: true,
            },
            {
                key: 'requester',
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
        gridUrlQuery="/historyView"
        :insertable="false"
        @openFullView="openFullView"
        @record-load-failed="onSosRequestBackendFailed"
        @record-create-failed="onSosRequestBackendFailed"
        @record-update-failed="onSosRequestBackendFailed"
        @record-remove-failed="onSosRequestBackendFailed"
    >
    </crud-control>
</template>

<script>
import CrudControl from '@kikiio2020/vue-crud-control';

export default {
    components: {CrudControl},
    props: [
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
                    placeholder: "Scheduled",
                    name: "needed_by",
                    id: "needed_by",
                    
                },
                {   
                    fieldType: "model-read-only-field",
                    placeholder: "Request Description",
                    name: "sos_description",
                    id: "sos_description"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Signed off by responder",
                    name: "responder_approved",
                    id: "responder_approved",
                    
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Signed off by requester",
                    name: "user_approved",
                    id: "user_approved",
                    label: "Requestor Approved",
                    
                },
            ],
        }
    },
    methods: {
    	openFullView(data) {
        	window.open('/sosRequest/' + data.id + '/history', '_blank');
        },
        onSosRequestBackendFailed(error) {
    		this.$root.$bvToast.toast(error.response.data.message, {
                title: 'SOS Requests',
                variant: 'danger',
            });
    	}, 
    },
    computed: {},
    mounted() {
    }
}
</script>