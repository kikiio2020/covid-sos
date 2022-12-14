<template>
    <div>
        <strong>{{placeHolder}}:</strong>
        <b-container>
            <b-row><b-col>
                <div 
                    style="overflow-y: scroll; height:7em;"
                    id="chatList"
                    rows="5"
                    readonly
                    class="border border-secondary"
                    v-html="formatedChat()"
                ></div>
            </b-col></b-row>
            <b-row><b-col class="text-right">
                <ValidationProvider :name="caption" rules="max:60" v-slot="validationContext">
                    Enter Message:
                    <b-form-textarea
                        id="inputBox"
                        rows="2"
                        v-model="chatInput"
                        :state="getValidationState(validationContext)"
                        aria-describedby="feedback"
                    ></b-form-textarea>
                    <b-form-invalid-feedback id="feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
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
                </ValidationProvider>
            </b-col></b-row>
        </b-container>
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
        name: String, 
        userName: String,
        caption: String, 
        placeHolder: String, 
        disabled: {
            default: false
        }, 
        api: String,
        value: {},
        modelId: Number
    },
    data() {
        return {
            chatList: '',
            chatInput: '',
            vModel: JSON.parse(this.value)
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
            this.vModel.push({
                user: this.userName,
                isNew: true,
                date: now,
                message: this.chatInput
            });
            axios.put(this.modelApi, {
                id: this.modelId,
                chat: JSON.stringify(this.vModel)
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
            this.vModel.forEach((item) => {
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

            return chatLogFormated;
        }
    },
    computed: {
        modelApi: function () {
            return this.api + '/' + this.modelId;
        },
    },
    mounted() {
        /*axios.get(this.modelApi).then(response => {
            this.modelExists = true;
            if (response.data.data[this.id]) {
                this.imageSrc = this.getModelImageApi();
            }
        }).catch(function(){
        });*/
        /*this.vModel.forEach(function(item){
            console.log(item);
        });*/
    }
}
</script>