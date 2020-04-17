<template>
    <model-view
        id="asks-in-progress-model-view"
        ref="myModelView"
        table-name=""
        :initial-values="{ 
            id: 0, 
            name: '', 
            description: '',
            needed_by: new Date(),
            vendor_name: '',
            vendor_address: '',
            delivery_option: null,
            payment_option: null,
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
                key: 'status_txt',
                label: 'Status',
                sortable: true,
            },
            'actions',
        ]"
        :modal-fields="modalFields"
        api="/webapi/ask"
        gridUrlQuery="/inProgressView"
        :insertable="false"
        :deletable="false"
        :user-name="userName"
    >
    </model-view>
</template>

<script>
export default {
    components: {},
    props: [
        'isResponder',
        'deliveryOptions', 
        'paymentOptions',
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
                /*{
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
                },*/
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
                {
                    fieldType: "model-chat-field",
                    placeholder: "Communication",
                    caption: "Message",
                    name: "chat",
                    id: "chat",
                },
                {
                    fieldType: "selectRadioButtons"
                }
            ],
        }
    },
    methods: {
        insertModel(target) {
            this.$refs.myModelView.insertModel(target);
        },
        onSave() {
        },
    },
    computed: {},
    mounted() {
    }
}
</script>