<template>
<div>
<b-container class="text-left">
<b-row><b-col class="font-weight-bold py-3">Thank you!</b-col></b-row>
<b-row><b-col class="py-3">
    Please use the <button-sos disabled=true></button-sos> 
    button to reach out with getting essentials. 
    Your neighbor who is on this network will be notified 
    by email. Use the 
    <button-invite disabled=true></button-invite>
    button to invite others to join the network.
</b-col></b-row>
<b-row><b-col class="py-3">
    Please click 'Save' to continue.
</b-col></b-row>
<b-row><b-col class="py-3 text-center">
    <b-button variant="primary" @click="onSave" :disabled="saving">Save</b-button>
</b-col></b-row>
<b-row><b-col class="py-3">Stay well!</b-col></b-row>
</b-container>
</div>
</template>
<script>
export default {
    components: {},
    props: ['value'],
    data() {
        return {
            saving: false,
        }
    },
    methods: {
        onSave() {
            this.saving = true;
            const data = {
                status: 3,
            }
            axios.put('/webapi/user/status', data)
                .then((response) => {
                    console.log(response);
                    /*this.$root.$bvToast.toast('Thank you! You are successfully registered.', {
                        title: 'Registration',
                        variant: 'success',
                    });*/
                    window.location = "/home";
                }).catch((error) => {
                    console.log(error);
                    this.saving=false;
                    this.$root.$bvToast.toast('Registration Failed. Please try again later or contact the site admin.', {
                        title: 'Registration',
                        variant: 'danger',
                    });
                }); 
        }
    },
    computed: {},
    mounted() {
        this.$parent.okNext = false;       
    }
}
</script>