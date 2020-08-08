<template>
<b-alert variant="primary" dismissible fade v-model="show" @dismissed="onDismiss">
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
        return {
        	cachedToShow: false,
        }
    },
    methods: {
        onDismiss(){
        	axios.delete('/webapi/user/showAlertCache/show_welcome').then((response) => {
                console.log(response);
            }).catch((error) => {
            });
        }
    },
    computed: {
    	show: function() {
    		return this.cachedToShow && window.location.pathname == '/home';
    	}
    },
    mounted() {
    	axios.get('/webapi/user/showAlertCache/show_welcome').then((response) => {
            this.cachedToShow = response.data.data.show; 
        }).catch((error) => {
        	if (401 == error.response.status) {
        		this.cachedToShow = false;
        	}
        });
    }
}
</script>