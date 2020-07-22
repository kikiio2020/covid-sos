<template>
<b-container>
    <b-row><b-col class="text-left">
        <crud-control
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
                	key: 'type', 
                    sortable: true,
                },
                {
                    key: 'hujo', 
                    sortable: true,
                },
                {
                    key: 'distance_km', 
                    label: 'KM',
                    sortable: true,
                },
                {
                	key: 'actions',
                	label: '',
                }
                
            ]"
            :actions="[
	        	'details',
	        	{
	        		label: 'Pledge',
	        		event: 'pledgeRequest',
	        		fontawesome: 'fas fa-hands-helping',
	        	},
	       	]"
            :modal-fields="modalFields"
            api="/webapi/sosRequest"
            gridUrlQuery="/nearbyView"
            :insertable="false"
            :table-properties="tableProperties"
			@pledgeRequest="pledgeRequest"            
        >
        </crud-control>
    </b-col></b-row>
</b-container>
</template>

<script>
import CrudControl from '@kikiio2020/vue-crud-control';

export default {
    components: {CrudControl},
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
                        '/webapi/sosRequest/pledgeRequest/' + request.id
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