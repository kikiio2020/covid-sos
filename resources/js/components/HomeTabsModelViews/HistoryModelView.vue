<template>
    <model-view
       	id="asks-history-model-view"
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
        api="/webapi/ask"
        gridUrlQuery="/historyView"
        :insertable="false"
        @openFullView="openFullView"
    >
    </model-view>
</template>

<script>
export default {
    components: {},
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
                    name: "requester_approved",
                    id: "requester_approved",
                    
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Vendor",
                    name: "vendor_name",
                    id: "vendor_name"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Vendor Address",
                    name: "vendor_address",
                    id: "vendor_address"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Payment",
                    name: "payment_option",
                    id: "payment_option"
                },
                {
                    fieldType: "model-read-only-field",
                    placeholder: "Special Instructions",
                    name: "special_instructions",
                    id: "special_instructions"
                },
                {
                    fieldType: "model-chat-field",
                    placeholder: "Communication",
                    caption: "Message",
                    name: "chat",
                    id: "chat",
                    readonly: true,
                },
            ],
        }
    },
    methods: {
    	openFullView(data) {
        	window.open('/ask/' + data.id + '/history', '_blank');
        },
    },
    computed: {},
    mounted() {
    }
}
</script>