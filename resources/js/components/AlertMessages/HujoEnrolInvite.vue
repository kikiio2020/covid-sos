<template>
<b-alert variant="primary" dismissible fade v-model="show" @dismissed="onDismiss">
    <p>
    Did you know we have a community token through which you can 
    exchange when helping someone and receiving help?
    </p>
    <p>Read more <a href="/aboutHujoCoin">here</a></p>
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
           	axios.delete('/webapi/user/showAlertCache/hujo_coin_invite').then((response) => {
                console.log(response);
            }).catch((error) => {
            }); 
        }
    },
    computed: {
    	show: function() {
    		//return this.cachedToShow && this.$store.state.currentHomeTabIndex == 0;
    		return this.cachedToShow && window.location.pathname == '/home';
    	}
    },
    mounted() {
    	axios.get('/webapi/user/showAlertCache/hujo_coin_invite').then((response) => {
            this.cachedToShow = response.data.data.show; 
        }).catch((error) => {
        	if (401 == error.response.status) {
        		this.cachedToShow = false;
        	}
        }); 
    }
}
</script>