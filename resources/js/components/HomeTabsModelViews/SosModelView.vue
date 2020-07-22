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
    <!-- <crud-control  
    	***TODO*** 
    	It seems vee-validate is preventing the pacakage from 
    	seeing state mutations of the hasRequireModelField computed array
    	inside the v-for loop of modal-field. I suspect somehow more is needed
    	inside plugin.js in the packahe to properly make ModalField properly visible 
    	in the main component CrudControl.
    	Vue 3 and Bootstrap 5 also coming up so might make more sense to defer 
    	further investigation and major FE work until we get to the new versions
    -->
    <model-view-2
        id="shoplist-view"
        ref="myModelView"
        table-name=""
        :initial-values="{ 
            id: 0, 
            name: '', 
            type: null,
            description: '',
            shoplist: {},
            detail_instructions: '',
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
        		label: 'Make Request',
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
        @async-returns="actionCompletes"
        @record-created="reloadSosArray"
        @record-removed="reloadSosArray"
    ></model-view-2>
    
    </b-col></b-row>
</b-container>
</div>
</template>
<script>
import CrudControl from '@kikiio2020/vue-crud-control';

export default {
    components: {CrudControl},
    props: [
        'types',
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
                    caption: "SOS Name",
                    placeholder: "SOS Name *",
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
                    fieldType: "model-select-field",
                    caption: "Type",
                    name: "Type",
                    placeholder: "Type *",
                    id: "type",
                    rules: "required",
                    options: this.types,
                    class: "my-3 mr-3",
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
                    fieldType: "b-form-textarea",
                    caption: "Detail Instructions",
                    placeholder: "Detail Instructions",
                    name: "detail_instructions",
                    id: "detail_instructions",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "shoplist-items-field",
                    caption: "Grocery List Items",
                    placeHolder: "Grocery List Items",
                    name: "shoplist",
                    id: "shoplist",
                    requireModel: true,
                    conditions: {
                    	type: '0',
                    },
                },
            ],
        }
    },
    methods: {
        makeRequest: function(sos) {
        	this.$emit('sosCreatesNewRequest', {
                sos_id: sos.id
            });
        },
        actionCompletes: function() {
        	this.$store.commit('endWorkflow');
        },
        reloadSosArray: function() {
        	this.$store.dispatch('loadSosArray');
        }
    },
    computed: {
    },
    mounted() {
    }
}
</script>