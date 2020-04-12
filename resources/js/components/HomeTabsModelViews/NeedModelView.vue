<template>
    <model-view
        id="need-model-view"
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
            /*delivery_option: {
                selected: null,
                otherText: '',
            },
            payment_option: {
                selected: null,
                otherText: '',
            }*/
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
        gridUrlQuery="/need"
        :insertable="!isResponder"
    >
    </model-view>
</template>

<script>
export default {
    components: {},
    props: [
        'isResponder',
        'deliveryOptions', 
        //'deliveryOptionCaptions', 
        //'deliveryOptionPlaceHolders',
        'paymentOptions',
        //'paymentOptionCaptions',
        //'paymentOptionPlaceHolders',
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
                    placeholder: "Request Name *",
                    required: true,
                    rules: "required",
                    name: "name",
                    id: "name",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Request Description",
                    placeholder: "Request Description",
                    name: "description",
                    id: "description",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-calendar",
                    caption: "Needed By",
                    placeholder: "Needed By Date:",
                    name: "needed_by",
                    id: "needed_by",
                    dateMin: minNeededByDate,
                    dateMax: maxNeededByDate,
                    
                },
                /*{
                    fieldType: "b-form-input",
                    caption: "Vendor Name",
                    placeholder: "Vendor Name",
                    name: "vendor_name",
                    id: "vendor_name",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-input",
                    caption: "Vendor Address",
                    placeholder: "Vendor Address",
                    name: "vendor_address",
                    id: "vendor_address",
                    class: "my-3 mr-3",
                },*/
                {
                    fieldType: "model-select-field",
                    caption: "Delivery Option",
                    name: "Delivery Option",
                    placeholder: "Delivery Option *",
                    id: "delivery_option",
                    required: true,
                    rules: "required",
                    options: this.deliveryOptions,
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "model-select-field",
                    caption: "Payment Option",
                    name: "Payment Option",
                    placeholder: "Payment Option *",
                    id: "payment_option",
                    required: true,
                    rules: "required",
                    options: this.paymentOptions,
                    class: "my-3 mr-3",
                },
                /*{
                    type: "modelListWOther",
                    caption: "Delivery Options",
                    name: "Delivery Option",
                    id: "delivery_option",
                    required: true,
                    rules: "required",
                    options: this.deliveryOptions,
                    optionCaptions: this.deliveryOptionCaptions,
                    optionPlaceHolders: this.deliveryOptionPlaceHolders,
                    optionName: "Delivery Instruction",
                    optionId: "delivery_instruction",
                },
                {
                    type: "modelListWOther",
                    caption: "Payment Option",
                    name: "Payment Option",
                    id: "payment_option",
                    required: true,
                    rules: "required",
                    options: this.paymentOptions,
                    optionCaptions: this.paymentOptionCaptions,
                    optionPlaceHolders: this.paymentOptionPlaceHolders,
                    optionName: "Payment Instruction",
                    optionId: "payment_instruction",
                },*/
                {
                    fieldType: "b-form-textarea",
                    caption: "Special Instructions",
                    placeholder: "Special Instructions",
                    name: "special_instructions",
                    id: "special_instructions",
                    class: "my-3 mr-3",
                },

                //TODO upload receipt

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