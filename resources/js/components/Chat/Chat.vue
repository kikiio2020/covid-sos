<template>
	<div>
	<b-modal 
		:id="id" 
		title="Chat" 
		ok-title="Send" 
		@ok="submitChat"
		:lazy="true"
		@change="modalToShow"
	><div>
        <strong>Communication:</strong>
        
        <b-container>
            <b-row><b-col>
                <chat-log
                	:chat="JSON.stringify(chat)"
                ></chat-log>
                <!-- 
                <div 
                    style="overflow-y: scroll; height:7em;"
                    id="chatList"
                    rows="5"
                    class="border border-secondary"
                    v-html="formatedChat()"
                ></div>
                -->
            </b-col></b-row>
            <b-row v-if="!readonly"><b-col >
                <ValidationProvider name="Message" rules="max:60" v-slot="validationContext">
                    <b-form-textarea
                        id="inputBox"
                        rows="2"
                        v-model="chatInput"
                        :state="getValidationState(validationContext)"
                        aria-describedby="feedback"
                        placeholder="Enter Message"
                    ></b-form-textarea>
                    <b-form-invalid-feedback id="feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                    <!-- 
                    <b-button
                        size="sm"
                        style="width:6em"
                        variant="primary"
                        class="my-2"
                        @click="submitChat"
                        :disabled="!getValidationState(validationContext)"
                    >
                        <b-icon-chat></b-icon-chat>
                    </b-button>
                     -->
                </ValidationProvider>
            </b-col></b-row>
        </b-container>
        
	    </div>
	</b-modal>
	</div>
</template>
<script>
import { BSpinner, BIcon, BButton, BFormFile } from 'bootstrap-vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, max } from 'vee-validate/dist/rules';

Vue.component('b-icon', BIcon);
Vue.component('b-button', BButton);

extend('required', {
    ...required,
    message: "{_field_} is required"
});

export default {
    components: {ValidationProvider, ValidationObserver},
    props: {
        id: String,
        //name: String, 
        userName: String,
        //caption: String, 
        //placeholder: String, 
        disabled: {
            default: false,
        }, 
        api: String,
        /*value: {
        	default: () => '[]',
        },*/
        modelId: Number,
        readonly: false,
    },
    data() {
        return {
            chat: [],
        	chatList: '',
            chatInput: '',
        }
    },
    methods: { 
        getValidationState({ dirty, validated, valid = null, invalid }) {
            return dirty || validated ? valid : null;
        },
        formatDate(date) {
            return date.getMonth() + '/' + date.getDate() + '/' +
                date.getFullYear() + ' ' + date.getHours() + ':'
                + date.getMinutes() + ':' + date.getSeconds();
        },
        submitChat() {
            const now = new Date();
            this.chat.push({
                user: this.userName,
                isNew: true,
                date: now,
                message: this.chatInput
            });
            axios.put(this.modelApi, {
                id: this.modelId,
                chat: JSON.stringify(this.chat)
            }).then(response => {
                this.chatInput = '';
            }).catch(error => {
                this.$root.$bvToast.toast('Error submitting chat.', {
                    title: Chat,
                    variant: 'danger',
                });
            });        
        },
        formatedChat() {
            var chatLogFormated = '';
            this.chat.forEach((item) => {
                var chatFormated = 
                    '<small>' + item.user
                    + ' &lt; <span class="font-italic">' 
                    + this.formatDate(new Date(item.date)) 
                    + '</span> &gt;:<br>' 
                    + item.message + '</small>';
                if (item.isNew) {
                    chatFormated =  '<b>' + chatFormated + '</b>';
                }
                chatLogFormated = chatFormated + '<br>' + chatLogFormated;
            });
            chatLogFormated = chatLogFormated ? chatLogFormated : '<small class="text-muted">This is start of your conversation...</small>';
            
            return chatLogFormated;
        },
        modalToShow() {
        	axios.get(this.modelApi)
        		.then(response => {
        			this.chat = JSON.parse(response.data.data.chat);
        		})
        }
    },
    computed: {
        modelApi: function () {
            return this.api + '/' + this.modelId;
        },
    },
    mounted() {
    }
}
</script>