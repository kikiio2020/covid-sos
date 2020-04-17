<template>
<b-container>
    <b-row><b-col class="text-left">
        <b-button
            variant="primary"
            v-b-popover="{
                        trigger: 'hover',
                        content: 'Select a request and click to help out!',
                        placement: 'right',
                        variant: 'primary'
                    }"
            @click="makePledge"
        >Make a Pledge</b-button>
    </b-col></b-row>
    <b-row><b-col class="text-left">
        <model-view
            id="nearby-model-view"
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
                    key: 'vendor', 
                    sortable: true,
                },
                {
                    key: 'creator', 
                    label: 'User',
                    sortable: true,
                },
                {
                    key: 'distance_km', 
                    label: 'Distance (KM)',
                    sortable: true,
                },

            ]"
            :modal-fields="modalFields"
            api="/webapi/ask"
            gridUrlQuery="/nearbyView"
            :insertable="false"
            :deletable="false"
            :table-properties="tableProperties"
        >
        </model-view>
    </b-col></b-row>
</b-container>
</template>

<script>
export default {
    components: {},
    props: [
        'isResponder',
        'deliveryOptions', 
        'paymentOptions',
        'userId',
    ],
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const minNeededByDate = new Date(today);
        const maxNeededByDate = new Date(today);
        maxNeededByDate.setDate(maxNeededByDate.getDate() + 14);

        return {
            tableProperties: {
                    selectable: true,
                    selectMode: "single",
            },
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
        makePledge() {
            this.$bvModal.msgBoxConfirm(
                'Make a pledge to ' + this.rowsSelected[0].creator + '?', 
                {
                    title: 'Confirm Pledge',
                    headerBgVariant: 'primary',
                    headerTextVariant: 'white',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'primary',
                    okTitle: 'YES',
                    cancelVariant: 'secondary',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                }
            ).then(confirm => {
                if (confirm) {
                    axios.put(
                        '/webapi/ask/' + this.rowsSelected[0].id, 
                        {
                            id: this.rowsSelected[0].id,
                            status: 1,
                            responded_by: this.userId
                        }
                    ).then(response => {
                        this.$root.$bvToast.toast('Pledge sent to user', {
                            title: 'Thank you!',
                            variant: 'success',
                        });
                        this.$bvModal.msgBoxOk(
                            'Thanks for helping out! Your pledge will be sent to the user and you can start ' +
                            'communicating the delivery details by selecting the record in the "In Progress" tab.',
                            {
                                title: 'Pledge Sent',
                                headerBgVariant: 'primary',
                                headerTextVariant: 'white',
                                size: 'sm',
                                buttonSize: 'sm',
                                footerClass: 'p-2',
                                hideHeaderClose: false,
                                centered: true
                            }
                        ).then((confirm) =>{
                            this.$emit('nearbyNewPledged', {
                                id: this.rowsSelected[0].id,
                            });
                        }).catch(err => {
                            // An error occurred
                        }
                        );
                        
                        //TODO switch view to in progress
                    }).catch(error => {
                        console.log(error);
                        this.$root.$bvToast.toast('Pledging failed', {
                            title: 'Pledging',
                            variant: 'danger',
                        });
                    });       
                }
            }).catch(err => {
                // An error occurred
            })
            
            
            
            //console.log(this.$refs.myModelView.rowsSelected);
        
            if (
                this.$refs.myModelView.rowsSelected 
                && this.$refs.myModelView.rowsSelected.length 
            ) {
                const id = this.$refs.myModelView.rowsSelected[0].id;
                this.$emit('nearbyNewpledge', {
                    id: id
                });
            }
        },
        insertModel(target) {
            this.$refs.myModelView.insertModel(target);
        },
        pledge(){

        },
        onSave() {
        },
    },
    computed: {
        rowsSelected: function() {
            if (this.$refs.myModelView && this.$refs.myModelView.rowsSelected ) {
                return this.$refs.myModelView.rowsSelected;
            } else {
                return {};
            }
        },
    },
    mounted() {
    }
}
</script>