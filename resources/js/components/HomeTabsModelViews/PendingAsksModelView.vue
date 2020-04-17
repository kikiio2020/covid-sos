<template>
    <model-view
        id="asks-pending-model-view"
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
            {
                key: 'status_txt',
                label: 'Status',
                sortable: true,
            },
            'actions',
        ]"
        :modal-fields="modalFields"
        api="/webapi/ask"
        gridUrlQuery="/pendingsView"
        :insertable="!isResponder"
    >
    </model-view>
</template>

<script>
export default {
    components: {},
    props: [
        'isResponder',
        'sosOptions',
    ],
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const minNeededByDate = new Date(today);
        minNeededByDate.setDate(minNeededByDate.getDate() + 7);
        const maxNeededByDate = new Date(today);
        maxNeededByDate.setMonth(maxNeededByDate.getMonth() + 1);

        return {
            modalFields: [
                {
                    fieldType: "model-select-field",
                    caption: "SOS",
                    name: "SOS",
                    placeholder: "SOS *",
                    id: "sos_id",
                    required: true,
                    rules: "required",
                    options: this.sosOptions,
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-calendar",
                    caption: "Needed By",
                    placeholder: "Needed By Date:",
                    name: "needed_by",
                    id: "needed_by",
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

                //TODO upload receipt

            ],
        }
    },
    methods: {
        insertModel(target, modalRecordData) {
            this.$refs.myModelView.insertModel(target, modalRecordData);
        },
        onSave() {
        },
    },
    computed: {},
    mounted() {
    }
}
</script>