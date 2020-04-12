<template>
<div>
<b-container class="text-left">
<b-row><b-col class="font-weight-bold py-3">Thank you!</b-col></b-row>
<b-row><b-col class="py-3">
    <p>for having a good heart to help others in need in this difficult time.
    You will be notified by email when a neighbor sends out an SOS to get essentials. Details will be on the email.
    </p>
    <p>
    If at any time you have become ill, please change your status on the <a href='/profile'>Profile</a> page
    and follow the instruction there.
    </p>
    <p>
    In the mean time please use the
    <button-invite disabled=true></button-invite>
    button to help our network grow. This tool will not work with just a few of us here.
    </p>
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
    props: [],
    data() {
        return {
            saving: false,
        }
    },
    methods: {
        onSave() {
            this.saving = true;
            const data = {
                status: 1
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