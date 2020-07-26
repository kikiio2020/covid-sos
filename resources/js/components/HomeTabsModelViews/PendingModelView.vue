<template>
    <div>
    <crud-control
        id="pending-model-view"
        ref="myModelView"
        table-name=""
        :initial-values="{ 
            id: 0, 
            sos_id: null,
            needed_by: null,
            special_instruction: '',
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
            'actions',
        ]"
        :actions="[
        	'edit',
        	'remove',	
        ]"
        :modal-fields="modalFields"
        api="/webapi/sosRequest"
        gridUrlQuery="/pendingsView"
        :insertable="!isResponder"
        @modal-close="actionCancel"
        @async-returns="actionCompletes"
    ></crud-control>
    </div>
</template>

<script>
import CrudControl from '@kikiio2020/vue-crud-control';

export default {
    components: {CrudControl},
    props: [
        'isResponder',
    ],
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const minNeededByDate = new Date(today);
        minNeededByDate.setDate(minNeededByDate.getDate() + 7);
        const maxNeededByDate = new Date(today);
        maxNeededByDate.setMonth(maxNeededByDate.getMonth() + 1);

        return {
        }
    },
    methods: {
        insertModel(target, modalRecordData) {
            this.$refs.myModelView.insertModel(target, modalRecordData);
        },
        onSave() {
        },
        actionCompletes: function() {
        	this.$store.commit('endWorkflow');
        },
        actionCancel: function() {
        	this.$store.commit('workflowCancel');
        },
    },
    computed: {
    	sosArray: function() {
    		return this.$store.state.sosArray.map(sos => {
    			return {
    				value: sos.id,
    				text: sos.name,
    			}
    		})
    	},
    	
    	modalFields: function() {
    		const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const minNeededByDate = new Date(today);
            minNeededByDate.setDate(minNeededByDate.getDate() + 7);
            const maxNeededByDate = new Date(today);
            maxNeededByDate.setMonth(maxNeededByDate.getMonth() + 1);
            
    		return [
                {
                    fieldType: "model-select-field",
                    caption: "SOS",
                    name: "SOS",
                    placeholder: "SOS *",
                    id: "sos_id",
                    required: true,
                    rules: "required",
                    options: this.sosArray,
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-datepicker",
                    caption: "Needed By",
                    placeholder: "Needed By Date:",
                    name: "Needed By Date",
                    id: "needed_by",
                    required: true,
                    rules: "required",
                    min: minNeededByDate,
                    max: maxNeededByDate,
                    locale: 'en-US',
                    labelHelp: '',
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Special Instructions",
                    placeholder: "Special Instructions",
                    name: "special_instruction",
                    id: "special_instruction",
                    class: "my-3 mr-3",
                },
            ];
    	}
    
    },
    mounted() {
    }
}
</script>