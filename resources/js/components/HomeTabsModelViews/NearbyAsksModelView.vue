<template>
<b-container>
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
                    key: 'distance_km', 
                    label: 'Distance (KM)',
                    sortable: true,
                },
                'actions',
            ]"
            :actions="[
	        	'details',
	        	{
	        		text: 'Pledge',
	        		event: 'pledgeRequest',
	        		fontawesome: 'fas fa-hands-helping',
	        	},
	       	]"
            :modal-fields="modalFields"
            api="/webapi/ask"
            gridUrlQuery="/nearbyView"
            :insertable="false"
            :table-properties="tableProperties"
			@pledgeRequest="pledgeRequest"            
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
                    //selectable: true,
                    //selectMode: "single",
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
            ],
        }
    },
    methods: {
        pledgeRequest(request) {
            this.$bvModal.msgBoxConfirm(
                'Make a pledge to ' + request.creator + '?', 
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
                        '/webapi/ask/pledgeRequest' + request.id
                        /*, 
                        {
                            id: request.id,
                            status: 1,
                            responded_by: this.userId
                        }*/
                    ).then(response => {
                        this.$root.$bvToast.toast('Pledge sent to user', {
                            title: 'Thank you!',
                            variant: 'success',
                        });
                        this.$bvModal.msgBoxOk(
                            'Thanks for helping out! Your pledge will be sent to the user and you can start ' +
                            'communicating the delivery details by going to the request Details in the "In Progress" tab.',
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
                                id: request.id,
                            });
                        }).catch(err => {
                            // An error occurred
                       	});
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
        
            //this.$emit('nearbyNewpledge');
            /*if (
                this.$refs.myModelView.rowsSelected 
                && this.$refs.myModelView.rowsSelected.length 
            ) {
                const id = this.$refs.myModelView.rowsSelected[0].id;
                this.$emit('nearbyNewpledge', {
                    id: id
                });
            }*/
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