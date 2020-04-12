<template>
<b-alert variant="primary" dismissible fade show @dismissed="onDismiss">
    <p>Welcome to the COVID-SOS application. 
    Please read the <a href="/about">About page</a> and guidelines before using this tool.
    </p>
    <p>Thank you! </p>
</b-alert>
</template>
<script>
import { BAlert } from 'bootstrap-vue';
Vue.component('b-alert', BAlert);
export default {
    components: {},
    props: {},
    data() {
        return {}
    },
    methods: {
        onDismiss(){
            axios.put('/webapi/user/removeCache', {
                cache: 'show_welcome'
            }).then((response) => {
                console.log(response);
            }).catch((error) => {
            }); 
        }
    },
    computed: {},
    mounted() {}
}
</script>