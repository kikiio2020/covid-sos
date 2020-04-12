<template>
<div>
    <b-jumbotron border-variant="success" bg-variant="light">
        <b-container>
            <b-row class="text-left">
                Thank you! You are welcome to come back any time when you are able to help. 
                In the mean time please stay safe, and practice social distancing.
            </b-row>
            <b-row>
                <b-col class="pb-1 text-center">
                    <b-button variant="primary" @click="doLogout" class="m-5">Logout</b-button>
                </b-col>
            </b-row>
        </b-container>
    </b-jumbotron>
</div>
</template>
<script>
export default {
    components: {},
    props: {},
    data() {
        return {
            canHelp: null,
            options: [
                { text: 'Yes', value: true },
                { text: 'No', value: false },
            ],
            steps: {
                nextStep: null,
            }
        }
    },
    methods: {
        doLogout() {
            event.preventDefault(); 
            document.getElementById('logout-form').submit();
        }
    },
    computed: {},
    mounted() {
        this.$parent.okNext = false;       
    }
}
</script>