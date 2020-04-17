<template>
    <b-container>
        <b-row><b-col>
            <strong>{{placeHolder}}:</strong>
            <model-view
                id="shoplist-item-view"
                ref="shoplistItemView"
                table-name=""
                :initial-values="{ 
                    item_id: 0, 
                    list_id: modelId,
                    description: '',
                    quantity: '',
                    max_dollar: '',
                }"
                :grid-fields="[
                    {
                        key: 'item',
                        sortable: true
                    },
                    {
                        key: 'description',
                        label: 'Description'
                    },
                    {
                        key: 'quantity',
                    },
                    {
                        key: 'max_dollar',
                        label: 'Max $ worth',
                    },
                    'actions',
                ]"
                :modal-fields="modalFields"
                :api="api + '/' + modelId + '/shoplistItem'"
                gridUrlQuery=""
                :searchable="false"
                :insertable="false"
                :deletable="true"                
            >
            </model-view>
        </b-col></b-row>
        <b-row><b-col class="text-center">
            <b-button 
                @click="$refs.shoplistItemView.insertModel($event.target)"
                variant="primary"
            >Add Item</b-button>
        </b-col></b-row>
    </b-container>
</template>
<script>
export default {
    components: {},
    props: [
        'name',
        'id',
        'placeHolder',
        'caption',
        'disabled',
        'modelId',
        'api',
    ],
    data() {
        return {
            modalFields: [
                {   
                    fieldType: "model-search-select-field",
                    caption: "Item",
                    placeholder: "Item *",
                    required: true,
                    rules: "required",
                    name: "Item",
                    id: "item_id",
                    options: this.options,
                    class: "my-3 mr-3",
                },
                {
                    fieldType: "b-form-textarea",
                    caption: "Description",
                    placeholder: "Description",
                    name: "Description",
                    id: "description",
                    rules: "max:255",
                    class: "my-3 mr-3",
                    vBPopover: {
                        trigger: 'hover',
                        content: 'Short description on the item if the selected name is not clear enough.',
                        placement: 'bottom',
                        variant: 'primary'
                    },
                },
                {   
                    fieldType: "b-form-input",
                    caption: "Qualtity",
                    placeholder: "Qualtity (including unit) *",
                    name: "Quantity",
                    id: "quantity",
                    rules: "required|max:255",
                    required: true,
                    class: "my-3 mr-3",
                },
                {   
                    fieldType: "b-form-input",
                    caption: "Maximum To Spend",
                    placeholder: "Maximum $ To Spend *",
                    name: "Maximum To Spend",
                    id: "max_dollar",
                    rules: {
                        required: true,
                        max: 255,
                        regex: /^\d{1,4}(\.\d{1,2})?$/,
                    },
                    required: true,
                    class: "my-3 mr-3",
                    vBPopover: {
                        trigger: 'hover',
                        content: 'Enter maximum amount of money to spend on this item.',
                        placement: 'bottom',
                        variant: 'primary'
                    },
                },
            ],
        }
    },
    methods: {
        /*getShoplistItemOptions() {
            
            axios.get('webapi/item').then( response => {
                this.$refs.shoplistItemView.modalFields[0].options = response.data.data;
            }).catch(err => {
                //{};
            });
        },*/
        insertModel(target) {
            this.$refs.myModelView.insertModel(target);
        },
        onSave() {
        },
    },
    computed: {
        /*shoplistItemOptions: function() {
            axios.get('webapi/item').then( response => {
                return response;
            }).catch(err => {
                return {};
            });
        }*/
    },
    mounted() {
        //this.getShoplistItemOptions();
    }
}
</script>