<template>
<div>
<b-container>
    <!-- <b-row><b-col class="text-left">
        <b-button
            variant="primary"
            v-b-popover="{
                        trigger: 'hover',
                        content: 'Select an SOS and click to send the request',
                        placement: 'right',
                        variant: 'primary'
                    }"
            @click="sendRequest"
        >Make a Request</b-button>
    </b-col></b-row>
     -->
    <!--Profile Update-->
    <b-row><b-col>
    <model-view
        id="shoplist-view"
        ref="myModelView"
        table-name=""
        :initial-values="{ 
            id: 0, 
            name: '', 
            description: '',
            vendor_name: '',
            vendor_address: '',
            shoplist: {},
        }"
        :grid-fields="[
            {
                key: 'name',
                sortable: true,
            },
            'actions',
        ]"
        :actions="[
        	{
        		text: 'Make Request',
        		event: 'make_request',
        		icon: 'chat-square',
        	},
        	'edit',
        	'remove',	
        ]"
        v-on:make_request="makeRequest"
        :modal-fields="modalFields"
        api="/webapi/sos"
        gridUrlQuery="/sosView"
        modal-size='lg'
        :table-properties="tableProperties"
        @asyncReturns="actionCompletes"
    >
    </model-view>
    </b-col></b-row>
</b-container>
</div>
</template>
<script>
export default {
    components: {},
    props: [
        'deliveryOptions', 
        'paymentOptions',
    ],
    data() {
        return {
            tableProperties: {
                    selectable: false,
                    selectMode: "single",
                    /*vBPopover: {
                        trigger: 'hover',
                        content: 'Select an SOS to send the request to the community',
                        placement: 'bottom',
                        variant: 'primary'
                    } */      
            },
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
                    vBPopover: {
                        trigger: 'hover',
                        content: 'Give your SOS a name for others to respond and yourself to remember next time.',
                        placement: 'top',
                        variant: 'primary'
                    },
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Request Description",
                    placeholder: "Request Description",
                    name: "description",
                    id: "description",
                    class: "my-3 mr-3",
                    vBPopover: {
                        trigger: 'hover',
                        content: 'Give it a short desciption for more clarity',
                        placement: 'top',
                        variant: 'primary'
                    },
                },
                {   
                    fieldType: "b-form-input",
                    caption: "Store Name",
                    placeholder: "Store Name *",
                    required: true,
                    rules: "required|max:255",
                    name: "vendor_name",
                    id: "vendor_name",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-input",
                    caption: "Store Address",
                    placeholder: "Store Address *",
                    required: true,
                    rules: "required|max:255",
                    name: "vendor_address",
                    id: "vendor_address",
                    class: "my-3 mr-3",
                    vBPopover: {
                        trigger: 'hover',
                        content: 'Please keep to cloest store to your address to minimize delivery distance',
                        placement: 'top',
                        variant: 'primary'
                    },
                },
                {
                    fieldType: "model-select-field",
                    caption: "Delivery Option",
                    name: "Delivery Option",
                    placeholder: "Delivery Option *",
                    id: "delivery_option",
                    //required: true,
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
                    //required: true,
                    rules: "required",
                    options: this.paymentOptions,
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-input",
                    caption: "Compensation",
                    placeholder: "Helper Compensation",
                    rules: { regex:/^\d{1,4}(\.\d{1,2})?$/ },
                    name: "compensation",
                    id: "compensation",
                    class: "my-3 mr-3",
                    vBPopover: {
                        trigger: 'hover',
                        content: 'Enter money amount eg) 10.50. As a reference, hired couriers earn about $10 each trip.',
                        placement: 'top',
                        variant: 'primary'
                    },
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Other Instructions",
                    placeholder: "Other Instructions",
                    name: "other_instructions",
                    id: "other_instructions",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "shoplist-items-field",
                    caption: "Grocery List Items",
                    placeHolder: "Grocery List Items",
                    name: "shoplist",
                    id: "shoplist",
                    requireModel: true,
                },
            ],
        }
    },
    methods: {
        makeRequest: function(sos) {
        	this.$emit('sosCreatesNewAsk', {
                sos_id: sos.id
            });
        },
        actionCompletes: function() {
        	this.$store.commit('endWorkflow');
        }
    },
    computed: {
    },
    mounted() {
    }
}
</script>