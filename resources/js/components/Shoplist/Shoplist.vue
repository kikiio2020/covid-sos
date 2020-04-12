<template>
<div>
<b-container>
    <b-row><b-col>
        <h3>Add / Manage Grocery List</h3>
    </b-col></b-row>

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
            {
                key: 'description', 
                sortable: true,
            },
            'actions',
        ]"
        :modal-fields="modalFields"
        api="/webapi/shoplist"
        gridUrlQuery=""
        :insertable="true"
        :deletable="true"
        modal-size='lg'
    >
    </model-view>
    </b-col></b-row>
</b-container>
</div>
</template>
<script>
export default {
    components: {},
    props: ['items'],
    data() {
        return {
            modalFields: [
                {   
                    fieldType: "b-form-input",
                    caption: "Grocery List Name",
                    placeholder: "Grocery List Name *",
                    required: true,
                    rules: "required|max:255",
                    name: "name",
                    id: "name",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Grocery List Description",
                    placeholder: "Grocery List Description",
                    rules: "max:255",
                    name: "description",
                    id: "description",
                    rows: "3",
                    maxRows: "6",
                    class: "my-3 mr-3",
                },
                {   
                    fieldType: "b-form-input",
                    caption: "Vendor Name",
                    placeholder: "Vendor Name *",
                    required: true,
                    rules: "required|max:255",
                    name: "vendor_name",
                    id: "vendor_name",
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-input",
                    caption: "Vendor Address",
                    placeholder: "Vendor Address *",
                    required: true,
                    rules: "required|max:255",
                    name: "vendor_address",
                    id: "vendor_address",
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
    },
    computed: {},
    mounted() {
    }
}
</script>