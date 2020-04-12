<template>
    <model-view
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
        api="/webapi/sos"
        gridUrlQuery="/history"
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
                    fieldType: "b-form-input",
                    caption: "Request Name",
                    placeHolder: "Request Name",
                    required: true,
                    rules: "required",
                    name: "name",
                    id: "name",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Request Description",
                    placeHolder: "Request Description",
                    name: "description",
                    id: "description",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-calendar",
                    caption: "Needed By",
                    placeHolder: "Needed By Date:",
                    name: "needed_by",
                    id: "needed_by",
                    dateMin: minNeededByDate,
                    dateMax: maxNeededByDate,
                },
                {
                    fieldType: "b-form-input",
                    caption: "Vendor Name",
                    placeHolder: "Vendor Name",
                    name: "vendor_name",
                    id: "vendor_name",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-input",
                    caption: "Vendor Address",
                    placeHolder: "Vendor Address",
                    name: "vendor_address",
                    id: "vendor_address",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-select",
                    caption: "Delivery Option",
                    name: "Delivery Option",
                    placeHolder: "Delivery Option:",
                    id: "delivery_option",
                    required: true,
                    rules: "required",
                    options: this.deliveryOptions,
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-select",
                    caption: "Payment Option",
                    name: "Payment Option",
                    placeHolder: "Payment Option:",
                    id: "payment_option",
                    required: true,
                    rules: "required",
                    options: this.paymentOptions,
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Special Instructions",
                    placeHolder: "Special Instructions",
                    name: "special_instructions",
                    id: "special_instructions",
                    class: "my-3 mr-3",
                },
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