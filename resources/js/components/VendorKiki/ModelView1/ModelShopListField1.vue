<template>
    <b-container>
        <b-row><b-col>
            <strong>{{placeHolder}}:</strong>
            <model-view
                id="shoplist-item-view"
                ref="shoplistItemView"
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
                        key: 'item',
                        sortable: true
                    },
                    {
                        key: 'desc',
                        label: 'Description'
                    },
                    {
                        key: 'qty',
                    },
                    {
                        key: 'max_dollar',
                        label: 'Max $ worth',
                    },
                    'actions',
                ]"
                :modal-fields="modalFields"
                api="/webapi/ShoplistItem"
                :gridUrlQuery="'/' + this.modelId"
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
        'modelId'
    ],
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const minNeededByDate = new Date(today);
        const maxNeededByDate = new Date(today);
        maxNeededByDate.setDate(maxNeededByDate.getDate() + 14);

        return {
            shoplistItems: [],
            modalFields: [
                {   
                    type: "select",
                    caption: "Item",
                    placeHolder: "Item",
                    required: true,
                    rules: "required",
                    name: "Item",
                    id: "Item",
                    options: this.shoplistItems
                },
                {   
                    type: "input",
                    caption: "Request Name",
                    placeHolder: "Request Name",
                    required: true,
                    rules: "required",
                    name: "name",
                    id: "name"
                },
                {
                    type: "textarea",
                    caption: "Request Description",
                    placeHolder: "Request Description",
                    name: "description",
                    id: "description"
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
        axios.get('webapi/shoplistItems').then( response => {
            this.shoplistItems = response;
        });
    }
}
</script>